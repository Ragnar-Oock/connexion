<?php

//classe regroupant les fonction d'acces a la base de données
class PdoBD
{
	private static $serveur='mysql:host=leabergefbbdd.mysql.db';
	private static $bdd='dbname=leabergefbbdd';
	private static $user='leabergefbbdd';
	private static $mdp='1Freedom';
	private static $monPdo;
	private static $monPdoBD=null;

	//constreur de la classe
	private function __construct()
	{
		PdoBD::$monPdo = new PDO(PdoBD::$serveur.';'.PdoBD::$bdd, PdoBD::$user, PdoBD::$mdp);
		PdoBD::$monPdo->query("SET CHARACTER SET utf8");
		PdoBD::$monPdo->query("SET lc_time_names = 'fr_FR';");
  	// Start the session
  	session_start();
	}
	//destructeur de la classe
	public function _destruct()
	{
		PdoBD::$monPdo = null;
	}
	/*création d'une unique instance la classe Pdo*/
	public static function getPdoBD()
	{
		if(PdoBD::$monPdoBD==null)	{PdoBD::$monPdoBD= new PdoBD();}
		return PdoBD::$monPdoBD;
	}

	//fonction de connexion
	public function getConnexion($login, $mdp)
	{
		//récupération du sel unique de l'utilisateur necessaire a la verification du mdp
		$req = "SELECT 	utilisateurs.uSalt
						FROM utilisateurs
						WHERE uLogin='".$login."'";
		//execution de la requete
		$rs = PdoBD::$monPdo->query($req);
		//si la requete retourne "false" comme resultat
		if ($rs === false)
		{
			//alors on affiche l'erreur envoyée par SQL avec un petit message d'information
			afficherErreurSQL("erreur select : uSalt", $req, PdoBD::$monPdo->errorInfo());
		}
		$salt = $rs->fetch();
		$mdp = hash('sha256', substr(hash('sha256', $mdp), 0, 32).$salt["uSalt"].substr(hash('sha256', $mdp), 32, 32));

		//création de la requete de verification des informations
		$req = "SELECT 	utilisateurs.uId,
										droits.gId,
										IF(DATE_ADD(utilisateurs.uActif, INTERVAL 14 DAY)>CURRENT_DATE(), 'TRUE', 'FALSE') as uEtat,
										DATE_FORMAT(utilisateurs.uActif, '%W %e %M %Y à %H:%i') as uActif
						FROM utilisateurs LEFT OUTER JOIN droits ON utilisateurs.uId=droits.uId
						WHERE uLogin='".$login."' and
									uMdp='".$mdp."';";
		//execution de la requete
		$rs = PdoBD::$monPdo->query($req);
		//si la requete retourne "false" comme resultat
		if ($rs === false)
		{
			//alors on affiche l'erreur envoyée par SQL avec un petit message d'information
			afficherErreurSQL("erreur select : uId, gId, uActif", $req, PdoBD::$monPdo->errorInfo());
		}
		//on transforme la réponse de la base de données en un tableau
		$ligne = $rs->fetch();
		//que l'on retourne
		return $ligne;
	}

	//fonction appelé lors de la déconnexion pour stocker le temps total de connexion dans la BDD
	public function statTemps()
	{
		//on verifie si l'utilisateur qui se deconnecte n'est pas admin (on ne prend pas de statistique de l'utilisateur admin)
		if ($_SESSION['gId']!=0) {
			//calcul du temps connecté
			$tempsTotal = time() - $_SESSION['heureConnexion'];
			//création de la requete
			$req = "UPDATE utilisateurs SET uTempsCo=uTempsCo+".$tempsTotal." WHERE uId=".$_SESSION['uId'].";";
			//execution de la requete
			$rs = PdoBD::$monPdo->exec($req);
		}
	}

