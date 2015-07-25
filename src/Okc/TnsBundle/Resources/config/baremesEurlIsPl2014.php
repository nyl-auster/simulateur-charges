<?php

// http://www.urssaf.fr/images/ref_DEP07-DebActiv-2492_W.pdf
// http://www.rsi.fr/baremes/cotisations.html
// http://service.cipav-retraite.fr/cipav/rubrique-104-montant-des-cotisations.htm
// http://service.cipav-retraite.fr/cipav/article-11-votre-protection-sociale-99.htm
// http://www.cnavpl.fr/les-chiffres-cles/principaux-parametres-du-regime-de-base/principaux-parametres-variables-du-regime-de-base/

// import des paramètres globaux pour 2014
$datas = include "baremes_generaux_2014.php";

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

// en premiere année, certaines cotisations
// sont calculées sur des bases cotisationaires
$datas['basesForfaitairesAnnee1'] = [
  'baseCalculCotisationsSociales' => round($datas['pass'] * 0.19),
  'assuranceVieillesseBase' => 721,
];

// en deuxieme années, les
$datas['basesForfaitairesAnnee2'] = [
  'baseCalculCotisationsSociales' => round($datas['pass2015'] * 0.27),
  'assuranceVieillesseBase' => 1024,
];

// Formation professionnelle
$datas['formationProfessionnelle'] = [
  'label' => 'Formation Professionnelle',
  'tranches' => [
    [
      'cotisation' => $datas['pass2013'] * 0.0025,
      'plafond' => PHP_INT_MAX,
      'baseCalcul' => $datas['pass2013'],
      'taux' => 0.25,
    ],
  ],
  'description' => "0,25 % du pass 2013 soit 94 euros",
];

// Allocation familiales
$datas['allocationsFamiliales'] = [
  'label' => 'Allocations familiales',
  'tranches' =>
    [
      [
        'taux' => 5.25,
        'plafond' => PHP_INT_MAX,
      ]
    ],
];

// Maladie et maternité
$datas['maladieMaternite'] = [
  'label' => 'Maladie Maternité',
  'tranches' => [
    [
      'taux' => 6.5,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];

// Crds = 0,5
// Csg déductible = 5,1
// Csg non déductible = 2,4
$datas['csgDeductible'] = [
  'label' => 'CSG Déductible',
  'tranches' => [
    [
      'taux' => 5.1,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];
$datas['csgNonDeductible'] = [
  'label' => 'CSG Non Déductible',
  'tranches' => [
    [
      'taux' => 2.4,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];

$datas['crds'] = [
  'label' => 'CRDS',
  'tranches' => [
    [
      'taux' => 0.5,
      'plafond' => PHP_INT_MAX,
    ]
  ],
];

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

// Assurance vieillesse de base
// http://service.cipav-retraite.fr/cipav/article-33-recapitulatif-des-options-de-cotisation-104.htm
$datas['assuranceVieillesseBase'] = [
  'label' => 'Retraite de base',
  'revenusNonConnus' => 3324 + 6137,
  'forfait' => [
    'plafond' => 9171,
    'total' => 190,
  ],
  'tranches' => [
    [
      //'plafond' => round($datas['pass'] * 0.85),
      'plafond' => 31916,
      'taux' => 10.1,
    ],
    [
      //'plafond' => round($datas['pass'] * 5),
      'plafond' => 187740,
      'taux' => 1.87
    ],
  ],
];

// Assurance vieillesse complémentaire (obligatoire)
// http://service.cipav-retraite.fr/cipav/article-33-recapitulatif-des-options-de-cotisation-104.htm
$datas['assuranceVieillesseComplementaire'] = [
  'label' => 'Retraite complémentaire',
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
