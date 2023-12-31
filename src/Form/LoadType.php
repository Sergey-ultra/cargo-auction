<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Load;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('downloadingDateStatus')
            ->add('toAddress')
            ->add('fromAddress')
            ->add('weight')
            ->add('volume')
            ->add('cargoType')
            ->add('bodyType')
            ->add('downloadingType')
            ->add('unloadingType')
            ->add('priceType')
            ->add('priceWithoutTax')
            ->add('priceWithTax')
            ->add('priceCash')
            ->add('user', EntityType::class, [
                'class' => User::class,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Load::class,
            'csrf_protection' => true,
            'allow_extra_fields' => true,
        ]);
    }
}
