<?php
	//https://validator.w3.org/nu/#textarea to validate the html template

function newPayCheckControleur($twig, $db) {
	$form = array();
	$utilisateur = new User($db);
	$user = $utilisateur->get($_GET['id']);

	$user['dateEmbauche'] = DateTimeImmutable::createFromFormat('Y-m-d', $user['dateEmbauche']);
	$nomFichier = md5(uniqid()).'.pdf';

	if (isset($_POST['btCreer'])){
		$proprietaire = $_GET['id'];
		$dateEmission = new \DateTime();
		$cheminFichier = $nomFichier;
		$heuresPayees = $_POST['heuresPayees'];
		$dateDebutPaie = $_POST['dateDebutPaie'];
		$dateFinPaie = $_POST['dateFinPaie'];
		$tauxHoraire = $_POST['tauxHoraire'];
		$tauxCompIncap = $_POST['tauxCompIncap'];
		$tauxCompSante = $_POST['tauxCompSante'];
		$tauxSecuPla = $_POST['tauxSecuPla'];
		$tauxSecuDepla = $_POST['tauxSecuDepla'];
		$tauxCompTrancheFirst = $_POST['tauxCompTrancheFirst'];
		$tauxCSGDeducIR = $_POST['tauxCSGDeducIR'];
		$tauxCSGnonDeducIR = $_POST['tauxCSGnonDeducIR'];

		$secuMaladie = $_POST['secuMaladie'];
		$accidentTra = $_POST['accidentTra'];
		$famille = $_POST['famille'];
		$chomage = $_POST['chomage'];
		$autresContrib = $_POST['autresContrib'];
		$prevoyance = $_POST['prevoyance'];
		$cotisStat = $_POST['cotisStat'];
		$exoEmp = $_POST['exoEmp'];
		$exoRegul = $_POST['exoRegul'];
	
		$form['valide'] = true;
		$fiche = new Fiche ($db);
		$exec = $fiche->insert($proprietaire, $dateEmission->format("Y-m-d"), $cheminFichier, $heuresPayees, $dateDebutPaie, $dateFinPaie, $tauxHoraire, $tauxCompIncap, $tauxCompSante, $tauxSecuPla, $tauxSecuDepla, $tauxCompTrancheFirst, $tauxCSGDeducIR, $tauxCSGnonDeducIR, $secuMaladie, $accidentTra, $famille, $chomage, $autresContrib, $prevoyance, $cotisStat, $exoEmp, $exoRegul);

		if (!$exec){
			$form['valide'] = false;
			$form['message'] = 'L\'insertion du fichier a échoué !';
		}else{
			$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
			$mpdf->WriteHTML($twig->render('paycheck/payCheckTemplate.html.twig', ['user'=>$user]));
			//$mpdf->Output();
			$mpdf->Output('../storage/'.$nomFichier, 'F');
		}
	}
	echo $twig->render('paycheck/newPayCheck.html.twig', array('form'=>$form, 'u'=>$user));
}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>