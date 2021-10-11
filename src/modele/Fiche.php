<?php

class Fiche {
    private $db;
    private $get;

    public function __construct($db) {
        $this->db = $db;
        $this->get = $this->db->prepare("select * from FichePaie where proprietaire=:id");

    }

    public function get(int $id){
        $unUtilisateur = $this->get->execute(array(':id'=>$id));
        if ($this->get->errorCode()!=0){
            print_r($this->get->errorInfo());
        }
        return $this->get->fetchAll();
    }
}