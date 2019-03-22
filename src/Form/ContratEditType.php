<?php

namespace App\Form;


use Symfony\Component\Form\FormBuilderInterface;


// pas réussi à intégrer les champs nocontrat en "readonly"
// --> ne ne retrouve pas le "access type" de la propriété nocontrat dans l'entité
// pas réussi à intégrer la liste déroulante en "disabled"
// --> ne retrouve pas au retour du formulaire le negociant
// ai ajouté dans la vue les deux champs en html
// ai procédé à un héritage de templates
class ContratEditType extends ContratType // héritage de ContratType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('negociant');
    }

}
