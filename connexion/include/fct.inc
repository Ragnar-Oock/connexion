<?php
/**
 * Fonctions php pour l'application
  * @version 1.0 version MVC modifiee le 6 février 2014 par Blain Pascal
 */

 /**
 * Teste si un utilisateur est connecté (return vrai ou faux)
 */
function estConnecte(){
  return isset($_SESSION['uId']);
}
/**
 * Enregistre dans des variables de session les informations d'un agent
 */
function connecter($uId, $gId, $uEtat, $uActif){
	$_SESSION['uId']=$uId;
  $_SESSION['gId']=$gId;
  $_SESSION['uEtat']=$uEtat;
  $_SESSION['uActif']=$uActif;
}
/**
 * Detruit la session active
 */
function deconnecter(){
	session_destroy();
}
/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
 * @param $madate au format  jj/mm/aaaa
 * @return la date au format anglais aaaa-mm-jj
*/
function dateFrancaisVersAnglais($maDate){
	if(estDateValide($maDate)== true)
	{
	@list($jour,$mois,$annee) = explode('/',$maDate);
	return date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee));
	}
	else
	{
	return null;
	}
}
/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa
 * @param $madate au format  aaaa-mm-jj
 * @return la date au format format français jj/mm/aaaa
*/
function dateAnglaisVersFrancais($maDate)
{
   @list($annee,$mois,$jour)=explode('-',$maDate);
   $date="$jour"."/".$mois."/".$annee;
   return $date;
}
/**
 * retourne le mois au format aaaamm selon le jour dans le mois
 * @param $date au format  jj/mm/aaaa
 * @return le mois au format aaaamm
*/
function getMois($date){
		@list($jour,$mois,$annee) = explode('/',$date);
		if(strlen($mois) == 1){
			$mois = "0".$mois;
		}
		return $annee.$mois;
}

/**
 * retourne la date en texte
*/
function dateTexte($laDate){
		$NomDuJour = array ("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
		$NomDuMois = array ("janvier", "f&eacute;vrier", "mars", "avril", "mai", "juin", "juillet", "ao&ucirc;t", "septembre", "octobre", "novembre", "d&eacute;cembre");
		$lejour = date("j",$laDate);
		$lemois = $NomDuMois[date("n",$laDate)-1];
		$dateTexte=$NomDuJour[date("w",$laDate)]." ";
		if($lejour==01){$dateTexte.=' 1er '; }
		else {$dateTexte.=$lejour.' ';}
		$dateTexte.=$lemois.' '.date('Y', $laDate);
		return $dateTexte;
}

/**
 * retourne le mois en texte
*/
function moisTexte($mois){
$tabMois = array(	'01' => "Janvier",
					'02' => "F&eacute;vrier",
					'03' => "Mars",
					'04' => "Avril",
					'05' => "Mai",
					'06' => "Juin",
					'07' => "Juillet",
					'08' => "Ao&ucirc;t",
					'09' => "Septembre",
					'10' => "Octobre",
					'11' => "Novembre",
					'12' => "D&eacute;cembre");
		return $tabMois[$mois];
}

function timeVersDuree($temps)
{
  $sec=$temps;
  $min = intval($sec / 60);
  $heure = intval($min / 60);
  $jour = intval($heure / 24);
  $annee = intval($jour / 365);
  $duree="";
  if ($annee>0) {
    $duree = $annee." ann&eacute;e(s) ";
  }
  if ($jour % 365>0 ) {
    $duree = $duree.($jour % 365)." jour(s) ";
  }
  if ($heure % 24 > 0) {
    if ($heure % 24 >9) {
    $duree = $duree.($heure % 24)." : ";
    }
    else {
      $duree = $duree."0".($heure % 24)." : ";
    }
  }
  else {
    $duree = $duree."00 : ";
  }
  if($min % 60 > 0){
    if ($min % 60 > 9) {
      $duree = $duree.($min % 60)." : ";
    }
    else {
      $duree = $duree."0".($min % 60)." : ";
    }
  }
  else {
    $duree = $duree."00 : ";
  }
  if ($sec % 60 > 0) {
    if ($sec % 60 > 9) {
      $duree = $duree.($sec % 60);
    }
    else {
      $duree = $duree."0".($sec % 60);
    }
  }
  else {
    $duree = $duree."00";
  }
  return $duree;
}

/* gestion des erreurs*/
/**
 * Indique si une valeur est un entier positif ou nul
 * @param $valeur
 * @return vrai ou faux
*/
function estEntierPositif($valeur)
{
	return preg_match("/[^0-9]/", $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 * @param $tabEntiers : le tableau
 * @return vrai ou faux
*/
function estTableauEntiers($tabEntiers)
{
	$ok = true;
	foreach($tabEntiers as $unEntier)
	{
		if(!estEntierPositif($unEntier)){$ok=false;}
	}
	return $ok;
}
/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 * @param $dateTestee
 * @return vrai ou faux
*/
function estDateDepassee($dateTestee)
{
	$dateActuelle=date("d/m/Y");
	@list($jour,$mois,$annee) = explode('/',$dateActuelle);
	$annee--;
	$AnPasse = $annee.$mois.$jour;
	@list($jourTeste,$moisTeste,$anneeTeste) = explode('/',$dateTestee);
	return ($anneeTeste.$moisTeste.$jourTeste < $AnPasse);
}
/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 * @param $date
 * @return vrai ou faux
*/
function estDateValide($date)
{
	$tabDate = explode('/',$date);
	$dateOK = true;
	if (count($tabDate) != 3) {
	    $dateOK = false;
    }
    else {
		if (!estTableauEntiers($tabDate)) {
			$dateOK = false;
		}
		else {
			if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
				$dateOK = false;
			}
		}
    }
	return $dateOK;
}

/**
 * Ajoute le libelle d'une erreur au tableau des erreurs
 * @param $msg : le libellé de l'erreur
 */
function ajouterErreur($msg)
{
   if (! isset($_REQUEST['erreurs'])){
      $_REQUEST['erreurs']=array();
	}
   $_REQUEST['erreurs'][]=$msg;
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 * @return le nombre d'erreurs
 */
function nbErreurs()
{
   if (!isset($_REQUEST['erreurs'])){
	   return 0;
	}
	else{
	   return count($_REQUEST['erreurs']);
	}
}

/* afficherErreurSQL :
		Affichage de messages lors l'accès &agrave; la bdd avec une requete SQL
		@param $message	: message a afficher
		@param $req 	: requete executee
		@param $info	: erreur constatee
*/
function afficherErreurSQL($message, $req, $info) {
		echo $message . "<br />";
		echo "Requete : " . $req . "<br />";
		echo "Code erreur : " . $info[0] . ", Message : " . $info[2];
		die();
}

//fonction d'erreur de mot de passe
function erreurMDP()
{
  echo"<div class='erreur'><p>Login ou mot de passe incorrect</p></div>";
}

//fonction creant un string de caracters aleatoires de longueur parametree
//utilisee pour le salage du mdp d'un utilisateur
function getRandomString($length)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $string = '';

  for ($i = 0; $i < $length; $i++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
  }

  return $string;
}
?>
