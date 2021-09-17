<?php

namespace App\Controller;

use App\Demo\RepositoryDemo;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Autre manière de sélectionner la locale par rapport au chemin.
 * En conflit avec nos souscripteurs.
 *
 * @Route({
 *     "en": "/post",
 *     "fr": "/publi"
 * }, methods="GET")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/written-by/{id}", defaults={"id": 1})
     */
    public function index(
        PostRepository $repository,
        RepositoryDemo $repositoryDemo,
        User $person
    ): Response {
        $repositoryDemo->demo();

        dump($this->generateUrl('app_post_index', ['id' => 1], UrlGeneratorInterface::ABSOLUTE_URL));

        // Menu généré par une sous-requête
        // déclenché ici directement dans le contrôleur
        $menuResponse = $this->forward(NavController::class.'::menu');

        return $this->render('post/index.html.twig', [
            'user' => $person,
            'posts' => $repository->findBy(['writtenBy' => $person], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @Route("/show/{id}", requirements={"id": "\d+"})
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

}
