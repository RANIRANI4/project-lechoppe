<?php

namespace App\Form;

use App\Enum\EnumLabel;
use App\Entity\Product;
use App\Entity\SellSlot;
use App\Repository\SellSlotRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de mon produit',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez charger une image valide (jpg, png, webp)',
                    ])
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Nom',
                'required' => false,
            ])
            ->add('description', TextType::class, [
                'label' => 'Déscription',
                'required' => false,
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité',
                'required' => false,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix',
                'required' => false,
            ])
            ->add('certifications', EnumType::class, [
                'class' => EnumLabel::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => function (EnumLabel $label) {
                    return $label->value;
                }
            ])
            ->add('sellSlots', EntityType::class, [
                'class' => SellSlot::class,
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'custom-select',
                ],
                'choice_label' => function (SellSlot $sellSlot) {
                    return $sellSlot->getShop()->getName() . ' - ' . $sellSlot->getStartDate()->format('d/m H:i');
                },
                'query_builder' => fn(SellSlotRepository $er) => $er->findActiveByUser($currentUser)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'current_user' => null,
        ]);
    }
}
