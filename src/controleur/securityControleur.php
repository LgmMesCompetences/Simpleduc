<?php

function inscrireControleur($twig, $db){

	$form = array();
	if (isset($_POST['btInscrire'])){
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$dateEmbauche = $_POST['dateEmbauche'];
		$fonction = $_POST['fonction'];
		$form['valide'] = true;

		if ($password!=$password2){
			$form['valide'] = false;
			$form['message'] = 'Les mots de passe sont différents !';
		}
		else{
			$utilisateur = new User($db);
			$exec = $utilisateur->insert($nom, $prenom, $email, password_hash($password, PASSWORD_DEFAULT), $dateEmbauche, $fonction);
			if (!$exec){
				$form['valide'] = false;
				$form['message'] = 'Problème d\'ajout de compte !';
			}
		$form['email'] = $email;
		$form['fonction'] = $fonction;		
		}
	}
	echo $twig->render('security/ajout.html.twig', array('form'=>$form));
}


//TODO créer la fonction updateMDP !!!!!!!!!!!

function securityControleur($twig, $db) {
	$form = array();

	if (isset($_POST['btConnecter'])){
		$form['valide'] = true;
		$email = $_POST['email'];
		$password = $_POST['password'];

		$utilisateur = new User($db);
		$unUtilisateur = $utilisateur->connect($email);
		if ($unUtilisateur!=null){
			if(!password_verify($password, $unUtilisateur['password'])){
				$form['valide'] = false;
				$form['message'] = 'Login ou mot de passe incorrect !';
			}
			else{
				$_SESSION['login'] = $email;
				$_SESSION['role'] = $unUtilisateur['fonction'];
				header("Location:index.php?page=profile");
			}
		}
		else{
			$form['valide'] = false;
			$form['message'] = 'Login ou mot de passe incorrect !';
		}
	}
	echo $twig->render('security/connexion.html.twig', array());
}

function deconnexionControleur($twig, $db){
	session_unset();
	session_destroy();
	header("Location:index.php?page=connexion");
}