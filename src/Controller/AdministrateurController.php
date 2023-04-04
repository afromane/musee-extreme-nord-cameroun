<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Musee;
use App\Form\ImageType;
use App\Form\MuseeType;
use App\Service\FileUploader;
use App\Repository\ImageRepository;
use App\Repository\MuseeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrateurController extends AbstractController
{
    /**
     * @Route("/administrateur", name="app_administrateur")
     */
    public function index(MuseeRepository $museeRepository,ReservationRepository $reservationRepository): Response
    {
        return $this->render('administrateur/index.html.twig',array(
            'musees'=>$museeRepository->findAll(),
            'reservation'=>$reservationRepository->findAll(),
            )
    );
    }
    /**
     * @Route("/museum", name="museum.index")
     */
    public function museum(MuseeRepository $museeRepository): Response
    {
        return $this->render('administrateur/musee_index.html.twig',array(
            'musees'=>$museeRepository->findAll()
        ));
    }
    /**
     * @Route("/museum/record", name="museum.record",methods={"POST","GET"})
     */
    public function newMuseum(Request $request,EntityManagerInterface $em) : Response
    {
        $musee = new Musee();
        $form = $this->createForm(MuseeType::class, $musee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($musee);
            $em->flush();
            $this->addFlash('success', 'Creation succeeded.');
            return $this->redirectToRoute('museum.index');
        }
        return $this->render('administrateur/add_musee.html.twig', [
            'form'     => $form->createView()
        ]);

    }
    /**
     * @Route("/museum/edit/{id}", name="museum.edit",methods={"POST","GET"})
     */
    public function editMuseum(Musee $musee,Request $request,EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(MuseeType::class, $musee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'Update succeeded.');
            return $this->redirectToRoute('museum.index');
        }
        return $this->render('administrateur/edit_musee.html.twig', [
            'form'     => $form->createView()
        ]);

    }
    /**
     * @Route("/museum/gallery/{id}", name="museum.gallery")
     */
    public function gallery(Musee $musee,ImageRepository $imageRepository): Response
    {
        return $this->render('administrateur/musee_gallery.html.twig',array(
            //'images'=>$imageRepository->findAll(),
            'images'=>$imageRepository->findBy(array('musee'=>$musee->getId())),
            'musee'=>$musee
        ));
    }

     /**
     * @Route("/gallery/record/{id}", name="gallery.record",methods={"POST","GET"})
     */
    public function newgallery(Musee $musee,Request $request,EntityManagerInterface $em,FileUploader $fileUploader) : Response
    {

        if ($_POST)
        {
            
            $data = $request->request->all();
            /** @var UploadedFile $uploadFile */
            $imageFile = $request->files->get('file');
            $filename = $fileUploader->upload($imageFile);
            $image = new Image();
            $image->setPath($filename)
                 ->setDescription($data['description'])
                 ->setTitre($data['titre'])
                 ->setMusee($musee);
            $em->persist($image);
            $em->flush(); 
            $this->addFlash('success', 'Creation succeeded.');
            return $this->redirectToRoute('museum.gallery',['id' => $musee->getId()]);
        }
        return $this->render('administrateur/add_gallery.html.twig');

    }
    
}
