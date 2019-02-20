<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 18/02/2019
 * Time: 14:22
 */

namespace App\Model;

use App\Entity\Ticket;
use App\Repository\CustomerRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TicketManager extends AbstractController
{

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



    public function __construct(\Swift_Mailer $swift_Mailer, ObjectManager $manager, CustomerRepository $customerRepository)
    {

        $this->swift_Mailer = $swift_Mailer;
        $this->manager = $manager;
        $this->customerRepository = $customerRepository;
    }

    public function save(Ticket $ticket)
    {
        foreach ($ticket->getVisitors() as $visitor) {
            $this->manager->persist($visitor);
        }

        $ticket->setCreatedAt(new \DateTime());
        $ticket->setReference(date_format($ticket->getCreatedAt(), 'Ymd') . $ticket->getCustomer()->getStripeCustomerId());

        $this->manager->persist($ticket->getCustomer());

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
}