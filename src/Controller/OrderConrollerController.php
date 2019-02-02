<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderConrollerController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index()
    {
        return $this->render('homepage.html.twig', [

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