	//fonction qui recupère l'ip d'un utilisateur
	public function getIp()
	{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //on récupère l'ip de l'utilisateur
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //on verifie si l'ip passe par un proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
		//on retourne l'ip trouvée
    return $ip;
	}
	//fonction retournant le pays de l'ip entrée en parametre
	public function getPays($ip)
	{
		$curlPays = curl_init();
		// $url = "http://ip-api.com/php/".$ip;
		$url = "http://ip-api.com/php/";
		$status = curl_setopt_array($curlPays, array(
    	CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_CONNECTTIMEOUT => 3,
    	CURLOPT_URL => $url
		));

		$pays = curl_exec($curlPays);

		$_SESSION["Pays"] = $pays;

		if ($pays["status"] === "success" && $status)
		{
			echo "1";
			var_dump($_SESSION["Pays"]);
			echo "<br> 2".$_SESSION["Pays"]."<br>";
			return $pays["countryCode"];
		}
		else {
			echo "curl fail";
			return "--";
		}
	}
	//fonction d'ajout d'utilisateur, le mdp est hasher en sha256 avec 3 sels
	public function ajouterUtilisateur($login, $mdp)
	{
		if (($login!=NULL) && ($mdp!=NULL)) {
			//hashage et salage du mot de passe
			$salt = getRandomString(64);
			$mdp = hash('sha256', substr(hash('sha256', $mdp), 0, 32).$salt.substr(hash('sha256', $mdp), 32, 32));

			//création du nouvel utilisateur
			$req = "INSERT INTO utilisateurs (uLogin, uMdp) VALUES ('".$login."', '".$mdp."');";
			$rs1 = PdoBD::$monPdo->exec($req);

			//récupération de l'identifiant numerique de l'utilisateur
			$req = "SELECT uId FROM utilisateurs WHERE uLogin='".$login."';";
			$rs2 = PdoBD::$monPdo->query($req);
			$reponse = $rs2->fetch();

			//création des droits de l'utilisateur
			$uId = $reponse['uId'];
			$req = "INSERT INTO droits (bNum, uId, gId) VALUES ('0', '".$uId."', '1')";
			$rs3 = PdoBD::$monPdo->exec($req);

			//si la requete renvoie "false"
      if ($rs1 === false) {
				//alors
				//si la réponse contient le mot "Duplicate"
        if (stristr($req, 'Duplicate') === FALSE){
					//alors retourner le text idoine a afficher
          return "<p>Le nom d'utilisateur existe d&eacute;j&agrave;.</p>
					<button onclick='location.href=\"index.php?choixTraitement=admin&action=nouveau\"'>OK</button>";
        }
			  else{
					//sinon on ne connais pas la cause de l'erreur
          return "<p>Probl&egrave;me lors de l'ajout de l'utilisateur.</p>
					<button onclick='location.href=\"index.php?choixTraitement=admin&action=nouveau\"'>OK</button>";
        }

				afficherErreurSQL("erreur verification duplicate", $req, PdoBD::$monPdo->errorInfo());
      }
      else{
        return true;
      }
		}
		else {
			return "<p>Erreur de saissie du mot de passe ou du login.</p>
			<button onclick='location.href=\"index.php?choixTraitement=admin&action=nouveau\"'>OK</button>";
		}
	}
	//fonction d'affichage des utilisateurs
	public function getUtilisateurs()
	{
		$req = "SELECT 	utilisateurs.uId,
										uLogin,
										gLabel,
										uNbCo,
										uFicheConsulte,
										uTempsCo,
										DATE_FORMAT(utilisateurs.uActif, '%d-%m-%Y') as date,
                    IF(DATE_ADD(utilisateurs.uActif, INTERVAL 14 DAY)>CURRENT_DATE(), '<span class=\"vert\">Activé</span>', '<span class=\"rouge\">Désactivé</span>') as etat,
										DATE_FORMAT(DATE_ADD(utilisateurs.uActif, INTERVAL 14 DAY), '%d-%m-%Y') as valide
						FROM utilisateurs INNER JOIN droits ON utilisateurs.uId=droits.uId
															INNER JOIN grades ON droits.gId=grades.gId
						WHERE droits.gId != 0
						ORDER BY uId;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {
			afficherErreurSQL("erreur select : uId, uLogin, gLabel, uNbCo, uFicheConsulte, uTempsCo, uActif", $req, PdoBD::$monPdo->errorInfo());
		}
		$lesLignesTypes = $rs->fetchAll();
		return $lesLignesTypes;
	}
	//fontion de suppression d'un utilisateur
	public function supprimerUtilisateurs($uId)
	{
		if($uId!=0){
		$req = "DELETE FROM utilisateurs WHERE uId='$uId';";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {afficherErreurSQL("Probleme lors de la suppression de l'utilisateur dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());}
		}
		else {
			return "Erreur: Cet utilisateur est protégé";
		}
	}
	//fonction de mise à jour des droits d'un utilisateur
	public function majDroitsUtilisateur($uId,$bNum,$gId)
	{
		$req = "INSERT INTO droits SET
					bNum=".$bNum.", uId=".$uId.", gId=".$gId.";";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {afficherErreurSQL("erreur update bNum, uId, gId", $req, PdoBD::$monPdo->errorInfo());}
	}
	//fonction de renseignement du pays lors de la connexion dans la base donnée
	public function ajoutPaysConnexion($uId, $pId)
	{
		$req = "INSERT INTO connexions SET uId=".$uId.", pId=(SELECT pId FROM paysConnexion WHERE alpha2='".$pId."');";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {afficherErreurSQL("Probleme lors de la mise à jour des stats de connexion dans la base de donn&eacute;es.", $req, PdoBD::$monPdo->errorInfo());}
	}
	//fonction d'affichage des grades
	public function getGrades()
	{
	  $req = "SELECT * FROM grades ORDER BY gId asc;";
	  $rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {afficherErreurSQL("erreur select grades*", $req, PdoBD::$monPdo->errorInfo());}
	  $lesLignesTypes = $rs->fetchAll();
	  return $lesLignesTypes;
	}
	//fonction d'affichage des bases
	public function getBases()
	{
	  $req = "SELECT * FROM bases WHERE bNum!=0 ORDER BY bNum asc;";
	  $rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {afficherErreurSQL("erreur select bases*", $req, PdoBD::$monPdo->errorInfo());}
	  $lesLignesTypes = $rs->fetchAll();
	  return $lesLignesTypes;
	}
	//fonction retournant les pays et dates de connexion enterieures d'un utilisateur
	public function getLoginPays($uId)
	{
		$req = "SELECT 	paysConnexion.pPays,
										DATE_FORMAT(cDate, '%d-%m-%Y %T') as cDate
						FROM connexions INNER JOIN paysConnexion ON connexions.pId = paysConnexion.pId
						WHERE uId='".$uId."'
						ORDER BY cId DESC;";
		$rs = PdoBD::$monPdo->query($req);
		if ($rs === false) {afficherErreurSQL("erreur select pPays, cDate", $req, PdoBD::$monPdo->errorInfo());}
		$lesLignesTypes = $rs->fetchAll();
		return $lesLignesTypes;
	}
  //fonction d'ajout d'une BDD
  public function ajouterBDD($nomBDD)
	{
		$req = "INSERT INTO `bases` (`bNom`) VALUES ('".$nomBDD."');";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			if (stristr($req, 'Duplicate') === true){
				echo "La base de donn&eacute;e existe d&eacute;j&agrave;.<br>";
			}
			else{
				afficherErreurSQL("erreur insert bNom", $req, PdoBD::$monPdo->errorInfo());
			}
		}
		else{
			echo "Base de données ajout&eacute;e avec succ&eacute;s.<br>";
		}
	}
	//fonction de comptage des connexions
	public function statNbCo($uId)
	{
		$req = "UPDATE utilisateurs SET uNbCo=uNbCo+1 WHERE uId='".$uId."';";
		$rs = PdoBD::$monPdo->exec($req);
	}
	//fonction servant a prolonger ou renouveler les les acces d'un utilisateur
	public function validerUtilisateur($uId)
	{
		$req = "UPDATE utilisateurs SET utilisateurs.uActif = NOW() WHERE utilisateurs.uId='".$uId."' ";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("erreur update uActif", $req, PdoBD::$monPdo->errorInfo());
		}
	}
	//function comptant le nombre de pages visitées par un utilisateur
	public function ficheConsulte()
	{
		$req = "UPDATE utilisateurs SET uFicheConsulte=uFicheConsulte+1 WHERE uId='".$_SESSION['uId']."';";
		$rs = PdoBD::$monPdo->exec($req);
		if ($rs === false) {
			afficherErreurSQL("erreur update uFicheConsulte", $req, PdoBD::$monPdo->errorInfo());
		}
	}
}
?>
