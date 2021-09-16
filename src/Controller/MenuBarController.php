<?php

namespace App\Controller;

use App\Form\LocaleSelectorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBarController extends AbstractController
{
    public function menuBar(
        UrlGeneratorInterface $generator,
        RequestStack $stack
    ): Response {
        $form = $this->createForm(LocaleSelectorType::class, null, [
            'action' => $generator->generate('app_menubar_setlocale'),
            'target' => $stack->getMainRequest()->getUri()
        ]);

        return $this->render('menu_bar/menu_bar.html.twig', [
            'locale_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/locale", methods="POST")
     */
    public function setLocale(
        Request $request
    ): Response {
        $form = $this->createForm(LocaleSelectorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('locale', $form->get('locale')->getData());
        }

        return $this->redirect($form->get('targetPath')->getData());
    }
}