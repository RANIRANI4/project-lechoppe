<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProductToSellSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder->add('product', EntityType::class, [
            'class' => Product::class,
            'label' => 'Ajouter des produits',
            'mapped' => false,
            'choice_label' => function (Product $p) {
                return $p->getTitle();
            },
            'query_builder' => function (EntityRepository $er) use ($currentUser) {
                return $er->createQueryBuilder('p')
                    ->where('p.producer = :user')
                    ->setParameter('user', $currentUser);
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'current_user' => null,
        ]);
    }
}
