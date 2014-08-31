<?php
namespace Okc\TnsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
// these import the "@Route"
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Okc\TnsBundle\Form\ChargesType;
use Okc\TnsBundle\Model\ChargesEurlIsPl;

/**
 * @TODO : rendre le controller testable (injecter charges, le déclarer en tant que service ?)
 * Class SimulateurController
 * @package Okc\TnsBundle\Controller
 */
class SimulateurController extends Controller
{

    /**
     * @route ("/eurl/is/pl", name="_eurl_is_pl")
     */
    public function simulateurAction(Request $request)
    {
        $datas = include '../src/Okc/TnsBundle/Resources/config/baremesEurlIsPl2014.php';
        $calculs = new ChargesEurlIsPl($datas);
        $form = $this->createForm(new ChargesType(), $calculs, ['method' => 'GET']);
        $form->handleRequest($request);
        $form->isValid();
        return $this->render('OkcTnsBundle::chargesDetails.html.twig',
          [
            'calculs' => $calculs,
            'datas' => $datas,
            'form' => $form->createView()
          ]
        );
    }

}

