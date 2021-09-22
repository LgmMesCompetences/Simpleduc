<?php
function getPage($db){
    $lesPages['accueil'] = "accueilControleur";
    $lesPages['404'] = "notfoundControleur";
    $lesPages['mentions'] = "mentionsControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur";
    $lesPages['connexion'] = "securityControleur;0";
    $lesPages['profile'] = "profileControleur;0";

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
		$role = $explose[1];

		if($role!=0) {
			if(isset($_SESSION['login'])){
				if($_SESSION['role']<$role){
					$contenu = 'accueil';
				}else {
					$contenu = $explose[0];
				}
			}else {
				$contenu = 'accueil';
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