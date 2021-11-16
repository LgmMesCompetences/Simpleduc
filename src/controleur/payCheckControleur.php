<?php
	//https://validator.w3.org/nu/#textarea to validate the html template

function newPayCheckControleur($twig, $db) {
	$form = array();
	$fiche = new Fiche ($db);
	$utilisateur = new User($db);
	$user = $utilisateur->get($_GET['id']);

	$form['last'] = $fiche->getLast($_GET['id']);

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
	
		if (!is_numeric($heuresPayees) || 
			!is_numeric($tauxHoraire) ||
			!is_numeric($tauxCompIncap) ||
			!is_numeric($tauxCompSante) ||
			!is_numeric($tauxSecuPla) ||
			!is_numeric($tauxSecuDepla) ||
			!is_numeric($tauxCompTrancheFirst) ||
			!is_numeric($tauxCSGDeducIR) ||
			!is_numeric($tauxCSGnonDeducIR) ||
			!is_numeric($secuMaladie) ||
			!is_numeric($accidentTra) ||
			!is_numeric($famille) ||
			!is_numeric($chomage) ||
			!is_numeric($autresContrib) ||
			!is_numeric($prevoyance) ||
			!is_numeric($cotisStat) ||
			!is_numeric($exoEmp) ||
			!is_numeric($exoRegul)
			) {
				$form['valide'] = false;
				$form['message'] = 'Valeur(s) incorrecte(s) saisie(s) !';
			}
		else{
			if ($form['last'] != null) {
				$lastDate = DateTimeImmutable::createFromFormat('Y-m-d', $form['last']['dateEmission']);
				$now = new DateTimeImmutable();

				if($now->diff($lastDate)->days == 0){
					unlink('../storage/'.$form['last']['cheminFichier']);
					$fiche->delete($form['last']['id']);
				}
			}

			$form['valide'] = true;
			$exec = $fiche->insert($proprietaire, $dateEmission->format("Y-m-d"), $cheminFichier, $heuresPayees, $dateDebutPaie, $dateFinPaie, $tauxHoraire, $tauxCompIncap, $tauxCompSante, $tauxSecuPla, $tauxSecuDepla, $tauxCompTrancheFirst, $tauxCSGDeducIR, $tauxCSGnonDeducIR, $secuMaladie, $accidentTra, $famille, $chomage, $autresContrib, $prevoyance, $cotisStat, $exoEmp, $exoRegul);
	
			if (!$exec){
				$form['valide'] = false;
				$form['message'] = 'L\'insertion du fichier a échoué !';
			}else{
				$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
				$mpdf->WriteHTML($twig->render('paycheck/payCheckTemplate.html.twig', ['user'=>$user, 'heuresP'=>$heuresPayees, 'dateD'=>$dateDebutPaie, 'dateF'=>$dateFinPaie, 'tauxH'=>$tauxHoraire, 'tauxInc'=>$tauxCompIncap, 'tauxS'=>$tauxCompSante, 'tauxSecuP'=>$tauxSecuPla, 'tauxSecuD'=>$tauxSecuDepla, 'tauxFirst'=>$tauxCompTrancheFirst, 'CSGd'=>$tauxCSGDeducIR, 'CSGnD'=>$tauxCSGnonDeducIR, 'secu'=>$secuMaladie, 'accident'=>$accidentTra, 'fam'=>$famille, 'chom'=>$chomage, 'autres'=>$autresContrib, 'prev'=>$prevoyance, 'stat'=>$cotisStat, 'exoE'=>$exoEmp, 'exoReg'=>$exoRegul]));
				//$mpdf->Output();
				$mpdf->Output('../storage/'.$nomFichier, 'F');
			}
		}
	}
	echo $twig->render('paycheck/newPayCheck.html.twig', array('form'=>$form, 'u'=>$user));
}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/listePayCheck.html.twig', array());
}


?>