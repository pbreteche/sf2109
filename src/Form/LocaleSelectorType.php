<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaleSelectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locale', LocaleType::class, [
                'choice_loader' => null,
                'choices' => ['Français' => 'fr', 'Anglais' => 'en'],
                'label' => 'website.global.locale',
            ])
            ->add('targetPath', HiddenType::class, [
                'data' => $options['target'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'target' => '',
        ]);
    }
}
