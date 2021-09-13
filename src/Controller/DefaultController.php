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

        // charger un article depuis la base de données

        return new Response('Article n°'.$id.' chargé.');
    }
}
