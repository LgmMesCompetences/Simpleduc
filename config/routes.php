<?php
function getPage($db){
    $lesPages['accueil'] = "connexionControleur;0";
    $lesPages['404'] = "notfoundControleur;0";
    $lesPages['mentions'] = "mentionsControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur;0";
    $lesPages['connexion'] = "connexionControleur;0";
    $lesPages['firstLogin'] = "firstLoginControleur;1,2,3,4,5";
    $lesPages['mdpoublie'] = "updatemdpControleur;0";
    $lesPages['deconnexion'] = "deconnexionControleur;0";
    $lesPages['profile'] = "profileControleur;1,2,3,4,5";
    $lesPages['ajout'] = "inscrireControleur;4,5";
    $lesPages['listeUser'] = "listeControleur;0,4,5";
    $lesPages['2FA'] = "dFAControleur;1,2,3,4,5";
    $lesPages['newPayCheck'] = "newPayCheckControleur;3";
    $lesPages['listePayCheck'] = "listePayCheckControleur;3";

if($db!=null) {
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }else {
        $page = 'accueil';
    }
    if(!isset($lesPages[$page])){
        $contenu = 'notfoundControleur';
    }else {
        $explose = explode(";", $lesPages[$page]);
		$roles = explode(',',$explose[1]);

        if(isset($_SESSION['lockFirst']) && $page != 'firstLogin') {
            header('Location:firstLogin');
        }

        if(isset($_SESSION['lock2FA']) && $page != '2FA') {
            header('Location:2FA');
        }

		if(!in_array(0, $roles)) {
			if(isset($_SESSION['login'])){
				if(!in_array($_SESSION['role'], $roles)){
					$contenu = 'accueilControleur';
				}else {
                    $contenu = $explose[0];
				}
			}else {
				$contenu = 'accueilControleur';
			}
		}else {
			$contenu = $explose[0];
		}
    }
}else {
    $contenu = 'maintenanceControleur';
}

return $contenu;
}