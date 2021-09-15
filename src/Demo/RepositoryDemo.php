<?php

namespace App\Demo;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RepositoryDemo
{
    private $postRepository;
    private $urlGenerator;

    public function __construct(
        PostRepository $postRepository,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->postRepository = $postRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function demo()
    {
        dump($this->postRepository->findByMonth(new \DateTimeImmutable()));
        dump($this->postRepository->findByMonth2(new \DateTimeImmutable()));
        dump($this->urlGenerator->generate('app_post_create'));
    }
}