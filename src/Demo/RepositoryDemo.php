<?php

namespace App\Demo;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RepositoryDemo
{
    private $postRepository;
    private $urlGenerator;
    private $validator;
    private $translator;

    public function __construct(
        PostRepository $postRepository,
        UrlGeneratorInterface $urlGenerator,
        ValidatorInterface $validator,
        TranslatorInterface $translator
    ) {
        $this->postRepository = $postRepository;
        $this->urlGenerator = $urlGenerator;
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function demo()
    {
        dump($this->postRepository->findByMonth(new \DateTimeImmutable()));
        dump($this->postRepository->findByMonth2(new \DateTimeImmutable()));
        dump($this->urlGenerator->generate('app_admin_post_create'));
        dump($this->validator->validate((new Post())->setTitle('bonjour!')));
        dump($this->validator->validate((new Post())->setTitle('bonjour')->setBody('blablabla')));
        dump($this->validator->validate((new Post())->setTitle('bonjour')->setBody(' blablabla blablabla blablabla')));

        // tester un groupe de validation spécifique
        dump($this->validator->validate((new Post())->setTitle('bonjour!'), null, 'publish'));

        dump($this->translator->trans('website.global.welcome'));
    }
}