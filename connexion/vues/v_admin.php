<?php
  switch ($_REQUEST['action']) {
    case 'nouveau': {
      echo "
      <form name='creationMDP' id='creationMDP' action='index.php?choixTraitement=admin&action=ajoutUtilisateur' method='post'>
        <div class='center_div'>
          <div class='CU_utilisateur'>
            <fieldset class='CU_fieldset'><legend>Créer un nouvel utilisateur</legend>
              <p><input id='login' class='login' minlength='5' maxlength='16' type='text' name='login' placeholder='Entrer un identifiant' autocomplete='off'></p>
              <p><input id='mdp1' class='mdp' minlength='8' maxlength='45' type='password' name='mdp1' placeholder='Entrer un mot de passe' required autocomplete='off'></p>
              <p><input id='mdp2' class='mdp' minlength='8' maxlength='45' type='password' name='mdp2' placeholder='Verifier le mot de passe' required autocomplete='off'></p>
              <p class='bouton'><input type='button' onclick='verificationFormulaire()' value='Cr&eacute;er'> <input type='reset' value='Annuler'></p>
            </fieldset>
          </div>
      </form>";
      break;
    }

    case 'voir':{
      if (count($utilisateurs)==0) {
        echo "
        <div class='UU_aucun'>
          <p>
            Aucun utilisateur trouvé.
          </p>
          <p>
            <button onclick=\"location.href='index.php?choixTraitement=admin&action=nouveau'\">Ajouter un utilisateur</button>
          </p>
        </div>";
      }
      else {
        foreach ($utilisateurs as $unUtilisateur) {
          echo("
          <div class='UU'>
            <div class='UU_login'><p><span class='UU_label'>Identifiant : </span>".$unUtilisateur['uLogin']."</p></div>
            <div class='UU_grade'><p><span class='UU_label'>Grade : </span>".$unUtilisateur['gLabel']."</p></div>
            <div class='UU_NbCo'><p><span class='UU_label'>Nombre de connexions : </span>".$unUtilisateur['uNbCo']."</p></div>
            <div class='UU_FicheConsultee'><p><span class='UU_label'>Nombre de fiches consult&eacute;e(s) : </span>".$unUtilisateur['uFicheConsulte']."</p></div>
            <div class='UU_TempsCo'><p><span class='UU_label'>Temps de connexion : </span>".timeVersDuree($unUtilisateur['uTempsCo'])."</p></div>
            <div class='UU_DateActiv'><p><span class='UU_label'>Date de derni&egrave;re validation : </span>".$unUtilisateur['date']."</p></div>
            <div class='UU_DateJusqua'><p><span class='UU_label'>Valide jusqu'au : </span>".$unUtilisateur['valide']."</p></div>
            <div class='UU_Etat'><p><span class='UU_label'>&Eacute;tat : </span>".$unUtilisateur['etat']."</p></div>
            <div class='UU_bouton'>
              <button class='negatif' onclick='location.href=\"index.php?choixTraitement=admin&action=validerSuppressionUtilisateur&uId=".$unUtilisateur['uId']."\"'>supprimer</button>
              <button onclick='location.href=\"index.php?choixTraitement=admin&action=validerValidationUtilisateur&uId=".$unUtilisateur['uId']."\"'>valider</button>
              <button onclick='toggleDetailUtilisateur(\"uu_detail".$unUtilisateur['uLogin']."\", \"UU_details_show\")'>détails connexions</button>
            </div>
            <div class='UU_details UU_details_hidden' id='uu_detail".$unUtilisateur['uLogin']."'>
              <iframe class='UU_iframe'  width='100%' height='100%' src='index.php?choixTraitement=connexions_utilisateurs&uId=".$unUtilisateur['uId']."'></iframe>
            </div>
          </div>
          ");
        }
      }
      break;
    }


    case 'ajoutBDD': {
      echo ("
      <div class='center_div'>
        <form name='creationMDP' action='index.php?choixTraitement=admin&action=validerAjoutBDD' method='post'>
          <fieldset class='CU_fieldset'><legend>Créer une nouvelle base de donnée</legend>
            <p><input type='text' minlength='3' maxlength='32' name='nomBDD' placeholder='Nom de la base donnée' required autocomplete='off'></p>
            <p class='bouton'><input type='submit' value='Cr&eacute;er'> <input type='reset' value='Annuler'></p>
          </fieldset>
      ");
      break;
    }

    default: {
      include("vues/v_connexion.php");
      break;
    }
  }
?>
