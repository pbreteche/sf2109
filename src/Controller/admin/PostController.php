<?php

namespace App\Controller\admin;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\EqualTo;

/**
 * @Route("/post", methods="GET")
 * @IsGranted("ROLE_ADMIN")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/new", methods="POST")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        LoggerInterface $logger
    ): Response {
        $newPost = new Post();
        $form = $this->createForm(PostType::class, $newPost, [
            // teste les contraintes "Default" de l'objet post, mais pas celles de l'objet Person
            'validation_groups' => 'Post',
        ]);

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$this->isGranted('ROLE_ADMIN')) {
            $logger->debug('accès refusé pour '.$this->getUser()->getFullName());
            throw $this->createAccessDeniedException();
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($newPost);
            $manager->flush();
            $this->addFlash('success', 'La nouvelle publication a bien été insérée');

            return $this->redirectToRoute('app_post_show', ['id' => $newPost->getId()]);
        }

        return $this->renderForm('post/create.html.twig', [
            'create_form' => $form,
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods="PUT")
     * @IsGranted("POST_EDIT", subject="post")
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