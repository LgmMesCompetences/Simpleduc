<?php
function getPage($db){
    $lesPages['accueil'] = "accueilControleur;0";
    $lesPages['404'] = "notfoundControleur;0";
    $lesPages['mentions'] = "mentionsControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur;0";
    $lesPages['connexion'] = "connexionControleur;0";
    $lesPages['firstLogin'] = "firstLoginControleur;1,2,3,4,5";
    $lesPages['mdpoublie'] = "updatemdpControleur;0";
    $lesPages['deconnexion'] = "deconnexionControleur;0";
    $lesPages['profile'] = "profileControleur;1,2,3,4,5";
    $lesPages['ajout'] = "inscrireControleur;4,5";
    $lesPages['listeUser'] = "listeControleur;4,5";

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