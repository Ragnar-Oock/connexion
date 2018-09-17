<?php
include("./vues/v_enteteConnexion.php");
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}

//test de connexion
switch($_REQUEST['action']){
	//le choix de la première arrivé sur la page
	case 'demandeConnexion':{
		//on ferme toutes sessions actives au cas où
		session_unset();
		//redirection sur la page de connexion
		include("vues/v_connexion.php");
		break;
	}

  //lorque l'on clique sur le bouton CONNEXION
	case 'valideConnexion':{
		$utilisateur=$pdo->getConnexion($_REQUEST['login'], $_REQUEST['mdp']);
		if(!is_array( $utilisateur)){
			// si le formulaire de connexion ne renvoi pas un tableau, il manque le login ou le mot de passe
			$_SESSION['connexionErreur']='mdp';
			// on renvoi sur la case erreur 'mpd' de v_connexion.php
			include("vues/v_connexion.php");
		}
		else{
			//sinon les 2 champs sont remplis
			connecter($utilisateur['uId'], $utilisateur['gId'], $utilisateur['uEtat'], $utilisateur['uActif']);
			//on appel la fonction connecter qui rentre ['des valeurs'] dans $utilisateur
			if ($_SESSION['gId']==0) {
				//on teste sur l'utilisateur est admin (uId==0)
				$_SESSION['heureConnexion']=time();
				//enregistrement de l'heure de connexion
				header ('location: index.php?choixTraitement=admin&action=voir');
				//on renvoi vers le pannel admin
			}
			else {
				//sinon on est pas admin
				//on test si l'utilisateur est encore actif
				if ($utilisateur['uEtat']=='FALSE') {
					//l'utilisateur n'est pas/plus actif
					$_SESSION['connexionErreur']='validite';
					//on renvoi vers la case erreur 'validité'
					include('./vues/v_connexion.php');
					//on renvoi vers la vue connecion
				}
				else{
				//l'utilisateur est actif
				$paysConnexion = $pdo->ajoutPaysConnexion($_SESSION['uId'] ,$pdo->getPays($pdo->getIp()));
				// $pdo->getPays($pdo->getIp());
				$pdo->statNbCo($_SESSION['uId']);
				//on recupère dans la BDD le pays de connexion, et on ajout 1 au nombres de connexions
				$_SESSION['heureConnexion']=time();
				//enregistrement de l'heure de connexion
				header ('location: index.php?choixTraitement=page');
				//on redirige vers les pages
				}
			}
		}
		break;
	}

	default :{
		$formulaire = "frmIdentification";
		$champ = "login";
		include("vues/v_connexion.php");
		break;
	}
}
?>
