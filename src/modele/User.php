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
    private $selectByEmail;
    private $setToken;
    private $getToken;


    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("SELECT id, email, fonction, password, lastLogin FROM User WHERE email=:email");
        $this->get = $this->db->prepare("SELECT U.*, F.libelle FROM User U JOIN Fonction F ON U.fonction = F.id WHERE U.id=:id");
        $this->insert = $this->db->prepare("INSERT INTO User (nom, prenom, email, password, dateEmbauche, fonction, numSecu) VALUES (:nom, :prenom, :email, :password, :dateEmbauche, :fonction, :numSecu)");
        $this->updateByUser = $this->db->prepare("UPDATE User SET nom=:nom, prenom=:prenom, email=:email, dfaType=:dfaType, otpKey=:otpKey WHERE id=:id;");
        $this->select = $db->prepare("SELECT u.id, nom, prenom, email, dateEmbauche, numSecu, f.libelle AS libellefonction FROM User u, Fonction f WHERE u.fonction = f.id ORDER BY nom");
        $this->selectSpeFonction = $db->prepare("SELECT u.id, nom, prenom, email, dateEmbauche, f.libelle AS libellefonction FROM  User u JOIN Fonction f ON  u.fonction = f.id WHERE u.fonction=:foncId ORDER BY nom");
        $this->selectByEmail = $db->prepare("SELECT id FROM User WHERE email=:email");
        $this->delete = $db->prepare("DELETE FROM User WHERE id=:id");
        $this->updateMdp = $this->db->prepare("UPDATE User SET password=:password WHERE id=:id");
        $this->updateLastLogin = $this->db->prepare("UPDATE User SET lastLogin=:lastLogin WHERE id=:id");
        $this->setToken = $this->db->prepare("UPDATE User SET token=:token WHERE id=:id");
        $this->getToken = $this->db->prepare("SELECT id FROM User WHERE token=:token");

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

    public function getWithObject($id){
        $unUtilisateur = $this->get->execute(array(':id'=>$id));
        if ($this->get->errorCode()!=0){
            print_r($this->get->errorInfo());
        }

        $usr = $this->get->fetch();

        $usr['dateEmbauche'] = \DateTimeImmutable::createFromFormat('Y-m-d', $usr['dateEmbauche']);

        return $usr;
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

    public function setToken($id, $token){
        $r = true;
        $this->setToken->execute(array(':id'=>$id, ':token'=>$token));
        if ($this->setToken->errorCode()!=0){
            print_r($this->setToken->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function getByToken($token){
        $this->getToken->execute(array(':token'=>$token));
        if ($this->getToken->errorCode()!=0){
            print_r($this->getToken->errorInfo());
        }
        return $this->getToken->fetch();
    }

    public function insert($nom, $prenom, $email, $password, $dateEmbauche, $fonction, $numSecu){
        $r = true;
        $this->insert->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':password'=>$password, ':dateEmbauche'=>$dateEmbauche, ':fonction'=>$fonction, ':numSecu'=>$numSecu));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function updateByUser($id, $nom, $prenom, $email, $dfaType, $otpKey = null) {
        $r = true;
        $this->updateByUser->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':email'=>$email, ':dfaType'=>$dfaType, ':otpKey'=>$otpKey,':id'=>$id));
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

    public function selectByEmail($email){
        $this->selectByEmail->execute(array(':email'=>$email));
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
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