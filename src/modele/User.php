<?php

class User{
    private $db;
    private $connect;
    private $insert;


    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("select id, email, nom, prenom, fonction, password from User where email:=email");
        $this->insert = $this->db->prepare("insert into User (nom, prenom, email, password, dateEmbauche, fonction) values (:nom, :prenom, :email, :password, :dateEmbauche, :fonction)");
    }

    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch(); 
    }

    public function insert($nom, $prenom, $email, $password, $dateEmbauche, $fonction){
        $r = true;
        $this->insert->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':password'=>$password, ':dateEmbauche'=>$dateEmbauche, ':fonction'=>$fonction));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }

}