<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Image;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Repository\ArticleRepository;
use App\Repository\AlbumsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//mail
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index', methods: ['GET', 'POST'])]
    public function index(Request $request , MailerInterface $mailer, ContactRepository $contactRepository, ArticleRepository $articleRepository , AlbumsRepository $albumsRepository ): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact);
            $data = (new TemplatedEmail())
                ->from((new Address($contact->getEmail())))
                ->to(new Address('alex.wasef@gmail.com'))
                //->bcc('bcc@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->replyTo($contact->getEmail())
                ->subject('Contact via ton portfolio')
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'nom' => $contact->getNom(),
                    'prenom' => $contact->getPrenom(),
                    'mail' => $contact->getEmail(),
                    'entreprise' => $contact->getEntreprise(),
                    'message' => $contact->getMessage()
                    ])
            ;
            $mailer->send($data);
        }

        return $this->renderForm('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'albums'=> $albumsRepository->findAll(),
            'contact' => $contact,
            'formContact' => $form,
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // On récupère les images transmises
            $images = $form->get('images')->getData();
            
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setNom($fichier);
                $article->addImage($img);
            }   

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //récuperer les images
            $images = $form['images']->getData();
           
            //dump($images);
            //on parcours chaque images 
            if($images){
                foreach($images as $image){
                    
                        foreach ($article->getImages() as $image_delete) {
                            if($image_delete->getNom() ){
                                dump($image_delete);
                                $article->removeImage($image_delete);
                                unlink($this->getParameter('images_directory').'/'.$image_delete->getNom());
                            }
                        }
                    
                    $fichier = md5(uniqid()).'.'.$image->guessExtension();
                    //on copie les fichier dans le dossier uploads
                    $image->move($this->getParameter('images_directory'),$fichier);
                    $newImage = new Image();
                    $newImage->setNom($fichier);
                    $article->addImage($newImage);  
                    
                }
            }
            $entityManager->persist($article);// mettre cascase dans la variable images 
            $entityManager->flush();

            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            foreach ($article->getImages() as $image) {
                $article->removeImage($image);
                unlink($this->getParameter('images_directory').'/'.$image->getNom());
                $entityManager->remove($image);
            }
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
}
