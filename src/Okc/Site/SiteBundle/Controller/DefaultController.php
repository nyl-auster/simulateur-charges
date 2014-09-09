<?php

namespace Okc\Site\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OkcSiteSiteBundle:Default:homepage.html.twig');
    }
}
