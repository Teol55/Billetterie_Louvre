<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\OrderFormType;
use App\Repository\TicketRepository;
use App\Repository\VisitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(Request $request, VisitorRepository $Repository)
    {
        $form=$this->createForm(OrderFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $data = $form->getData();
//           dd($data);
//            $ticket=new Ticket();
//            $ticket->setDateVisit($data['dateVisit']);
//         dd($data['dateVisit']);
//            $ticket=new Ticket();
//            $ticket->setDateVisit($data['dateVisit']);
//            $ticket->setTypeTicket($data['typeTicket']);
////           $dateVisit=$data['typeTicket'];
//            dd($ticket);
dd(strftime ("%A",strtotime(date_format($data['Ticket']['dateVisit'],"d-m-Y"))));
        dd($Repository->countByDateVisit($data['dateVisit']));

        }

        return $this->render('homepage.html.twig',[
            'orderForm' => $form->createView()

        ]);
    }
    /**
     * @Route("/test", name="app_test")
     */
    public function test(TicketRepository $repository)

    {
        $orders=$repository->findAll();
        dd($orders);
        return $this->render('test.html.twig', [


        ]);
    }
}
