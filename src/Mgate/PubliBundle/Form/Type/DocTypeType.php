<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mgate\PubliBundle\Form\Type;

use Mgate\PubliBundle\Controller\TraitementController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', ChoiceType::class, array(
                        'required' => true,
                        'label' => 'Document Type',
                        'choices' => array(
                            TraitementController::DOCTYPE_SUIVI_ETUDE => 'Fiche de suivi d\'étude',
                            TraitementController::DOCTYPE_DEVIS => 'Devis',
                            TraitementController::DOCTYPE_AVANT_PROJET => 'Avant-Projet',
                            TraitementController::DOCTYPE_CONVENTION_CLIENT => 'Convention Client',
                            TraitementController::DOCTYPE_FACTURE_ACOMTE => 'Facture d\'acompte',
                            TraitementController::DOCTYPE_FACTURE_INTERMEDIAIRE => 'Facture intermédiaire',
                            TraitementController::DOCTYPE_FACTURE_SOLDE => 'Facture de solde',
                            TraitementController::DOCTYPE_PROCES_VERBAL_INTERMEDIAIRE => 'Procès verbal de recette intermédiaire',
                            TraitementController::DOCTYPE_PROCES_VERBAL_FINAL => 'Procès verbal de recette final',
                            TraitementController::DOCTYPE_RECAPITULATIF_MISSION => 'Récapitulatif de mission',
                            TraitementController::DOCTYPE_DESCRIPTIF_MISSION => 'Descriptif de mission',
                            TraitementController::DOCTYPE_CONVENTION_ETUDIANT => 'Convention Etudiant',
                            TraitementController::DOCTYPE_FICHE_ADHESION => 'Fiche d\'adhésion',
                            TraitementController::DOCTYPE_ACCORD_CONFIDENTIALITE => 'Accord de confidentialité',
                            TraitementController::DOCTYPE_DECLARATION_ETUDIANT_ETR => 'Déclaration étudiant étranger',
                            TraitementController::DOCTYPE_NOTE_DE_FRAIS => 'Note de Frais',
                            TraitementController::ROOTNAME_BULLETIN_DE_VERSEMENT => 'Bulletin de Versement',
                            ), ))
                ->add('etudiant', 'genemu_jqueryselect2_entity', array(
                'class' => 'Mgate\\PersonneBundle\\Entity\\Membre',
                'property' => 'identifiant',
                'label' => 'Intervenant pour vérifier le template',
                'required' => false,
                ))
             ->add('template', FileType::class, array('required' => true))
             ->add('etude', 'genemu_jqueryselect2_entity', array(
                       'label' => 'Etude pour vérifier le template',
                        'class' => 'Mgate\\SuiviBundle\\Entity\\Etude',
                        'property' => 'reference',
                        'required' => false, ))
             ->add('verification', CheckboxType::class, array('label' => 'Activer la vérification', 'required' => false));
    }

    public function getBlockPrefix()
    {
        return 'Mgate_suivibundle_doctypetype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}
