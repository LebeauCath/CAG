<?php

namespace App\Form;

use App\Entity\Negociant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NegociantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // par défaut, l'attribut 'required' est positionné à vrai
        // 'attr' permet d'utiliser les attributs html
        $builder
            ->add('nomnegociant', TextType::class, array(
                'label' => 'Nom',
                'attr' => array('size' => 50)
            ))
            ->add('adrnegociant', TextareaType::class, array(
                'label' => 'Adresse',
                'required' => false,
                'attr' => array('rows' => 5,
                    'cols' => 80),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Negociant::class,
        ]);
    }
}
