<?php

namespace App\Form;

use App\Entity\SellSlot;
use App\Repository\SellSlotRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSellSlotToProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $options['current_user'];

        $builder->add('sellSlot', EntityType::class, [
            'class' => SellSlot::class,
            'label' => 'Ajouter à un créneau',
            'mapped' => false,
            'choice_label' => function (SellSlot $s) {
                return $s->getShop()->getName() . ' — ' . $s->getStartDate()->format('d/m H:i');
            },
            'query_builder' => fn(SellSlotRepository $er) => $er->findActiveByUser($currentUser),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'current_user' => null,
        ]);
    }
}
