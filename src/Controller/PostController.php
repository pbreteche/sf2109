<?php

namespace App\Controller;

use App\Demo\RepositoryDemo;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
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
     * @Cache(
     *     expires="+1 hour", maxage="3600", public=true,
     *     lastModified="post.getCreatedAt()"
     * )
     */
    public function show(Post $post, Request $request): Response
    {
        $response = new Response();

        $response->setLastModified($post->getCreatedAt());
        $response->setEtag(sha1($post->getBody()));

        // isNotModified fonctionne aussi bien LastModified que pour Etag
        if ($response->isNotModified($request)) {
            return $response; // envoi de la réponse 304 Not modified
        }

        $response->setContent($this->renderView('post/show.html.twig', [
            'post' => $post,
        ]));

        // Symfony désactive la gestion du cache dès qu'une session est démarrée.
        // Désactive la gestion auto des en-têtes de cache par Symfony :
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');

        $response->headers->set('Expires', (new \DateTimeImmutable('+2 hour'))->format('c'));
        $response->setExpires(new \DateTimeImmutable('+3 hour'));
        $response->setPublic();
        //$response->headers->addCacheControlDirective('no-store');

        return $response;
    }

}
