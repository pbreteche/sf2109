<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function hello(string $name): Response
    {
        return new Response('<h1>Bonjour '.$name.'!</h1>');
    }
}
