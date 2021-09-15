<?php

namespace App\Demo;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RepositoryDemo
{
    private $postRepository;
    private $urlGenerator;
    private $validator;

    public function __construct(
        PostRepository $postRepository,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator
    ) {
        $this->postRepository = $postRepository;
        $this->urlGenerator = $urlGenerator;
        $this->validator = $validator;
    }

    public function demo()
    {
        dump($this->postRepository->findByMonth(new \DateTimeImmutable()));
        dump($this->postRepository->findByMonth2(new \DateTimeImmutable()));
        dump($this->urlGenerator->generate('app_post_create'));
        dump($this->validator->validate((new Post())->setTitle('bonjour!')));
        dump($this->validator->validate((new Post())->setTitle('bonjour')->setBody('blablabla')));
        dump($this->validator->validate((new Post())->setTitle('bonjour')->setBody(' blablabla blablabla blablabla')));

        // tester un groupe de validation spécifique
        dump($this->validator->validate((new Post())->setTitle('bonjour!'), null, 'publish'));
    }
}