<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function hello(string $name): Response
    {
        return new Response('<h1>Bonjour '.$name.'!</h1>');
    }

    public function article(int $id, Request $request): Response
    {
        // URL
        // scheme:domain/path?query-string#hashbang

        // exploitation de la QueryString ($_GET)via un ParameterBag
        // possibilité d'avoir une valeur par défaut
        $lang = $request->query->get('lang', 'fr');
        // moins d'intérêt, accès en écriture
        $request->query->set('lang', 'de');
        // Données de formulaire envoyé en POST ($_POST)
        $username = $request->request->get('username');
        // variable de session idem, mais accès via une méthode au lieu d'un champs
        // ($_SESSION)
        $lang = $request->getSession()->get('lang');

        // autre infos depuis $_SERVER
        $request->getBaseUrl();
        $request->getBasePath();
        $request->getSchemeAndHttpHost();
        $request->getMethod();

        // => Première règle: ne plus utiliser les superglobales !

        // Manipulation de la réponse HTTP
        // via l'écriture sur la sortie standard:
        // commutateur fin de PHP (point interro + chevron)
        // echo, print, print_r, var_dump, etc.
        // ou manipulation des en-têtes de réponse
        // header(), http_response_code()

        // charger un article depuis la base de données

        $response = new Response();
        $response
            ->setContent('le corps de réponse')
            ->headers->set('Content-type', 'text/html')
        ;
        $response
            ->setExpires(new \DateTimeImmutable('tomorrow'))
        ;

        // Raccourcis de fabrication de réponses:
        $redirectResponse = $this->redirect('url de redirection', Response::HTTP_SEE_OTHER);
        $jsonResponse = $this->json(['key' => 'data']);
        // Erreur 404 et 403
        // throw $this->createNotFoundException();
        // throw $this->createAccessDeniedException();
        // Erreur 500 par défaut sur toutes les autres exceptions

        return new Response('Article n°'.$id.' chargé.');
    }
}
