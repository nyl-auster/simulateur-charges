<?php

namespace Okc\Site\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
      $response = $this->render('OkcSiteSiteBundle:Default:homepage.html.twig');
      $response->setETag(md5($response->getContent()));
      $response->setPublic(); // permet de s'assurer que la réponse est publique, et qu'elle peut donc être cachée
      return $response;
    }
}
