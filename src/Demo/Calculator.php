<?php

namespace App\Demo;

class Calculator implements CalculatorInterface
{
    private $inc = 0;

    public function add(int $a, int $b): int
    {
        // Ici, c'est stateless
        // le résultat est toujours le même,
        // car dépend uniquement des paramètres (fonction pure)
        return $a + $b;
    }

    public function inc()
    {
        // Ici, on a un état. Un appel d'un utilisateur de cette méthode
        // fera changer le résultat pour un autre utilisateur de la méthode
        return $this->inc++;
    }
}