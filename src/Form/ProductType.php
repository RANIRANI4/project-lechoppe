<?php

namespace App\Form;

use App\Config\EnumLabel;
use App\Entity\Product;
use App\Entity\SellSlot;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $shops = $options['current_user']->getShops()->toArray();
        $shopsId = [];

        foreach ($shops as $shop) {
            $shopsId[] = $shop->getId();
        }

        $builder
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
                },])
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
                'query_builder' => function (EntityRepository $er) use ($shopsId): QueryBuilder {
                    return $er->createQueryBuilder('ss')
                        ->where('ss.shop IN (:shopsId)')
                        ->setParameter('shopsId', $shopsId);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'current_shops' => null,
            'current_user' => null,
        ]);
    }
}
