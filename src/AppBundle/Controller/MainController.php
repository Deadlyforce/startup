<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of MainController
 *
 * @author Norman
 */
class MainController extends Controller
{
    public function homepageAction() 
    {
        return $this->render('main/homepage.html.twig');
    }
}
