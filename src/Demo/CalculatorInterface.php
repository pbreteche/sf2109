<?php

namespace App\Demo;

interface CalculatorInterface
{
    public function add(int $a, int $b): int;
}