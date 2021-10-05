<?php

function profileControleur($twig, $db) {
	$utilisateur = new User($db);
	$id = $_SESSION['id'];
	$user = $utilisateur->get($id);

	$error = null;
	$good = null;
	if (isset($_POST['btnUpdate'])){
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$email = $_POST['email'];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 'Email invalide';
		}
		if (strlen($nom) == 0 || strlen($prenom) == 0) {
			$error = 'Le nom et le prenom ne peuvent pas être vides';
		}

		if ($error == null) {
			$exec = $utilisateur->updateByUser($id, $nom, $prenom, $email);
			if (!$exec) {
				$error = 'erreur';
			}else {
				header('Location:profile');
			}
		}
	}

	if (isset($_POST['btnUpdatePswd'])){
		$pswdOld = $_POST['passwordO'];
		$pswdNew = $_POST['passwordN'];
		$pswdRep = $_POST['passwordR'];

		$user = $utilisateur->get($_SESSION['id']);

		if(strlen($pswdOld) == 0 || strlen($pswdNew) == 0 || strlen($pswdRep) == 0) {
			$error = 'Tous les champs doivent être renseignés';
		}elseif(!password_verify($pswdOld, $user['password'])) {
			$error = 'L\'ancien mot de passe ne correspond pas';
		}elseif($pswdNew != $pswdRep){
			$error = 'Les mots de passe ne correspondent pas';
		}

		if ($error == null) {
			$utilisateur->updateMdp($_SESSION['id'], password_hash($pswdNew, PASSWORD_DEFAULT));
			$good = true;
		}
	}

	echo $twig->render('user/profile.html.twig', [
		'user' => $user,
		'error' => $error,
		'good' => $good
	]);
}