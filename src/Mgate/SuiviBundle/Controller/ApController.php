<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mgate\SuiviBundle\Controller;

use Mgate\SuiviBundle\Entity\Ap;
use Mgate\SuiviBundle\Form\Type\ApType;
use Mgate\SuiviBundle\Form\Type\DocTypeSuiviType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApController extends Controller
{
    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MgateSuiviBundle:Etude')->findAll();

        return $this->render('MgateSuiviBundle:Etude:index.html.twig', array(
                    'etudes' => $entities,
                ));
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     */
    public function voirAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        //attention reflechir si faut passer id etude ou rester en id Ap
        // en fait y a 2 fonction voir
        // une pour voir le suivi
        // et une pour voir la redaction
        $etude = $em->getRepository('MgateSuiviBundle:Etude')->find($id);
        $entity = $etude->getAp();
        if (!$entity) {
            throw $this->createNotFoundException('L\'Avant-Projet demandé n\'existe pas !');
        }

        if ($this->get('Mgate.etude_manager')->confidentielRefus($etude, $this->getUser(), $this->get('security.authorization_checker'))) {
            throw new AccessDeniedException('Cette étude est confidentielle');
        }

        return $this->render('MgateSuiviBundle:Ap:voir.html.twig', array(
                    'ap' => $entity,
                ));
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     */
    public function redigerAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$etude = $em->getRepository('Mgate\SuiviBundle\Entity\Etude')->find($id)) {
            throw $this->createNotFoundException('L\'étude demandée n\'existe pas!');
        }

        if ($this->get('Mgate.etude_manager')->confidentielRefus($etude, $this->getUser(), $this->get('security.authorization_checker'))) {
            throw new AccessDeniedException('Cette étude est confidentielle');
        }

        if (!$ap = $etude->getAp()) {
            $ap = new Ap();
            $etude->setAp($ap);
        }

        $form = $this->createForm(ApType::class, $etude, array('prospect' => $etude->getProspect()));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $this->get('Mgate.doctype_manager')->checkSaveNewEmploye($etude->getAp());

                $em->flush();

                if ($request->get('phases')) {
                    return $this->redirect($this->generateUrl('MgateSuivi_phases_modifier', array('id' => $etude->getId())));
                } else {
                    return $this->redirect($this->generateUrl('MgateSuivi_etude_voir', array('nom' => $etude->getNom())));
                }
            }
        }

        return $this->render('MgateSuiviBundle:Ap:rediger.html.twig', array(
                    'form' => $form->createView(),
                    'etude' => $etude,
                ));
    }

    /**
     * @Security("has_role('ROLE_SUIVEUR')")
     */
    public function suiviAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$etude = $em->getRepository('Mgate\SuiviBundle\Entity\Etude')->find($id)) {
            throw $this->createNotFoundException('L\'étude demandée n\'existe pas!');
        }

        if ($this->get('Mgate.etude_manager')->confidentielRefus($etude, $this->getUser(), $this->get('security.authorization_checker'))) {
            throw new AccessDeniedException('Cette étude est confidentielle');
        }

        $ap = $etude->getAp();
        $form = $this->createForm(DocTypeSuiviType::class, $ap); //transmettre etude pour ajouter champ de etude

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('MgateSuivi_etude_voir', array('nom' => $etude->getNom())));
            }
        }

        return $this->render('MgateSuiviBundle:Ap:modifier.html.twig', array(
                    'form' => $form->createView(),
                    'etude' => $etude,
                ));
    }
}