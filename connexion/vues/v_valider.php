<?php
switch ($action) {
  case 'validerAjoutBDD':{
    echo "
    <p></p>
    <button onclick='location.href=\"index.php?choixTraitement=admin&action=voir\"'>OK</button>
    ";
    break;
  }

  case 'validerSuppressionUtilisateur':{
    if (!isset($_REQUEST['execution'])) {
      echo "
      <p>Voulez-vous vraiment supprimer cet utilisateur?</p>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=validerSuppressionUtilisateur&uId=".$_REQUEST['uId']."&execution=true\"'>Valider la suppresion</button>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=voir\"'>annuler</button>

      ";
    }
    else {
      if ($_REQUEST['execution']==true) {
        $pdo->supprimerUtilisateurs($_REQUEST['uId']);
        header('location: index.php?choixTraitement=admin&action=voir');
      }
    }
    break;
  }

  case 'validerValidationUtilisateur':{
    if (!isset($_REQUEST['execution'])) {
      echo "
      <p>Voulez-vous vraiment valider cet utilisateur pour les 2 semaines à venir?</p>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=validerValidationUtilisateur&uId=".$_REQUEST['uId']."&execution=true\"'>Valider l'utilisateur</button>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=voir\"'>annuler</button>
      ";
    }
    else {
      if ($_REQUEST['execution']==true) {
        $pdo->validerUtilisateur($_REQUEST['uId']);
        header('location: index.php?choixTraitement=admin&action=voir');
      }
    }
    break;
  }

  case 'ajoutUtilisateur':{
    $_SESSION['login'] = $_REQUEST['login'];
    $_SESSION['mdp1'] = $_REQUEST['mdp1'];
    $_SESSION['mdp2'] = $_REQUEST['mdp2'];
    $_SESSION['basesSelectionnees'] = $_REQUEST['baseDeDonnees'];
    echo "
    <div class='SC_boite'>
      <p>L'utilisateur \"".$_REQUEST['login']."\" va être créer</p>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=validerAjoutUtilisateur\"'>valider</button>
      <button onclick='location.href=\"index.php?choixTraitement=admin&action=nouveau\"'>annuler</button>
    </div>
    ";
    break;
  }

  default:{
    deconnecter();
    header ('location: index.php?choixTraitement=connexion');
    break;
  }
}
 ?>
