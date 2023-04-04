<?php

namespace App\Controller;

use App\Entity\Musee;
use App\Repository\ImageRepository;
use App\Repository\MuseeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MuseeRepository $museeRepository,ImageRepository $imageRepository): Response
    {
        
       
        return $this->render('home/index.html.twig',array(
            'musees'=>$museeRepository->findAll(),
            'limitMusee' => $museeRepository->findBy(array(), null, 5)
            )
        );
    }
    /**
     * @Route("/musee", name="app_museum")
     */
    public function museum(MuseeRepository $museeRepository): Response
    {
        return $this->render('home/museum.html.twig',array(
            'musees'=>$museeRepository->findAll()
        ));
    }
    /**
     * @Route("/musee/discover/{id}", name="app_discover")
     */
    public function discover(Musee $musee): Response
    {
        return $this->render('home/discover.html.twig',array(
            'musees'=>$musee
        ));
    }
    /**
     * @Route("/musee/detail/{id}", name="app_museum_detail",methods={"POST","GET"})
     */
    public function museum_detail(Musee $musee): Response
    {
        return $this->render('home/museum_detail.html.twig',array(
            'musee'=>$musee
        ));
    }
    /**
     * @Route("/reservation", name="app_reservation")
     */
    public function reservation(): Response
    {
        dd('error');
        return $this->render('home/museum.html.twig');
    }
    /**
     * @Route("/about", name="app_about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}
