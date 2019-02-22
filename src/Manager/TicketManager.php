<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 18/02/2019
 * Time: 14:22
 */

namespace App\Manager;

use App\Entity\Ticket;
use App\Repository\CustomerRepository;
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


    public function __construct(\Swift_Mailer $swift_Mailer, ObjectManager $manager, CustomerRepository $customerRepository, SessionInterface $session)
    {

        $this->swift_Mailer = $swift_Mailer;
        $this->manager = $manager;
        $this->customerRepository = $customerRepository;
        $this->session = $session;
    }

    public function save(Ticket $ticket)
    {
        if($ticket->getCustomer()->getId()){
            $ticket->setCustomer($this->manager->merge($ticket->getCustomer()));
        }

        $ticket->setCreatedAt(new \DateTime());
        $ticket->setReference(date_format($ticket->getCreatedAt(), 'Ymd') . $ticket->getCustomer()->getStripeCustomerId());



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
}