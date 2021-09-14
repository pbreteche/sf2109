<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(
        PostRepository $repository
    ): Response {
        return $this->render('post/index.html.twig', [
            'posts' => $repository->findAll(),
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

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $newPost = new Post();
        $form = $this->createFormBuilder($newPost)
            ->add('title')
            ->add('body')
            ->add('isPublished')
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPost->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($newPost);
            $manager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $newPost->getId()]);
        }

        return $this->render('post/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }
}
