<?php

namespace App\Form;

use App\Entity\Ticket;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisit',DateType::class,[
                'label'=>'Date de votre visite:',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],

                'html5' => false,
            ])
            ->add('typeTicket',ChoiceType::class,[
                'help' => 'Votre visite peux durer que la demi-journée!',
                'label'=> 'Type de visite:',
                'choices'=>['Journée'=>'tarifJournee','Demi-Journée'=> 'tarifDemiJournée']


            ])
            ->add('numberPlace',IntegerType::class,[

                'attr' => [

                    'min'  => 1,
                    'max'  => 10,
                    'step' => 1
                ],
                'label'=>'Nombres de Places:',
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
