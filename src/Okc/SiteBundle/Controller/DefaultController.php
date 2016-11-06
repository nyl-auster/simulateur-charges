<?php

namespace Okc\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
      $response = $this->render('OkcSiteBundle::homepage.html.twig');
      $response->setETag(md5($response->getContent()));
      $response->setPublic(); // permet de s'assurer que la réponse est publique, et qu'elle peut donc être cachée
      return $response;
    }
}
