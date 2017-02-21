<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Band;

/**
 * 
 */
class BandController extends Controller
{
    /**
     * @Route("/band/new")
     */
    public function newAction() 
    {
        $band = new Band();
        $band->setName('Obituary' . rand(1, 100));
        $band->setSubGenre('Death Metal');
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($band);
        $em->flush();
        
        return new Response('<html><body>Band created!</body></html>');
    }
    
    /**
     * @Route("/band", name="band_list")
     */
    public function listAction() 
    {
        $em = $this->getDoctrine()->getManager();
        $bands = $em->getRepository('AppBundle:Band')->findAllPublished();
        
        return $this->render('band/list.html.twig', [
            'bands' => $bands
        ]);
    }
    
    /**
     * @Route("/band/{bandName}", name="band_show")
     */
    public function showAction( $bandName ) 
    {        
        $em = $this->getDoctrine()->getManager();
        $band = $em->getRepository('AppBundle:Band')->findOneBy(['name' => $bandName]);
        
        if( !$band ) {
            throw $this->createNotFoundException('Pauvre idiot!...Nothing found');
        }
        
//        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
//        $key = md5($funFact);
//        
//        if( $cache->contains($key) ) {
//            $funFact = $cache->fetch($key);
//        } else {
//            sleep(1);
//            $funFact = $this->get('markdown.parser')->transform($funFact);
//            $cache->save( $key, $funFact );
//        }       
                
        return $this->render('band/show.html.twig', [
            'band' => $band    
        ]);
    }
    
    /**
     * @Route("/band/{bandName}/notes", name="band_show_notes")
     * @Method({"GET"})
     */
    public function getNotesAction()
    {
        $notes = [
            ['id' => 1, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Octopus asked me a riddle, outsmarted me', 'date' => 'Dec. 10, 2015'],
            ['id' => 2, 'username' => 'AquaWeaver', 'avatarUri' => '/images/ryan.jpeg', 'note' => 'I counted 8 legs... as they wrapped around me', 'date' => 'Dec. 1, 2015'],
            ['id' => 3, 'username' => 'AquaPelham', 'avatarUri' => '/images/leanna.jpeg', 'note' => 'Inked!', 'date' => 'Aug. 20, 2015'],
        ];
        
        $data = [
            'notes' => $notes
        ];
        
        return new JsonResponse($data);
    }
    
}
