<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mgate\SuiviBundle\Form\Type;

use Mgate\SuiviBundle\Entity\Etude;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviEtudeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('stateID', ChoiceType::class, array('choices' => Etude::getStateIDChoice(), 'label' => 'Etat de l\'Étude', 'required' => true))
                ->add('auditDate', 'genemu_jquerydate', array('label' => 'Audité le', 'format' => 'd/MM/y', 'required' => false, 'widget' => 'single_text'))
                ->add('auditType', new AuditType(), array('label' => 'Type d\'audit', 'required' => false))
                ->add('stateDescription', TextareaType::class, array('label' => 'Problèmes', 'required' => false, 'attr' => array('cols' => '100%', 'rows' => 5)))
                ->add('ap', new DocTypeSuiviType(), array('label' => 'Avant-Projet', 'data_class' => 'Mgate\SuiviBundle\Entity\Ap'))
                ->add('cc', new DocTypeSuiviType(), array('label' => 'Convention Client', 'data_class' => 'Mgate\SuiviBundle\Entity\Cc'));

        $builder->add('missions', CollectionType::class, array(
            'type' => new DocTypeSuiviType(),
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false, //indispensable cf doc
        ));

        $builder->add('pvis', CollectionType::class, array(
            'type' => new DocTypeSuiviType(),
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false, //indispensable cf doc
        ));
        $builder->add('avs', CollectionType::class, array(
                'type' => new DocTypeSuiviType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false, //indispensable cf doc
            )
        );
        $builder->add('pvr', new DocTypeSuiviType(), array('label' => 'PVR', 'data_class' => 'Mgate\SuiviBundle\Entity\ProcesVerbal'));
    }

    public function getBlockPrefix()
    {
        return 'Mgate_suivibundle_suivietudetype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mgate\SuiviBundle\Entity\Etude',
        ));
    }
}
