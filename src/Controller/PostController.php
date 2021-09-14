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

    /**
     * @Route("/show/{id}", requirements={"id": "\d+"})
     */
    public function show(
        int $id,
        PostRepository $repository
    ): Response {
        $post = $repository->find($id);

        if (!$post) {
            throw $this->createNotFoundException();
        }

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

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }
}
