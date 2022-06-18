<?php

namespace App\Controller;

use App\Entity\Albums;
use App\Form\AlbumsType;
use App\Repository\AlbumsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/albums')]
class AlbumsController extends AbstractController
{
    #[Route('/', name: 'app_albums_index', methods: ['GET'])]
    public function index(AlbumsRepository $albumsRepository): Response
    {
        return $this->render('albums/index.html.twig', [
            'albums' => $albumsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_albums_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $album = new Albums();
        $form = $this->createForm(AlbumsType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('albums_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $album->setNom($fichier);
            }   
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('albums/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_albums_show', methods: ['GET'])]
    public function show(Albums $album): Response
    {
        return $this->render('albums/show.html.twig', [
            'album' => $album,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_albums_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Albums $album, AlbumsRepository $albumsRepository): Response
    {
        $form = $this->createForm(AlbumsType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $albumsRepository->add($album, true);

            return $this->redirectToRoute('app_albums_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('albums/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_albums_delete', methods: ['POST'])]
    public function delete(Request $request, Albums $album, AlbumsRepository $albumsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            unlink($this->getParameter('albums_directory').'/'.$album->getNom());
            $albumsRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_albums_index', [], Response::HTTP_SEE_OTHER);
    }
}
