<?php

class User{
    private $db;
    private $connect;
    private $insert;
    private $get;
    private $updateByUser;

    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("select id, email, fonction, password from User where email=:email");
        $this->get = $this->db->prepare("select U.*, F.libelle from User U join Fonction F on U.fonction = F.id where U.id=:id");
        $this->insert = $this->db->prepare("insert into User (nom, prenom, email, password, dateEmbauche, fonction) values (:nom, :prenom, :email, :password, :dateEmbauche, :fonction)");
        $this->updateByUser = $this->db->prepare("UPDATE User set nom=:nom, prenom=:prenom, email=:email where id=:id;");
    }

    public function connect($email){
        $unUtilisateur = $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch(); 
    }

    public function get($id){
        $unUtilisateur = $this->get->execute(array(':id'=>$id));
        if ($this->get->errorCode()!=0){
            print_r($this->get->errorInfo());
        }
        return $this->get->fetch(); 
    }

    public function insert($nom, $prenom, $email, $password, $dateEmbauche, $fonction){
        $r = true;
        $this->insert->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':password'=>$password, ':dateEmbauche'=>$dateEmbauche, ':fonction'=>$fonction));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function updateByUser($id, $nom, $prenom, $email) {
        $r = true;
        $this->updateByUser->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':id'=>$id));
        if ($this->updateByUser->errorCode()!=0){
            print_r($this->updateByUser->errorInfo());
            $r=false;
        }
        return $r;
    }
}