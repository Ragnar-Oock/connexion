<?php

  require_once("include/fct.inc");
  require_once("include/class.pdo.php");
  include_once("include/simple_html_dom.php");

  $pdo = PdoBD::getPdoBD();

  //verification de la connexion
  if(!isset($_REQUEST['choixTraitement']) || !isset($_SESSION['uId']) || !isset($_SESSION['gId']))
  {
    $_REQUEST['choixTraitement']='connexion';
  }

  //verification du choix de redirection
  $choixTraitement= $_REQUEST['choixTraitement'];
  switch($choixTraitement) {
    //cas ou l'utilisateur a été redirigé vers la page de connexion
    case 'connexion':{
      include("./controleurs/c_connexion.php");
      break;
    }
    //cas ou l'utilisateur s'est connecté en admin
    case 'admin': {
      include("./controleurs/c_admin.php");
      break;
    }
    //cas ou l'utilisteur s'est connecté en lecteur
    case 'page': {
      include './vues/v_entete.php';
      echo "lien vers pages";
      break;
    }
    //cas utilisé dans l'affichage des information des utilisateurs, ici l'affichage de l'historique de connexion
    case 'connexions_utilisateurs':{
      include("./controleurs/c_historiqueConnexion.php");
      break;
    }
    //cas ou l'utilisateur se deconnecte
    case 'deconnexion':{
      $pdo->statTemps();
      deconnecter();
      header('location: index.php?choixTraitement=connexion');
      break;
    }
    //page utilisée pour le débugage
    case 'debug': {
      include("./controleurs/c_debug.php");
      break;
    }
    //cas utilisé si aucun des cas précédents n'a été employé
    default:{
      $pdo->statTemps($_SESSION['heureConnexion']);
      deconnecter();
      header('location: index.php?choixTraitement=connexion');
      break;
    }
  }
  include './vues/v_footer.php';
 ?>
