<?php

function newPayCheckControleur($twig) {
	echo $twig->render('paycheck/newPayCheck.html.twig', array());
	//lien de bareme IS en fonction des salaires
	//https://www.service-public.fr/particuliers/vosdroits/F1419


	//proposition d'ajout pour l'entité fiche de paie
	//heuresPayess, debutPaieMoinsUn, finPaieMoinsUn, tauxHoraire, tauxSecuPatronale tauxCompIncap, tauxCompSante, tauxAccidentW
	//tauxSecuPla, tauxSecuDepla, tauxCompTrancheFirst, tauxFamille, tauxChom
	//tauxAutreContrib, tauxForfaitSoc, tauxCotisConv, tauxCSGDeducIR, tauxExoEmp
	//tauxExoEmpRegul, tauxCSGnonDeducIR, tauxIRperso
}


function listePayCheckControleur($twig) {
	echo $twig->render('paycheck/payCheckTemplate.html.twig', array());
}


?>