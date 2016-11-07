<?php

// SOURCES :
// https://www.urssaf.fr/portail/home/taux-et-baremes/taux-de-cotisations/les-professions-liberales/bases-de-calcul-et-taux-des-coti.html#FilAriane
// http://www.rsi.fr/baremes/cotisations.html
// http://service.cipav-retraite.fr/cipav/rubrique-104-montant-des-cotisations.htm
// http://service.cipav-retraite.fr/cipav/article-11-votre-protection-sociale-99.htm
// http://www.cnavpl.fr/les-chiffres-cles/principaux-parametres-du-regime-de-base/principaux-parametres-variables-du-regime-de-base/

$datas['pass'] = 38616;
$datas['pass_annee_precedente'] = 38040;
$datas['pass_annee_suivante'] = $datas['pass'];

// TVA
$datas['tva']['normale'] = 20;
$datas['tva']['intermediaire'] = 10;
$datas['tva']['reduite'] = 5.5;

// à quel organismes appartiennent quelle cotisation
// Utile pour regrouper ensuite les cotisations par organismes
$datas['organismes'] = [
  'urssaf' => [
    'label' => 'URSSAF',
    'cotisations' => [
      'allocationsFamiliales',
      'formationProfessionnelle',
      'csgDeductible',
      'csgNonDeductible',
      'crds'
    ],
  ],
  'rsi' => [
    'label' => 'RSI',
    'cotisations' => [
      'maladieMaternite',
    ],
  ],
  'cipav' => [
    'label' => 'CIPAV',
    'cotisations' => [
      'assuranceVieillesseBase',
      'assuranceVieillesseComplementaire',
      'invaliditeDeces',
    ],
  ],
];

// en premiere année, certaines cotisations sont calculées sur des bases forfaitaires
// https://www.urssaf.fr/portail/home/taux-et-baremes/taux-de-cotisations/les-professions-liberales/bases-de-calcul-forfaitaire-annu.html
$datas['basesForfaitairesAnnee1'] = [
  'baseCalculCotisationsSociales' => round($datas['pass'] * 0.19),
  'assuranceVieillesseBase' => 721,
];

// en deuxieme années, les cotisations sont calculées sur des bases cotisationaire
$datas['basesForfaitairesAnnee2'] = [
  'baseCalculCotisationsSociales' => round($datas['pass_annee_suivante'] * 0.27),
  'assuranceVieillesseBase' => 1024,
];

/**
 * URSSAF
 * https://www.urssaf.fr/portail/home/taux-et-baremes/taux-de-cotisations/les-professions-liberales/bases-de-calcul-et-taux-des-coti.html#FilAriane
 */

