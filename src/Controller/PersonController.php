<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/person", methods="GET")
 */
class PersonController extends AbstractController
{
    /**
     * @Route("/new", methods="POST")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $newPerson = new Person();
        $form = $this->createForm(PersonType::class, $newPerson);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($newPerson);
            $manager->flush();
            $this->addFlash('success', 'La nouvelle personne a bien été insérée');

            return $this->redirectToRoute('app_person_create');
        }

        return $this->render('person/create.html.twig', [
            'create_form' => $form->createView(),
        ]);
    }
}
