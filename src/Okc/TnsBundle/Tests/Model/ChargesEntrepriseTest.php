<?php

namespace Okc\TnsBundle\Tests\Model;
use Okc\TnsBundle\Model\ChargesEntreprise;

class ChargesEntrepriseTest extends \PHPUnit_Framework_TestCase
{

    protected $simulateur;

    function setup() {
        $this->simulateur = new ChargesEntreprise();
    }

    function testCalculTranchesParTaux() {
        $somme = 40000;
        $expected_tranche_1 = 5718;
        $expected_tranche_2 = 626.604;
        $expected_total_charge = 6344.604;
        $datas = [
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
        $result = $this->simulateur->calculTranchesParTaux($datas, $somme);
        $this->assertEquals(round($expected_tranche_1), round($result['tranches'][0]['cotisation']));
        $this->assertEquals(round($expected_tranche_2), round($result['tranches'][1]['cotisation']));
        $this->assertEquals(round($expected_total_charge), round($result['total']));
    }

    function testCalculTranchesParForfait() {
        $datas = [
          'label' => 'Retraite complémentaire',
          'tranches' => [
            [
              'plafond' => 26420,
              'cotisation' => 1198,
            ],
            [
              'plafond' => 102560,
              'cotisation' => 13175,
            ],
            [
              'plafond' => 122560,
              'cotisation' => 14373,
            ],
            [
              'plafond' => PHP_INT_MAX,
              'cotisation' => 15570,
            ],
          ],
        ];
        $result = $this->simulateur->calculTranchesParForfait($datas, 0);
        $this->assertEquals(round($result['total']), 1198);

        $result = $this->simulateur->calculTranchesParForfait($datas, 30000);
        $this->assertEquals(round($result['total']), 13175);

        $result = $this->simulateur->calculTranchesParForfait($datas, 102560);
        $this->assertEquals(round($result['total']), 13175);

        $result = $this->simulateur->calculTranchesParForfait($datas, 102561);
        $this->assertEquals(round($result['total']), 14373);

        $result = $this->simulateur->calculTranchesParForfait($datas, 130000);
        $this->assertEquals(round($result['total']), 15570);
    }


}