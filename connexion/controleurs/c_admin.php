<?php
  include './vues/v_entete_admin.php';
  $action = $_REQUEST['action'];
  if($_SESSION['gId']!=0) {
    $action = NULL;
  }

  switch ($action) {
    case 'nouveau':{
      $grades = $pdo->getGrades();
      $_SESSION['bases'] = $pdo->getBases();
      include("./vues/v_admin.php");
      break;
    }

    case 'voir':{
      $utilisateurs=$pdo->getUtilisateurs();
      include("./vues/v_admin.php");
      break;
    }

    case 'ajoutUtilisateur':{
      include './vues/v_valider.php';
      break;
    }

    case 'validerAjoutUtilisateur':{
      $reponse = $pdo->ajouterUtilisateur($_SESSION['login'], $_SESSION['mdp1'], $_SESSION['listeBases']);
      echo $reponse;
      unset($_SESSION['login'], $_SESSION['mdp1'], $_SESSION['mdp2'], $_SESSION['listeBases']);
      break;
    }

    case 'ajoutBDD':{
      include("./vues/v_admin.php");
      break;
    }

    case 'validerAjoutBDD':{
      $nomBDD = $_REQUEST['nomBDD'];
      $pdo->ajouterBDD($nomBDD);
      break;
    }

    case 'validerSuppressionUtilisateur':{
      include './vues/v_valider.php';
      break;
    }

    case 'validerValidationUtilisateur':{
      include './vues/v_valider.php';
      break;
    }

    default:{
      deconnecter();
      header ('location: index.php?choixTraitement=connexion');
      break;
    }
  }
 ?>
