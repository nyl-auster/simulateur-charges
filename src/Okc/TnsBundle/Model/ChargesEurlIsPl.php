<?php
namespace Okc\TnsBundle\Model;

class ChargesEurlIsPl extends ChargesEntreprise
{

    // On protege les variables et on les rend accessibles uniquement par getter
    // pour éviter qu'une erreur dans la vue (assignation involontaire à une propriété publique)
    // ne modifie les résultats affichés à l'utilisateur.
    public $salaire = 0;

    //protected $totalCotisationsSocialesHorsCsg = 0;

    // données de syntheses des calculs :
    // @TODO laisser changer la classe invalidité
    public $classeInvaliditeDeces = 'a';

    function getSalaire()
    {
        return $this->salaire;
    }



    /**
     * Calcul de la base de calcul pour les cotisations sociales
     * @return float
     */
    function baseCalculCotisationsSociales()
    {
        if ($this->cotisationRegime == 'annee_1')
        {
            return $this->datas['basesForfaitairesAnnee1']['baseCalculCotisationsSociales'];
        }
        elseif($this->cotisationRegime == 'annee_2')
        {
            return $this->datas['basesForfaitairesAnnee2']['baseCalculCotisationsSociales'];
        }
        return $this->salaire;
    }

    /**
     * Calcul de la base de calcul pour les impots sur le revenu
     * @return float
     */
    function baseCalculIs()
    {
        $base_is = $this->caHt - $this->salaire - $this->totalCotisationsSociales() - $this->frais;
        return $base_is < 0 ? 0 : $base_is;
    }

    function baseCalculCsgCrds()
    {
        $cotisationsSocialesAReintegrer = $this->totalCotisationsSocialesHorsCsg();
        if ($this->cotisationsSocialesDejaVerseesEnN)
        {
            $cotisationsSocialesAReintegrer = $this->cotisationsSocialesDejaVerseesEnN;
        }
        return $this->baseCalculCotisationsSociales() + $cotisationsSocialesAReintegrer;
    }

    /**
     * @return float
     */
    function allocationsFamiliales()
    {
        return $this->calculTranchesParTaux($this->datas['allocationsFamiliales'], $this->baseCalculCotisationsSociales());
    }

    /**
     * @return float
     */
    function maladieMaternite()
    {
        return $this->calculTranchesParTaux($this->datas['maladieMaternite'], $this->baseCalculCotisationsSociales());
    }

    /**
     * @return float
     */
    function assuranceVieillesseBase()
    {
        // en premier année
        if ($this->cotisationRegime == 'annee_1')
        {
            return ['total' => $this->datas['basesForfaitairesAnnee1']['assuranceVieillesseBase']];
        }
        // en deuxieme année
        elseif ($this->cotisationRegime == 'annee_2')
        {
            return ['total' => $this->datas['basesForfaitairesAnnee2']['assuranceVieillesseBase']];
        }
        // en regime établi :
        else
        {
            // Si le revenu est inférieur au montant du premier plafond, on applique la cotisation indiquée à la premiere
            // tranche au lieu d'appliquée un taux comme pour les tranches suivantes.
            if ($this->baseCalculCotisationsSociales() < $this->datas['assuranceVieillesseBase']['forfait']['plafond'])
            {
                $cotisation = $this->datas['assuranceVieillesseBase']['forfait'];
            }
            else {
                $cotisation = $this->calculTranchesParTaux($this->datas['assuranceVieillesseBase'], $this->baseCalculCotisationsSociales());
            }
            return $cotisation;
        }
    }

    function assuranceVieillesseComplementaire()
    {
        //$result = $this->calculTranchesParForfait($this->datas['assuranceVieillesseComplementaire'], $this->baseCalculCotisationsSociales());
        return $this->calculTranchesParForfait($this->datas['assuranceVieillesseComplementaire'], $this->baseCalculCotisationsSociales());
    }

    function formationProfessionnelle()
    {
        return $this->calculTranchesParForfait($this->datas['formationProfessionnelle'], $this->baseCalculCotisationsSociales());
    }

    function csgDeductible()
    {
        return $this->calculTranchesParTaux($this->datas['csgDeductible'], $this->baseCalculCsgCrds());
    }

    function csgNonDeductible()
    {
        return $this->calculTranchesParTaux($this->datas['csgNonDeductible'], $this->baseCalculCsgCrds());
    }

    function crds()
    {
        return $this->calculTranchesParTaux($this->datas['crds'], $this->baseCalculCsgCrds());
    }

    function csgCrds()
    {
        return $this->csgDeductible()['total'] + $this->csgNonDeductible()['total'] + $this->crds()['total'];
    }

    function csgCrdsDeductible()
    {
        return $this->csgDeductible();
    }

    function csgCrdsNonDeductible()
    {
        return $this->csgCrdsNonDeductible() + $this->crds();
    }

    function invaliditeDeces()
    {
        return [
          'total' => $this->datas['invaliditeDeces']['classes'][$this->classeInvaliditeDeces]['cotisation'],
        ];
    }

    public function is()
    {
        return $this->calculTranchesParTaux($this->datas['is'], $this->baseCalculIs());
    }

    function totalCotisationsSocialesHorsCsg()
    {
        return
          $this->allocationsFamiliales()['total']
          + $this->maladieMaternite()['total']
          + $this->assuranceVieillesseComplementaire()['total']
          + $this->formationProfessionnelle()['total']
          + $this->assuranceVieillesseBase()['total'];
    }

    function totalCotisationsSociales()
    {
        return $this->totalCotisationsSocialesHorsCsg() + $this->csgDeductible()['total'] + $this->csgNonDeductible()['total'] + $this->crds['total'];
    }

    function benefices()
    {
        return $this->caHt - $this->frais - $this->salaire;
    }

    function chargesAprevoir()
    {
        return $this->totalCotisationsSociales()
        + $this->is()['total']
        + $this->cfe
        - $this->cotisationsSocialesDejaVerseesEnN;
    }

    function verdict()
    {
        return $this->benefices() - $this->chargesAprevoir();
    }

    function totalCharges()
    {
        return $this->totalCotisationsSociales() + $this->salaire + $this->frais + $this->cfe + $this->is()['total'];
    }

}
