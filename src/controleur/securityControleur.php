<?php

function securityControleur($twig, $db) {
	$form = array();

	if (isset($_POST['btConnecter'])){
		$form['valide'] = true;
		$email = $_POST['email'];
		$password = $_POST['password'];
		$utilisateur = new User($db);
		$unUtilisateur = $utilisateur->connect($email);
		if ($unUtilisateur!=null){
			if(!password_verifiy($password, $unUtilisateur['password'])){
				$form['valide'] = false;
				$form['message'] = 'Login ou mot de passe incorrect !';
			}
			else{
				$_SESSION['login'] = $email;
				$_SESSION['role'] = $fonction;
				header("Location:index.php");
			}
		}
		else{
			$form['valide'] = false;
			$form['message'] = 'Login ou mot de passe incorrect !';
		}
	}
	echo $twig->render('security.html.twig', array());
}

function deconnexionControleur($twig, $db){
	session_unset();
	session_destroy();
	header("Location:index.php");
}


?>