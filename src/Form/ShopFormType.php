<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ShopFormType extends AbstractType
{
    private const DESCRIPTION_MAX_LENGTH = 500;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $imageConstraints = [];

        if ($options['require_image']) {
            $imageConstraints[] = new NotNull([
                'message' => 'Veuillez ajouter une photo de votre échoppe.',
            ]);
        }

        $imageConstraints[] = new File([
            'maxSize' => '2048k',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
            'mimeTypesMessage' => 'Veuillez charger une image valide (jpg, png, webp)',
        ]);

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Photo de mon échoppe',
                'mapped' => false,
                'required' => $options['require_image'],
                'constraints' => $imageConstraints,
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse de l'échoppe",
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Je décris mon échoppe',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => self::DESCRIPTION_MAX_LENGTH,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => self::DESCRIPTION_MAX_LENGTH,
                    'rows' => 5,
                    'data-counter' => 'true',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
            'require_image' => false,
        ]);

        $resolver->setAllowedTypes('require_image', 'bool');
    }
}
