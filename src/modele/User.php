<?php

class User{
    private $db;
    private $connect;
    private $insert;
    private $get;
    private $updateByUser;
    private $updateMdp;
    private $updateLastLogin;
    private $select;
    private $delete;
    private $selectSpeFonction;


    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("select id, email, fonction, password, lastLogin from User where email=:email");
        $this->get = $this->db->prepare("select U.*, F.libelle from User U join Fonction F on U.fonction = F.id where U.id=:id");
        $this->insert = $this->db->prepare("insert into User (nom, prenom, email, password, dateEmbauche, fonction) values (:nom, :prenom, :email, :password, :dateEmbauche, :fonction)");
        $this->updateByUser = $this->db->prepare("UPDATE User set nom=:nom, prenom=:prenom, email=:email where id=:id;");
        $this->select = $db->prepare("select u.id, nom, prenom, email, dateEmbauche, numSecu, f.libelle as libellefonction from User u, Fonction f where u.fonction = f.id order by nom");
        $this->selectSpeFonction = $db->prepare("select u.id, nom, prenom, email, dateEmbauche, f.libelle as libellefonction from  User u JOIN Fonction f ON  u.fonction = f.id where u.fonction=:foncId order by nom");
        $this->delete = $db->prepare("delete from User where id=:id");
        $this->updateMdp = $this->db->prepare("update User set password=:password where id=:id");
        $this->updateLastLogin = $this->db->prepare("update User set lastLogin=:lastLogin where id=:id");

    }

    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
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

    public function updateMdp($id, $password){
        $r = true;
        $this->updateMdp->execute(array(':id'=>$id, ':password'=>$password));
        if ($this->updateMdp->errorCode()!=0){
            print_r($this->updateMdp->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function updateLastLogin($id, $lastLogin){
        $r = true;
        $this->updateLastLogin->execute(array(':id'=>$id, ':lastLogin'=>$lastLogin));
        if ($this->updateLastLogin->errorCode()!=0){
            print_r($this->updateLastLogin->errorInfo());
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

    public function updateByUser($id, $nom, $prenom, $email) {
        $r = true;
        $this->updateByUser->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':id'=>$id));
        if ($this->updateByUser->errorCode()!=0){
            print_r($this->updateByUser->errorInfo());
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

    public function selectSpeFonction($foncId){
        $this->selectSpeFonction->execute(array(':foncId'=>$foncId));
        if ($this->selectSpeFonction->errorCode()!=0){
            print_r($this->selectSpeFonction->errorInfo());
        }
        return $this->selectSpeFonction->fetchAll();
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