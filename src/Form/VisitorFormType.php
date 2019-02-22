<?php

namespace App\Form;

use App\Entity\Visitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null,
                ['label'=> 'Nom:',
                ])
            ->add('firstName',null,
                ['label'=> 'Prénom:',])
            ->add('country',CountryType::class,[
                'label'=> 'Pays:',
                'preferred_choices' => ['FR']
            ])
            ->add('birthday',DateType::class,[
                'label'=>'Date de naissance:',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'years' =>Range(-100,0)])
            ->add('reduction',null,[
                'label'=>'Tarif réduit:',
                'help'=>'Vous devrez présenter votre carte lors de votre arrivée au musée',
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visitor::class,
        ]);
    }
}
