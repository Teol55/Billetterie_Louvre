<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Ticket;
use App\Entity\Visitor;
use App\Form\ContactFormType;
use App\Form\CustomerFormType;
use App\Form\OrderFormType;
use App\Form\VisitorFormType;
use App\Manager\TicketManager;
use App\Repository\CustomerRepository;
use App\Repository\TicketRepository;
use App\Repository\VisitorRepository;
use App\Service\CalculatePriceVisitor;
use App\Service\StripeService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class TicketController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(Request $request, TicketManager $ticketManager)
    {

        $ticket = $ticketManager->initializeTicket();

        $form = $this->createForm(OrderFormType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_visitor',[
            'ticket'=> $ticket
            ]);
        }

        return $this->render('homepage.html.twig', [
            'orderForm' => $form->createView()

        ]);
    }

    /**
     * @Route("/visiteurs", name="app_visitor")
     */
    public function visitor(TicketManager $ticketManager, Request $request,CalculatePriceVisitor $priceVisitor)
    {
        $ticket = $ticketManager->getCurrentTicket();

        if (!$ticket->needAnotherVisitor()) {
            return $this->redirectToRoute('app_customer',[
                            'ticket'=> $ticket
            ]);
        }


        $form = $this->createForm(VisitorFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $visitor = $form->getData();
            $ticket->addVisitor($visitor);
            $priceVisitor->visitorPrice($ticket);
            return $this->redirectToRoute('app_visitor',[
                'ticket'=> $ticket]);
        }

        return $this->render('visitor.html.twig', [
            'visitorForm' => $form->createView(),
            'ticket'=> $ticket
        ]);
    }


    /**
     * @Route("/Adresse", name="app_customer")
     */
    public function customer(TicketRepository $repository, Request $request, TicketManager $ticketManager)

    {
        $form = $this->createForm(CustomerFormType::class);

        $form->handleRequest($request);
        $ticket = $ticketManager->getCurrentTicket();

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();



            $testCustomer = $ticketManager->findCustomer($customer->getAdresseEmail());

            if (!$testCustomer) {
                $ticket->setCustomer($customer);
            } else {
                $ticket->setCustomer($testCustomer);
            }


            return $this->redirectToRoute('app_payment');

        }

        return $this->render('customer.html.twig', [
            'customerForm' => $form->createView(),
            'ticket'=> $ticket

        ]);
    }

    /**
     * @Route("/payment", name="app_payment")
     */
    public function payment(Request $request, StripeService $stripeClient, TicketManager $ticketManager)

    {


        $ticket = $ticketManager->getCurrentTicket();
        $error = false;



        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');

            try {
                $ticketManager->chargeCustomer($ticket, $token);
            } catch (\Stripe\Error\Card $e)
            {
                $error='Il y a un probleme de Paiement avec votre carte:'.$e->getMessage();
            }
            if(!$error) {
                $ticketManager->save($ticket);

                $ticketManager->sendMessage($ticket);
                $this->addFlash('success', 'Paiement Validé! Bonne visite!');

                return $this->redirectToRoute('app_confirmation');
            }
        }


        return $this->render('creditCard.html.twig', [
            'ticket' => $ticket,
            'error' => $error,


        ]);
    }

    /**
     * @Route("/confirmation", name="app_confirmation")
     */
    public function confirmation(TicketManager $ticketManager)

    {
        $ticket = $ticketManager->getCurrentTicket();
        $ticketManager->closeTicket();


        return $this->render('confirmation.html.twig', [
            "ticket" => $ticket
        ]);
    }
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request,TicketManager $ticketManager)
    {


        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message=$form->getData();

            $ticketManager->contactMessage($message);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('app_homepage',[

            ]);
        }

        return $this->render('contact.html.twig', [
            'contactForm' => $form->createView()

        ]);
    }
    /**
     * @Route("/test", name="app_test")
     */
    public function test(Request $request)
    {







        return $this->render('test/test.html.twig', [


        ]);
    }
}
