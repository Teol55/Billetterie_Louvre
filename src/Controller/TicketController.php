<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Ticket;
use App\Entity\Visitor;
use App\Form\CustomerFormType;
use App\Form\OrderFormType;
use App\Form\VisitorFormType;
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
    public function index(Request $request, VisitorRepository $Repository, SessionInterface $session)
    {
        $form = $this->createForm(OrderFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ticket = $form->getData();
            $session->set('ticket', $ticket);
            $session->set('numberVisitor', $ticket->getNumberPlace());


            return $this->redirectToRoute('app_visitor', [

            ]);
        }

        return $this->render('homepage.html.twig', [
            'orderForm' => $form->createView()

        ]);
    }

    /**
     * @Route("/visiteurs", name="app_visitor")
     */
    public function visitor(TicketRepository $repository, SessionInterface $session, Request $request)
    {
        /** @var Ticket $ticket */
        $ticket = $session->get('ticket');

        if (!$ticket->needAnotherVisitor()) {
            return $this->redirectToRoute('app_customer');
        }


        $form = $this->createForm(VisitorFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $visitor = $form->getData();
            $ticket->addVisitor($visitor);
            return $this->redirectToRoute('app_visitor');
        }

        return $this->render('visitor.html.twig', [
            'visitorForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/test", name="app_test")
     */
    public function test(TicketRepository $repository, SessionInterface $session, Request $request, CalculatePriceVisitor $priceVisitor)

    {
        $ticket = $session->get('ticket');
        $priceVisitor->visitorPrice($ticket);
        dd($session);
        return $this->render('test.html.twig', [


        ]);
    }

    /**
     * @Route("/Adresse", name="app_customer")
     */
    public function customer(TicketRepository $repository, SessionInterface $session, Request $request)

    {
        $form = $this->createForm(CustomerFormType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $ticket = $session->get('ticket');
            $ticket->setCustomer($customer);
            return $this->redirectToRoute('app_payment', [


            ]);

        }

        return $this->render('customer.html.twig', [
            'customerForm' => $form->createView()

        ]);
    }

    /**
     * @Route("/payment", name="app_payment")
     */
    public function payment( SessionInterface $session, Request $request, CalculatePriceVisitor $priceVisitor,StripeService $stripeClient,ObjectManager $em,\Swift_Mailer $mailer)

    {
        $ticket = $session->get('ticket');
        $priceVisitor->visitorPrice($ticket);

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');




            /** @var Customer $customer */

            $customer = $ticket->getCustomer();

            if (!$customer->getStripeCustomerId()) {


                $stripeClient->createCustomer($customer, $token);
            } else {

                $stripeClient->updateCustomerCard($customer, $token);
            }
            /**@var Visitor $visitor */
            foreach ($ticket->getVisitors() as $visitor) {
                $em->persist($visitor);
                $stripeClient->createInvoiceItem(
                    $visitor->getPrice() * 100,
                    $customer,
                    $visitor->getName()." ".$visitor->getFirstName()

                );
            }
            $stripeClient->createInvoice($customer, true);
            $ticket->setCreatedAt(new \DateTime());
            $ticket->setReference(date_format($ticket->getCreatedAt(),'Ymd').$customer->getStripeCustomerId());
            $em->persist($ticket->getCustomer());

            $em->persist($ticket);
            $em->flush();
            $message=(new \Swift_Message('Confirmation de Commande'))
                ->SetFrom('Billettrie@louvre.fr')
                ->setTo($customer->getAdresseEmail())
                ->setBody($this->renderView('email.html.twig',
                    ['ticket'=> $ticket]),'text/html');
            $mailer->send($message);

            $this->addFlash('success', 'Order Complete! Yay!');

            return $this->redirectToRoute('app_confirmation');

        }



        return $this->render('creditCard.html.twig', [
            'ticket'=> $ticket,


        ]);
    }

    /**
     * @Route("/confirmation", name="app_confirmation")
     */
    public function confirmation( SessionInterface $session, Request $request, CalculatePriceVisitor $priceVisitor,StripeService $stripe)

    {
        $ticket = $session->get('ticket');


        return $this->render('confirmation.html.twig', [
            'ticket'=> $ticket,


        ]);
    }
}
