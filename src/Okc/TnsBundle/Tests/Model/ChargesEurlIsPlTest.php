<?php

namespace Okc\TnsBundle\Tests\Model;
use Okc\TnsBundle\Model\ChargesEurlIsPl;

class ChargesEurlIsPlTest extends \PHPUnit_Framework_TestCase
{

    function testCalculTranchesParTaux() {
        $somme = 40000;
        $expected_tranche_1 = 5718;
        $expected_tranche_2 = 626.604;
        $expected_total_charge = 6344.604;
        $datas['is'] = [
          'label' => 'Impot sur les sociétés',
          'tranches' => [
            [
              'plafond' => 38120,
              'taux' => 15,
            ],
            [
              'plafond' => PHP_INT_MAX,
              'taux' => 33.33,
            ]
          ]
        ];
        $class = new ChargesEurlIsPl($datas);
        $result = $class->calculTranchesParTaux($datas['is'], $somme);


        $this->assertEquals(round($expected_total_charge), round($result['total']));
    }


}