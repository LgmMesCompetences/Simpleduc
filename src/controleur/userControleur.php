<?php

use OTPHP\TOTP;

function profileControleur($twig, $db) {
	include '../config/parametres.php';
	$utilisateur = new User($db);
	$qualificationObjet = new Qualification($db);
	$fiche = new Fiche($db);
	$id = $_SESSION['id'];
	$user = $utilisateur->get($id);
	$qualification = $qualificationObjet->get($id);
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
	}

	$error = null;
	$good = null;

	if (isset($_POST['btnUpdate'])){
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$email = $_POST['email'];
		$dfa = $_POST['2fa'];
		if ($dfa == 'email') {
			$otpKey = null;
		}else {
			if($_SESSION['user']['dfaType'] != 'otp') {
				$otpKey = TOTP::create()->getSecret();
			}else {
				$otpKey = $_SESSION['user']['otpKey'];
			}
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = 'Email invalide';
		}
		if (strlen($nom) == 0 || strlen($prenom) == 0) {
			$error = 'Les champs du nom et du prénom ne peuvent pas être vides !';
		}
		if (!in_array($dfa, ['email', 'otp'])) {
			$error = 'Seul l\'email et l\'OTP sont acceptés pour la double authentification';
		}

		if ($error == null) {
			$exec = $utilisateur->updateByUser($id, $nom, $prenom, $email, $dfa, $otpKey);
			if (!$exec) {
				$error = 'erreur';
			}else {
				$_SESSION['showOtp'] = ($dfa == 'otp' && $_SESSION['user']['dfaType'] != 'otp');

				$_SESSION['login'] = $email;
				$_SESSION['user']['nom'] = $nom;
				$_SESSION['user']['prenom'] = $prenom;
				$_SESSION['user']['dfaType'] = $dfa;
				$_SESSION['user']['otpKey'] = $otpKey;

				header('Location:profile');
				return;
			}
		}
	}

	if (isset($_POST['btAjoutQualif'])) {
		$newQualif = $_POST['newQualif'];
		if (strlen($newQualif) == 0) {
			$error = 'Le champ ne doit pas être vide !';
		}
		if ($error == null) {
			$exec = $qualificationObjet->add($newQualif,$user['id']);
		}
		header('Location:profile#mesQualif');

	}

	if (isset($_POST['btnUpdateQualif'])) {
		$contenu = $_POST['qualification'];
		$qualification = $qualificationObjet->clear($user['id']);
		foreach ($contenu as $qualif) {
			if (strlen($qualif) != 0) {
				$qualificationObjet->add($qualif,$user['id']);
			}
		}
		header('Location:profile');

	}

	if (isset($_POST['btnUpdatePswd'])){
		$pswdOld = $_POST['passwordO'];
		$pswdNew = $_POST['passwordN'];
		$pswdRep = $_POST['passwordR'];

		$user = $utilisateur->get($_SESSION['id']);

		if(strlen($pswdOld) == 0 || strlen($pswdNew) == 0 || strlen($pswdRep) == 0) {
			$error = 'Tous les champs doivent être renseignés !';
		}elseif(!password_verify($pswdOld, $user['password'])) {
			$error = 'L\'ancien mot de passe ne correspond pas';
		}elseif($pswdNew != $pswdRep){
			$error = 'Les mots de passe ne correspondent pas';
		}
		elseif (strlen($pswdNew) < 8){
			$error = 'Merci de spécifier un mot de passe d\'au moins 8 caractères !';
		}
		elseif (!preg_match('/[a-z]+[A-Z]+[0-9]+[\W]+/', $pswdNew)){
			$error = 'Le mot de passe doit comporter au moins: 1 minuscule, 1 majuscule, 1 chiffre et 1 caractère spécial !';
		}

		if ($error == null) {
			$utilisateur->updateMdp($_SESSION['id'], password_hash($pswdNew, PASSWORD_DEFAULT));
			$good = true;
		}
	}

	if (isset($_SESSION['showOtp']) && $_SESSION['showOtp']) {
		$otp = TOTP::create($_SESSION['user']['otpKey']);
		$otp->setLabel($_SESSION['login']);
        $otp->setIssuer('Simpl\'Educ');

		$otpUri = $otp->getProvisioningUri();

		unset($_SESSION['showOtp']);
	}else {
		$otpUri = null;
	}

	echo $twig->render('user/profile.html.twig', [
		'user' => $user,
		'error' => $error,
		'good' => $good,
		'years' => $years,
		'otpUri' => urlencode($otpUri),
		'qualification' => $qualification
	]);
}

function ficheDlControleur($twig, $db) {
	$id = $_GET['id'];
	if($id != null) {
		$ficheP = new Fiche($db);

		$fiche = $ficheP->getById($id);

		if ($fiche != null) {
			if ($fiche['proprietaire'] == $_SESSION['id']) {
				$file = '../storage/'.$fiche['cheminFichier'];
				$dateEmission = DateTimeImmutable::createFromFormat('Y-m-d', $fiche['dateEmission']);

				if (file_exists($file)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/pdf');
					header('Content-Disposition: attachment; filename=fiche-paie-'.$dateEmission->format('d-m-Y').'.pdf');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					ob_clean();
					flush();
					readfile($file);
					exit;
				}else {
					header('Location: profile#fichePaie?error=dontexist');
				}
			}else {
				header('Location: profile#fichePaie?error=unavailable');
			}
		}else {
			header('Location: profile#fichePaie?error=unexisting');
		}
	}else {
		header('Location: profile#fichePaie?error=noId');
	}

	header('Location: profile#fichePaie');
}

function dlDonneesControleur($twig, $db) {
	$userM = new User($db);
	$user = $userM->get($_SESSION['id']);

	$zip = new ZipArchive();
	$filename = '../storage/temp/'.md5($user['id']).'.zip';

	if (file_exists($filename)) {
		unlink($filename);
	}

	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Impossible d'ouvrir le fichier <$filename>\n");
		header('Location: profile');
	}else {
		$ficheM = new Fiche($db);		
		$fiches = $ficheM->get($_SESSION['id']);

		foreach ($fiches as $fiche) {
			$dateEmission = DateTimeImmutable::createFromFormat('Y-m-d', $fiche['dateEmission']);
			$zip->addFile('../storage/'.$fiche['cheminFichier'], 'fiche-paie/fiche-paie-'.$dateEmission->format('d-m-Y').'.pdf');
		}

		$date = DateTimeImmutable::createFromFormat('Y-m-d', $user['dateEmbauche']);

		$userData='Nom: '.$user['nom']."\n";
		$userData='Prenom: '.$user['prenom']."\n";
		$userData.='Email: '.$user['email']."\n";
		$userData.='Numero de Securite Social: '.$user['numSecu']."\n\n";
		$userData.='Date d\'embauche: '.$date->format('d/m/Y')."\n";
		$userData.='Fonction: '.$user['libelle']."\n";

		$zip->addFromString("utilisateur.txt", $userData);

		$zip->close();

		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename=donnees-personnel-'.strtolower($user['nom']).'-'.strtolower($user['prenom']).'.zip');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		ob_clean();
		flush();
		readfile($filename);
		exit;
	}
}