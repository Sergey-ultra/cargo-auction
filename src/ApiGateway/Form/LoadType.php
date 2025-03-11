<?php

declare(strict_types=1);

namespace App\ApiGateway\Form;


use App\Modules\Load\Domain\Entity\Load;
use App\Modules\User\Domain\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('downloadingDateStatus', TextType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Статус загрузки не может быть пустым',
                        ]),
                    ],
                ])
            ->add('toAddress', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('fromAddress', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('weight', TextType::class,   [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('volume', TextType::class,   [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('cargoType', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('bodyType', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('downloadingType',TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('unloadingType',TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('priceType', TextType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('priceWithoutTax', NumberType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('priceWithTax', NumberType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
            ->add('priceCash', NumberType::class,  [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Статус загрузки не может быть пустым',
                    ]),
                ],
            ])
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
            //'csrf_protection' => true,
            'allow_extra_fields' => true,
        ]);
    }
}
