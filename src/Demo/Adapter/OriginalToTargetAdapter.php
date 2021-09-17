<?php

namespace App\Demo\Adapter;

class OriginalToTargetAdapter implements TargetInterface
{
    private $original;

    public function __construct(OriginalInterface $original)
    {
        $this->original = $original;
    }

    public function anotherMethod()
    {
        return $this->original->originalMethod();
    }
}
