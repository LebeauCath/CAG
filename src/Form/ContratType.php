<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datecontrat', DateType::class, array(
                'label' => 'Date',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('negociant', EntityType::class, array(
                'class' => 'App\Entity\Negociant',
                'choice_label' => 'nomNegociant'
            ))
            ->add('cereale', EntityType::class, array(
                'class' => 'App\Entity\Cereale',
                'choice_label' => 'variete'
            ))
            ->add('qtecde', TextType::class, array(
                'label' => 'Quantité',
                'attr' => array('size' => 10)
            ))
            ->add('prixcontrat', TextType::class, array(
                'label' => 'Prix',
                'attr' => array('size' => 10)
            ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
            // enlever la validation  pour les tests en mode production
            'validation_groups' => false,
        ]);
    }
}
