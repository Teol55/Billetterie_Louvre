<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 18/02/2019
 * Time: 14:22
 */

namespace App\Manager;

use App\Entity\Ticket;
use App\Entity\Customer;
use App\Entity\Visitor;
use App\Repository\CustomerRepository;
use App\Service\StripeService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TicketManager extends AbstractController
{
    const SESSION_TICKET = 'ticket';

    /**
     * @var \Swift_Mailer
     */
    private $swift_Mailer;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var StripeService
     */
    private $stripeClient;


    public function __construct(\Swift_Mailer $swift_Mailer, ObjectManager $manager, CustomerRepository $customerRepository, SessionInterface $session,StripeService $stripeClient)
    {

        $this->swift_Mailer = $swift_Mailer;
        $this->manager = $manager;
        $this->customerRepository = $customerRepository;
        $this->session = $session;
        $this->stripeClient = $stripeClient;
    }

    public function save(Ticket $ticket)
    {
        if($ticket->getCustomer()->getId()){
            $ticket->setCustomer($this->manager->merge($ticket->getCustomer()));
        }

        $ticket->setCreatedAt(new \DateTime());
        $ticket->setReference($ticket->getCreatedAt()->getTimestamp() . $ticket->getCustomer()->getStripeCustomerId());



        $this->manager->persist($ticket);
        $this->manager->flush();

    }

    public function sendMessage(Ticket $ticket)
    {
        $message = (new \Swift_Message('Confirmation de Commande'))
            ->SetFrom('Billettrie@louvre.fr')
            ->setTo($ticket->getCustomer()->getAdresseEmail())
            ->setBody($this->renderView('email.html.twig',
                ['ticket' => $ticket]), 'text/html');
        $this->swift_Mailer->send($message);

    }

    public function findCustomer($adresseEmail)
    {
        return $this->customerRepository->findOneBy(['adresseEmail'=>$adresseEmail]);

    }

    /**
     * @return Ticket
     */
    public function getCurrentTicket()
    {
        $ticket = $this->session->get(TicketManager::SESSION_TICKET);
        if(!$ticket instanceof  Ticket){
            throw new NotFoundHttpException();
        }
        return $ticket;
    }

    /**
     * @return Ticket
     */
    public function initializeTicket()
    {
        $ticket = new Ticket();
        $this->session->set(TicketManager::SESSION_TICKET, $ticket);
        return $ticket;
    }
    public function closeTicket()
    {
        return $this->session->clear();
    }
    public function contactMessage($form)
    {

        $message = (new \Swift_Message('Confirmation de Commande'))
            ->SetFrom($form["emailContact"])
            ->setTo('emaildulouvre@louvre.fr')
            ->setBody($this->renderView('emailContact.html.twig',
                ['message' => $form['messageContact'],
                    'nom'=>$form['nameContact'],
                    'email'=>$form['emailContact']
                ]), 'text/html');
        $this->swift_Mailer->send($message);

    }
    public function chargeCustomer(Ticket $ticket, $token)
    {
        /** @var Customer $customer */

        $customer = $ticket->getCustomer();

        if (!$customer->getStripeCustomerId()) {

            $this->stripeClient->createCustomer($customer, $token);
        } else {

            $this->stripeClient->updateCustomerCard($customer, $token);
        }
        /**@var Visitor $visitor */
        foreach ($ticket->getVisitors() as $visitor) {

            $this->stripeClient->createInvoiceItem(
                $visitor->getPrice() * 100,
                $customer,
                $visitor->getName() . " " . $visitor->getFirstName()

            );
        }

        $this->stripeClient->createInvoice($customer, true);

    }
}