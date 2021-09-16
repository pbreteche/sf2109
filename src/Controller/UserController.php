<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user", methods="GET")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/new", methods="POST")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $newPerson = new User();
        $form = $this->createForm(UserType::class, $newPerson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($newPerson);
            $manager->flush();
            $this->addFlash('success', 'La nouvelle personne a bien été insérée');

            return $this->redirectToRoute('app_user_create');
        }

        return $this->render('user/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }
}
