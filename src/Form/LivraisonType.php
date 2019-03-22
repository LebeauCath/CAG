<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Repository\SiloRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // passage du paramètre "lesSilos" dans le constructeur de formulaire
        // Via le tableau $options
        // penser à le déclarer dans la fonction configureOptions()

        $lesSilos = $options['lesSilos'];

        $builder
            /* ->add('contrat', EntityType::class, array(
                 'class'     => 'App\Entity\Contrat',
                 'label' => 'Contrat',
                 'choice_label'  => 'nocontrat',
                 'attr'  => array( 'size' => 10,
                             'readonly' => 'true')
             ))*/
            ->add('dateliv', DateType::class, array(
                'label' => 'Date',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('qteliv', TextType::class, array(
                'label' => 'Quantité',
                'attr' => array('size' => 10)
            ))
            ->add('silo', EntityType::class, array(
                'class' => 'App\Entity\Silo',
                'choice_label' => 'codesilo',
                'choices' => $lesSilos
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
        $resolver->setRequired(['lesSilos']);
    }
}
