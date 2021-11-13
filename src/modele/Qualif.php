<?php

class Qualification {
    private $db;
    private $get;
    private $add;
    private $modif;
    private $delete;
    private $clear;

    public function __construct($db) {
        $this->db = $db;
        $this->get = $this->db->prepare("SELECT * FROM Qualification WHERE user=:id");
        $this->add = $this->db->prepare("INSERT INTO Qualification(contenu, user) VALUES(:contenu, :user)");
        $this->modif = $this->db->prepare("UPDATE Qualification SET contenu=:contenu WHERE id=:id");
        $this->delete = $db->prepare("DELETE FROM Qualification WHERE id=:id");
        $this->clear = $db->prepare("DELETE FROM Qualification WHERE user=:user");

    }

    public function get(int $id){
        $unUtilisateur = $this->get->execute(array(':id'=>$id));
        if ($this->get->errorCode()!=0){
            print_r($this->get->errorInfo());
        }
        return $this->get->fetchAll();
    }

    public function add($contenu, $user){
        $r = true;
        $this->add->execute(array(':contenu'=>$contenu, ':user'=>$user));
        if ($this->add->errorCode()!=0){
            print_r($this->add->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function modif($id, $contenu) {
        $r = true;
        $this->modif->execute(array(':contenu'=>$contenu,':id'=>$id));
        if ($this->modif->errorCode()!=0){
            print_r($this->modif->errorInfo());
            $r=false;
        }
        return $r;
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

    public function clear($user){
    $r = true;
        $this->clear->execute(array(':user'=>$user));
        if ($this->clear->errorCode()!=0){
            print_r($this->clear->errorInfo());
            $r = false;
        }
        return $r;
    }
}