<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post", methods="GET")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $post = new Post();

        $post
            ->setTitle('Mon premier article')
            ->setBody('test démo')
            ->setCreatedAt(new \DateTimeImmutable('2021-09-13 15:34:56'))
            ->setIsPublished(true)
        ;

        return $this->render('post/index.html.twig', [
            'post' => $post,
        ]);
    }
}