<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Band;
use AppBundle\Form\BandFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_MANAGE_BAND')")
 */
class BandAdminController extends Controller
{
    /**
     * @Route("/band", name="admin_band_list")
     */
    public function indexAction()
    {        
        $bands = $this->getDoctrine()->getRepository('AppBundle:Band')->findAll();

        return $this->render('admin/band/list.html.twig', array(
            'bands' => $bands
        ));
    }
    
    /**
     * @Route("/band/new", name="admin_band_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(BandFormType::class);

        // only handles data on POST
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($band);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Genus created by you: %s!', $this->getUser()->getEmail())
            );

            return $this->redirectToRoute('admin_band_list');
        }

        return $this->render('admin/band/new.html.twig', [
            'bandForm' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/band/{id}/edit", name="admin_band_edit")
     */
    public function editAction(Request $request, Band $band)
    {
        $form = $this->createForm(BandFormType::class, $band);

        // only handles data on POST
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($band);
            $em->flush();

            $this->addFlash('success', 'Band updated!');

            return $this->redirectToRoute('admin_band_list');
        }

        return $this->render('admin/band/edit.html.twig', [
            'bandForm' => $form->createView()
        ]);
    }
}