// [x] Maladie et maternité
$datas['maladieMaternite'] = [
  'organisme' => 'URSSAF',
  'label' => 'Maladie Maternité',
  'type' => 'tranche_exclusive',
  'tranches' => [
    [
      'taux' => 6.50,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];

// [x] Allocation familiales
$datas['allocationsFamiliales'] = [
  'organisme' => 'URSSAF',
  'label' => 'Allocations familiales',
  'type' => "tranche_exclusive",
  'description' => "Pour les revenus compris entre 42 478 € et 54 062 €, taux progressif : entre 2,15 % et 5,25 %",
  'tranches' =>[
    [
      'taux' => 2.15,
      'plafond' => 42478,
    ],
    // en fait, le taux est progressif entre 2,15 % et 5,25 %
    // pour les revenus compris entre 42 478 € et 54 062 €. On tire l'estimation vers le haut.
    [
      'taux' => 5.25,
      'plafond' => PHP_INT_MAX,
    ],
  ]
];

// [x] CSG-CRDS
$datas['csgCrds'] = [
  'organisme' => 'URSSAF',
  'label' => 'Maladie Maternité',
  'description' => "Totalité du revenu de l’activité non salariée + cotisations sociales obligatoires",
  'type' => 'tranche_exclusive',
  'tranches' => [
    [
      'taux' => 8,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];

// Formation professionnelle : le PASS * 0.25%. Tout bêtement.
$datas['formationProfessionnelle'] = [
  'organisme' => 'URSSAF',
  'label' => 'Formation Professionnelle',
  'type' => 'tranche_exclusive',
  'description' => 'Sur la base de ' . $datas['pass'],
  'tranches' => [
    [
      'cotisation' => $datas['pass'] * 0.0025,
      'plafond' => PHP_INT_MAX,
      'baseCalcul' => $datas['pass'],
      'taux' => 0.25,
    ],
  ],
];

// Retraite de base CNAVPL
// http://service.cipav-retraite.fr/cipav/article-33-recapitulatif-des-options-de-cotisation-104.htm
$datas['assuranceVieillesseBase'] = [
  'label' => 'Retraite de base',
  'description' => "Retraite de base CNAVPL",
  'revenusNonConnus' => 3324 + 6137,
  'forfait' => [
    'plafond' => 9171,
    'total' => 190,
  ],
  'tranches' => [
    // sous 4441, cotisation forfaitaire
    [
      'plafond' =>  4441,
      'cotisation' => 448,
    ],
    [
      'plafond' =>  $datas['pass'],
      'taux' => 8.23,
    ],
    [
      'plafond' => 193080,
      'taux' => 1.87
    ],
  ],
];


// =============

$datas['prevoyance'] = [
  'description' => "76, 228, ou 380 euros suivant la classe choisie",
];

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


// Assurance vieillesse complémentaire (obligatoire)
// http://service.cipav-retraite.fr/cipav/article-33-recapitulatif-des-options-de-cotisation-104.htm
$datas['assuranceVieillesseComplementaire'] = [
  'label' => 'Retraite complémentaire',
  'type' => "tranche_exclusive",
  'tranches' => [
    [
      'nom' => 'A',
      'plafond' => 26420,
      'cotisation' => 1198,
      'points' => 36,
    ],
    [
      'nom' => 'B',
      'plafond' => 48890,
      'cotisation' => 2395,
      'points' => 72,
    ],
    [
      'nom' => 'C',
      'plafond' => 57500,
      'cotisation' => 3593,
      'points' => 108,
    ],
    [
      'nom' => 'D',
      'plafond' => 66000,
      'cotisation' => 5989,
      'points' => 180,
    ],
    [
      'nom' => 'E',
      'plafond' => 82260,
      'cotisation' => 8394,
      'points' =>  252,
    ],
    [
      'nom' => 'F',
      'plafond' => 102560,
      'cotisation' => 13175,
      'points' => 396,
    ],
    [
      'nom' => 'G',
      'plafond' => 122560,
      'cotisation' => 14373,
      'points' => 432,
    ],
    [
      'nom' => 'H',
      'plafond' => PHP_INT_MAX,
      'cotisation' => 15570,
      'points' => 468,
    ],
  ],
];

// http://service.cipav-retraite.fr/cipav/article-33-recapitulatif-des-options-de-cotisation-104.htm
$datas['invaliditeDeces'] = [
  'classes' => [
    'a' => [
      'nom' => 'A',
      'cotisation' => 76,
    ],
    'b' => [
      'nom' => 'B',
      'cotisation' => 228,
    ],
    'c' => [
      'nom' => 'C',
      'cotisation' =>380,
    ],
  ]

];

// Réduction assurance vieillesse complémentaire
$datas['assurance_vieillesse_complementaire_reduction'] = [
  'label' => 'Réduction assurance vieillesse complémentaire',
  'type' => "tranche_exclusive",
  'tranches' => [
    [
      'plafond' => 5632,
      'taux' => 100,
    ],
    [
      'plafond' => 11264,
      'taux' => 0.75,
    ],
    [
      'plafond' =>  16897,
      'taux' => 0.50,
    ],
    [
      'plafond' => 22529,
      'taux' => 0.25,
    ]
  ],
];

// les professions libérales ne cotisent pas pour les indemnités journalières
// source : http://www.rsi.fr/baremes/cotisations.html
$datas['indemnitesJournalieres'] = [];

return $datas;
