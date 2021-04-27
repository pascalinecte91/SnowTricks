<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Entity\Picture;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // recuperation des images transmises
            $pictures = $form->get('picture')->getData();

            // faire une boucle pour plusieurs images
            foreach ($pictures as $picture) {
            //  generer un nouveau nom de fichier images
                $fichier = md5(uniqid()) . '.' . $picture->guessExtension();
            
            // recopier le fichier dans le upload
            $picture->move(
                $this->getParameter('pictures_directory'),
                $fichier
            );
        
            // stocker l image dans la db avec son nom
            $img = new Picture();
            $img->setName($fichier);
            $trick->addPicture($img);
        }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    
        }
    

    /**
     * @Route("/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // recuperation des images transmises
            $pictures = $form->get('picture')->getData();

            // faire une boucle pour plusieurs images
            foreach ($pictures as $picture) {
            //  generer un nouveau nom de fichier images
                $fichier = md5(uniqid()) . '.' . $picture->guessExtension();
            
            // recopier le fichier dans le upload
            $picture->move(
                $this->getParameter('pictures_directory'),
                $fichier
            );
        
            // stocker l image dans la db avec son nom
            $img = new Picture();
            $img->setName($fichier);
            $trick->addPicture($img);
        }   
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }
/**
 *  @Route("/delete/picture/{id}", name="trick_delete_picture", methods={"DELETE"})
 */
    public function deletePicture(Trick $picture,  Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //verification du token  valid ou pas
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            $name = $picture->getName();
            unlink($this->getParameter('pictures_directory') . '/' . $name);

        // suppression de la db
            $em = $this->getDoctrine()->getManager();
            $em->remove($picture);
            $em->flush();

            //reponse en json_decode
            return new JsonResponse(['success'=> 1]);
        }else{
            return new JsonResponse(['error'=> 'Token Invalide'], 400);
        }
    }
}
