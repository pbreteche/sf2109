<?php

namespace App\Controller;

use App\Demo\RepositoryDemo;
use App\Entity\Person;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * @Route("/post", methods="GET")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/written-by/{id}", defaults={"id": 1})
     */
    public function index(
        PostRepository $repository,
        RepositoryDemo $repositoryDemo,
        Person $person
    ): Response {
        $repositoryDemo->demo();

        dump($this->generateUrl('app_post_index', ['id' => 1], UrlGeneratorInterface::ABSOLUTE_URL));

        // Menu généré par une sous-requête
        // déclenché ici directement dans le contrôleur
        $menuResponse = $this->forward(NavController::class.'::menu');

        return $this->render('post/index.html.twig', [
            'person' => $person,
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

    /**
     * @Route("/new", methods="POST")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $newPost = new Post();
        $form = $this->createForm(PostType::class, $newPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($newPost);
            $manager->flush();
            $this->addFlash('success', 'La nouvelle publication a bien été insérée');

            return $this->redirectToRoute('app_post_show', ['id' => $newPost->getId()]);
        }

        return $this->render('post/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods="PUT")
     */
    public function edit(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(PostType::class, $post, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'La modification a bien été appliquée');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('post/edit.html.twig', [
            'edit_form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/delete/{id}",requirements={"id": "\d+"}, methods="DELETE")
     */
    public function delete(
        Post $post,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createFormBuilder(null, [
            'method' => 'DELETE',
        ])->add('confirm', TextType::class, [
            'help' => 'Recopier le titre de la publication ici',
            'constraints' => [
                new EqualTo(['value' => $post->getTitle()])
            ]
        ])->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($post);
            $manager->flush();

            $this->addFlash('success', 'La publication a été définitivement supprimée');

            return $this->redirectToRoute('app_post_index');
        }

        return $this->render('post/delete.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
}
