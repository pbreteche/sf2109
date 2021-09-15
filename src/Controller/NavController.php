<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavController extends AbstractController
{
    public function menu(): Response
    {
        return new Response('<nav>Le menu de navigation</nav>');
    }
}