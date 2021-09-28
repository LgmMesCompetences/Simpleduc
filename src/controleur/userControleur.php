<?php

function profileControleur($twig, $db) {
	$utilisateur = new User($db);
	$id = $_SESSION['id'];
	$user = $utilisateur->get($id);

	$error = null;
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
				$user['nom'] = $nom;
				$user['prenom'] = $prenom;
				$user['email'] = $email;
			}
		}
	}

	echo $twig->render('user/profile.html.twig', [
		'user' => $user,
		'error' => $error
	]);
}