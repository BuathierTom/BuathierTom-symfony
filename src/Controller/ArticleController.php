<?php

namespace App\Controller;

use App\Form\ArticleFormType;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Article;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles_list')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/getAll.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cree', name: 'app_article_cree')]
    public function creeArticle(Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository): Response
    {
        $article = new Article();

        // Pour le menu
        $articles = $articleRepository->findAll();

        // On ajoute la date généree automatiquement
        $article->setDate(new DateTimeImmutable());

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        // On récupère les données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $imgFile = $form->get('imageFileName')->getData();  
            // dd($imgFile);
            if ($imgFile) {
                // On change le nom du fichier en article + id.jpg
                $newFileName = 'article'.$article->getId().'.jpg';
                // On déplace le fichier dans le dossier public/images
                $imgFile->move(
                    $this->getParameter('imageFileName_directory'),
                    $newFileName
                );

            }
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/creeArticle.html.twig', [
            'form' => $form->createView(),
            'articles' => $articles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/voir/{id}', name: 'article_show')]
    public function showByID(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->find($id);
        // dd($article);

        // Pour le menu
        $articles = $articleRepository->findAll();

        if (!$article) {
            throw $this->createNotFoundException(
                "Aucun article à été trouver avec l'id ".$id
            );
        }

        return $this->render('article/getById.html.twig', [
            'article' => $article,
            'articles' => $articles,
        ]);

        // return new Response('Regarde cet article : '.$article->getTitre());
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/modifier/{id}', name: 'article_update')]
    public function update(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $article = $articleRepository->find($id);

        // Pour le menu
        $articles = $articleRepository->findAll();

        if (!$article) {
            throw $this->createNotFoundException(
                "Aucun article à été trouver avec l'id ".$id
            );
        }

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        // On récupère les données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
    
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/update.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'articles' => $articles,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/delete/{id}', name: 'article_delete')]
    public function delete(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                "Aucun article à été trouver avec l'id ".$id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('articles_list');
    }
}

