<?php
/**
 * AJout date et organisme pour charges à prevoir
 */
namespace Okc\TnsBundle\Model;

abstract class ChargesEntreprise {

    // Chiffre d'affaire hors taxe
    public $caHt = 0;

    // Cotisation foncière
    public $cfe = 0;
    // cotisations sociales déjà versée lors de l'année N
    public $cotisationsSocialesDejaVerseesEnN = 0;

    // Frais de la société, hors taxe.
    public $frais = 0;

    // indique si l'entreprise est en année 1, année 2 ou régime
    // établi. En un année 1 et 2, les cotisations sociales prévisionnelles
    // sont calculées sur une base forfaitaires.
    // on reste en régime établi (?) par défaut pour les calculs.
    public $cotisationRegime = 'regime_etabli';

    // taux et baremes pour les calculs
    protected $datas = [];

    /**
     * @param array $variables
     * @param $datas
     * @param array $variables
     */
    function __construct($datas, $variables = [])
    {
        $this->datas = $datas;
        if (!empty($variables))
        {
            foreach ($variables as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }

    function getCaHt()
    {
        return $this->caHt;
    }

    /**
     * Magic method : automatically call {Property} if method exists,
     * call simply the corresponding property otherwise.
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        static $values = [];
        if (isset($values[$property])) return $values[$property];
        $accessor = $property;
        $value = method_exists($this, $accessor) ? $this->$accessor() : $this->$property;
        $values[$property] = $value;
        return $value;
    }

    function calculTranchesParForfait($datas, $somme)
    {
        $cotisation = 0;
        $id_tranche = 0;
        foreach ($datas['tranches'] as $id => $tranche)
        {
            if ($somme <= $tranche['plafond']) {
                $cotisation = $tranche['cotisation'];
                $id_tranche = $id;
                break;
            }
        }
        $datas['total'] = $cotisation;
        $datas['trancheActive'] = $datas['tranches'][$id_tranche];
        return $datas;
    }

    /**
     * Methode générique pour calculer le montant d'un impot / d'une cotisation
     * en fonction d'un bareme de tranche avec pourcentage
     * @param array $datas
     * @param float $somme
     * @return array
     */
    function calculTranchesParTaux($datas, $somme)
    {

        $datas['total'] = 0;
        foreach ($datas['tranches'] as $id => $tranche)
        {


            if (!is_array($tranche))
            {
                // can't throw exception because we use __toString to render template..
                trigger_error(__CLASS__ . '::' . __FUNCTION__ . " : Les données de tranches sont mal formatées : " . var_export($datas, TRUE), E_USER_ERROR);
            }
            // calcul automatique du plancher. Le plancher est le plafond de la tranche précédente,
            // sauf pour la premiere tranche (qui n'a pas de tranche précédente) pour lequel le plancher est mis à zéro.
            $plancher = !empty($datas['tranches'][$id-1]['plafond']) ? $datas['tranches'][$id-1]['plafond'] : 0;
            // on calcul le montant de l'intervalle de la tranche courante (ex : tranche de 1000 à 1500 = intervalle de 500 euros)
            $datas['tranches'][$id]['intervalle']  = $tranche['plafond'] - $plancher;

            // si la somme est supérieure au plafond de la tranche courante ...
            if ($somme >= $tranche['plafond'])
            {
                // ... on calcule le montant dû pour la tranche courante
                $datas['tranches'][$id]['cotisation'] = ($datas['tranches'][$id]['intervalle'] * $tranche['taux']) / 100;
                $datas['tranches'][$id]['baseCalcul'] = $datas['tranches'][$id]['intervalle'];
                // on ajoute le montant de la cotisation de cette tranche au total.
                $datas['total'] += $datas['tranches'][$id]['cotisation'];
            }
            // mais si la somme est inférieure au plafond courant, c'est que nous sommes à la dernière tranche qui nous intéresse pour le calcul
            else
            {
                // on calcule le montant pour cette derniere tranche
                $depassement_plancher = $somme - $plancher;
                if ($depassement_plancher > 0)
                {
                    $datas['tranches'][$id]['baseCalcul'] = $depassement_plancher;
                    $datas['total'] += $datas['tranches'][$id]['cotisation'] = $depassement_plancher * $datas['tranches'][$id]['taux'] / 100;
                }
                // si le depassement du plancher est négatif, c'est qu'on est passé dans les tranches supérieurs
                // à la derniere "imposable". On indique tout de même une cotisation de zéro pour information.
                else
                {
                    $datas['tranches'][$id]['cotisation'] = 0;
                    $datas['tranches'][$id]['baseCalcul'] = 0;
                }

            }

        }
        return $datas;
    }

    /**
     * Retourne les meta datas de l'organisme chargé de récupérer la cotisation passé en arguments
     */
    function getOrganisme($searched_cotisationId)
    {
        foreach ($this->datas['organismes'] as $organismeId => $datas)
        {
            foreach ($datas['cotisations'] as $cotisationId)
            {
                if ($cotisationId == $searched_cotisationId)
                {
                    return $datas;
                }
            }
        }
    }

    function getTotalCotisationsSocialesParOrganismes()
    {
        $totaux = [];
        foreach ($this->datas['organismes'] as $organismeId => $datas)
        {
            $totaux[$organismeId]['total'] = 0;
            $totaux[$organismeId]['label'] = $datas['label'];
            foreach ($datas['cotisations'] as $cotisationId)
            {
                $method = $cotisationId;
                $totaux[$organismeId]['total'] += $this->$method()['total'];
            }
        }
        return $totaux;
    }

}