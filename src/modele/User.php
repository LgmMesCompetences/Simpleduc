<?php

class User{
    private $db;
    private $connect;
    private $insert;
    private $select;
    private $delete;


    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("select email, fonction, password from User where email=:email");
        $this->insert = $this->db->prepare("insert into User (nom, prenom, email, password, dateEmbauche, fonction) values (:nom, :prenom, :email, :password, :dateEmbauche, :fonction)");
        $this->select = $db->prepare("select u.id, nom, prenom, email, dateEmbauche, f.libelle as libellefonction from User u, Fonction f where u.fonction = f.id order by nom");
        $this->delete = $db->prepare("delete from User where id=:id");
        $this->updateMdp = $this->db->prepare("update User set password=:password where id=:id");
    }

    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch(); 
    }

    public function updateMdp($id, $password){
        $r = true;
        $this->updateMdp->execute(array(':id'=>$id, ':password'=>$password));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
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

    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errocInfo());
        }
        return $this->select->fetchAll();
    }

    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}