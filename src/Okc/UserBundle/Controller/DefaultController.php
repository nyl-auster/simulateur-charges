<?php

namespace Okc\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OkcUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
