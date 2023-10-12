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
    #[Route('/', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/cree', name: 'app_article_cree')]
    public function creeArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        // On ajoute la date généree automatiquement
        $article->setDate(new DateTimeImmutable());

        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        // On récupère les données du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
    
            return $this->redirectToRoute('articles_list');
        }

        return $this->render('article/creeArticle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/voir/{id}', name: 'article_show')]
    public function show(ArticleRepository $articleRepository, int $id): Response
    {
        $article = $articleRepository->find($id);
        // dd($article);

        if (!$article) {
            throw $this->createNotFoundException(
                "Aucun article à été trouver avec l'id ".$id
            );
        }

        return $this->render('article/getById.html.twig', [
            'article' => $article,
        ]);

        // return new Response('Regarde cet article : '.$article->getTitre());
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/voir', name: 'articles_list')]
    public function list(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('article/getAll.html.twig', [
            'articles' => $articles,
        ]);

    }

}

