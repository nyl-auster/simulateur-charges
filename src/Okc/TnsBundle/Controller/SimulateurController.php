<?php
namespace Okc\TnsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Okc\TnsBundle\Form\ChargesType;
use Okc\TnsBundle\Model\calculsChargesEurlIsPl;
use Okc\tnsBundle\Resources\forms\eurlIsPlForm;

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
        $calculs = new calculsChargesEurlIsPl($datas, $request->query->get('charges'));

        $validator = $this->get('validator');
        $errors = $validator->validate($calculs);

        $form = $this->createForm(new ChargesType(), $calculs, ['method' => 'GET']);
        return $this->render('OkcTnsBundle::charges.html.twig',
          [
            'calculs' => $calculs,
            'datas' => $datas,
            'form' => $form->createView()
          ]
        );
    }

}

