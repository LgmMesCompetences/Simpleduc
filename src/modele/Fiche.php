<?php

class Fiche {
    private $db;
    private $get;
    private $insert;

    public function __construct($db) {
        $this->db = $db;
        $this->get = $this->db->prepare("select * from FichePaie where proprietaire=:id");
        $this->insert = $this->db->prepare("insert into FichePaie(proprietaire, dateEmission, cheminFichier, heuresPayees, dateDebutPaie, dateFinPaie, tauxHoraire, tauxCompIncap, tauxCompSante, tauxSecuPla, tauxSecuDepla, tauxCompTrancheFirst, tauxCSGDeducIR, tauxCSGnonDeducIR, secuMaladie, accidentTra, famille, chomage, autresContrib, prevoyance, cotisStat, exoEmp, exoRegul) values (:proprietaire, :dateEmission, :nomFichier, :heuresPayees, :dateDebutPaie, :dateFinPaie, :tauxHoraire, :tauxCompIncap, :tauxCompSante, :tauxSecuPla, :tauxSecuDepla, :tauxCompTrancheFirst, :tauxCSGDeducIR, :tauxCSGnonDeducIR, :secuMaladie, :accidentTra, :famille, :chomage, :autresContrib, :prevoyance, :cotisStat, :exoEmp, :exoRegul)");
    }

    public function get(int $id){
        $unUtilisateur = $this->get->execute(array(':id'=>$id));
        if ($this->get->errorCode()!=0){
            print_r($this->get->errorInfo());
        }
        return $this->get->fetchAll();
    }

    public function insert($proprietaire, $dateEmission, $cheminFichier, $heuresPayees, $dateDebutPaie, $dateFinPaie, $tauxHoraire, $tauxCompIncap, $tauxCompSante, $tauxSecuPla, $tauxSecuDepla, $tauxCompTrancheFirst, $tauxCSGDeducIR, $tauxCSGnonDeducIR, $secuMaladie, $accidentTra, $famille, $chomage, $autresContrib, $prevoyance, $cotisStat, $exoEmp, $exoRegul){
        $r = true;

        $this->insert->execute(array(':proprietaire'=>$proprietaire, ':dateEmission'=>$dateEmission, ':nomFichier'=>$cheminFichier, ':heuresPayees'=>$heuresPayees, ':dateDebutPaie'=>$dateDebutPaie, ':dateFinPaie'=>$dateFinPaie, ':tauxHoraire'=>$tauxHoraire, ':tauxCompIncap'=>$tauxCompIncap, ':tauxCompSante'=>$tauxCompSante, ':tauxSecuPla'=>$tauxSecuPla, ':tauxSecuDepla'=>$tauxSecuDepla, ':tauxCompTrancheFirst'=>$tauxCompTrancheFirst, ':tauxCSGDeducIR'=>$tauxCSGDeducIR, ':tauxCSGnonDeducIR'=>$tauxCSGnonDeducIR, ':secuMaladie'=>$secuMaladie, ':accidentTra'=>$accidentTra, ':famille'=>$famille, ':chomage'=>$chomage, ':autresContrib'=>$autresContrib, ':prevoyance'=>$prevoyance, ':cotisStat'=>$cotisStat, ':exoEmp'=>$exoEmp, ':exoRegul'=>$exoRegul));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }    
}