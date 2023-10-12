<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function creeArticle(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article ->setTitre("Mon 1er article")
                 ->setTexte('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut blandit nibh id mi pellentesque, elementum luctus nulla consectetur.')
                 ->setEtat(true)
                 ->setDate(new DateTimeImmutable());
        // dd($article);

        // Envoie un signal a Doctrine pour ajouter l'article (Eventuellement)
        $entityManager->persist($article);

        // Execute la requete
        $entityManager->flush();

        return new Response("Ajout de l'article avec l'id ".$article->getId());
    }

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

        return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);

        // return new Response('Regarde cet article : '.$article->getTitre());
    }

    
}
