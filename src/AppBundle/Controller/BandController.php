<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BandController extends Controller
{
    /**
     * @Route("/band/{bandName}", name="band_show")
     */
    public function showAction( $bandName ) 
    {        
        $funFact = 'C\'est vraiment *n\'importe quoi!*';
        
        $cache = $this->get('doctrine_cache.providers.my_markdown_cache');
        $key = md5($funFact);
        
        if( $cache->contains($key) ) {
            $funFact = $cache->fetch($key);
        } else {
            sleep(1);
            $funFact = $this->get('markdown.parser')->transform($funFact);
            $cache->save( $key, $funFact );
        }       
        
        
        return $this->render('band/show.html.twig', [
            'name' => $bandName,
            'funFact' => $funFact    
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
