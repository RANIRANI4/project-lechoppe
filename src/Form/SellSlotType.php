<?php

namespace App\Form;

use App\Entity\SellSlot;
use App\Entity\Shop;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SellSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['current_user']){
            throw new \Exception("vous devez être connecté pour accéder à cette page.");
        }

        $builder
            ->add('startDate', DateTimeType ::class, [
                'label' => 'Date de début',
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
            ])
            ->add('shop', EntityType::class, [
                'label' => 'Echoppe',
                'class' => Shop::class,
                'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->where('s.producer = :producer')
                        ->setParameter('producer', $options['current_user'])
                        ->orderBy('s.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SellSlot::class,
            'current_user' => null,
        ]);
    }
}
