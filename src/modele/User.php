<?php

class User{
    private $db;
    private $connect;

    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("select id, email, nom, prenom, fonction, password from User where email:=email");
    }

    public function connect($email){
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch(); 
    }



}