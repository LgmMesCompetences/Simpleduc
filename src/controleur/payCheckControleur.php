<?php
	//lien de bareme IS en fonction des salaires
	//https://www.service-public.fr/particuliers/vosdroits/F1419


	//proposition d'ajout pour l'entité fiche de paie
	//heuresPayees, debutPaieMoinsUn, finPaieMoinsUn, tauxHoraire, tauxSecuPatronale tauxCompIncap, tauxCompSante, tauxAccidentW
	//tauxSecuPla, tauxSecuDepla, tauxCompTrancheFirst, tauxFamille, tauxChom
	//tauxAutreContrib, tauxForfaitSoc, tauxCotisConv, tauxCSGDeducIR, tauxExoEmp
	//tauxExoEmpRegul, tauxCSGnonDeducIR, tauxIRperso

function newPayCheckControleur($twig, $db) {
	$form = array();
	$utilisateur = new User($db);
	$user = $utilisateur->get($id);

	if (isset($_POST['btCreer'])){
		


	/*
		$mpdf = new \Mpdf\Mpdf(['tempDir' => '../mpdf']);
		$mpdf->WriteHTML($twig->render('paycheck/.html.twig'));
		$mpdf->Output('../storage/'.md5(uniqid()).'.pdf', 'F');
	*/
	
	}
	echo $twig->render('paycheck/newPayCheck.html.twig', array('form'=>$form, 'user'=>$user));



}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>