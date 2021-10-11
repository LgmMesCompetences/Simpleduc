<?php

function profileControleur($twig, $db) {
	include '../config/parametres.php';
	$utilisateur = new User($db);
	$fiche = new Fiche($db);
	$id = $_SESSION['id'];
	$user = $utilisateur->get($id);

	$fiches = $fiche->get($id);
	if($config['debug']) dump($fiches);
	$years=null;

	if(count($fiches)!=0){

	

	$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $french_months = array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');

	foreach ($fiches as $fiche) {
		$date = new DateTime($fiche['dateEmission']);
		$date->setTimezone(new DateTimeZone('Europe/Paris'));
		$year = $date->format('Y');
		$month = str_replace($english_months, $french_months, $date->format('F'));

		$years[$year][$month] = $fiche;
	}
	if($config['debug']) dump($years);
	}
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
				$_SESSION['login'] = $email;
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
/*
	$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
	$mpdf->WriteHTML($twig->render('emails/email2FA.html.twig'));
	$mpdf->Output('../storage/'.md5(uniqid()).'.pdf', 'F');
*/
	echo $twig->render('user/profile.html.twig', [
		'user' => $user,
		'error' => $error,
		'good' => $good,
		'years' => $years
	]);
}