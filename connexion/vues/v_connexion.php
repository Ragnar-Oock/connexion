<?php
//si il y a une erreur dans $_SESSION
if (isset($_SESSION['connexionErreur'])) {
  //on entre dans un switch pour afficher differentent erreurs
  switch ($_SESSION['connexionErreur']) {
    //mauvais mot de passe
    case 'mdp':{
      echo('<div id="boiteConnexion">
        <div class="erreur"><p>Login ou mot de passe incorrect</p></div>
        <div id="contenuConnection">
          <form name="frmIdentification" method="POST" action="index.php?choixTraitement=connexion&action=valideConnexion">
              <p>
               <input id="login" type="text" name="login" maxlength="45" placeholder="Nom d\'utilisateur" required>
              </p>
              <p>
                <input id="mdp" type="password" name="mdp" maxlength="45" placeholder="Mot de Passe" required>
              </p>
              <p>
                <input type="submit" 	name="valider"		value="connexion"> <input type="reset" 	name="annuler" 		value="Annuler">
              </p>
          </form>
        </div>
        <div id="bandeauConnexion">
          <p>
            En vous connectant sur ce site, vous acceptez que nous retenions votre pays ainsi qu\'un historique de vos connexion pour des raisons de sécurité.
          </p>
        </div>
      </div>');
      break;
    }

      case 'validite':{
        //le compte n'est plus valide
        echo('<div id="boiteConnexion">
          <div class="erreur"><p>Votre compte n\'est plus valide depuis le '.$_SESSION['uActif'].'</p></div>
          <div id="contenuConnection">
            <form name="frmIdentification" method="POST" action="index.php?choixTraitement=connexion&action=valideConnexion">
                <p>
                 <input id="login" type="text" name="login" maxlength="45" placeholder="Nom d\'utilisateur" required>
                </p>
                <p>
                  <input id="mdp" type="password" name="mdp" maxlength="45" placeholder="Mot de Passe" required>
                </p>
                <p>
                  <input type="submit" 	name="valider"		value="connexion"> <input type="reset" 	name="annuler" 		value="Annuler">
                </p>
            </form>
          </div>
          <div id="bandeauConnexion">
            <p>
              En vous connectant sur ce site, vous acceptez que nous retenions votre pays ainsi qu\'un historique de vos connexion pour des raisons de sécurité.
            </p>
          </div>
        </div>');
        break;
      }

      case 'default':{
        //la case defaut du controleur c_connexion.php
        echo('<div id="boiteConnexion">
              <div class="erreur"><p>Erreur interne</p></div>
          <div id="contenuConnection">
            <form name="frmIdentification" method="POST" action="index.php?choixTraitement=connexion&action=valideConnexion">
                <p>
                 <input id="login" type="text" name="login" maxlength="45" placeholder="Nom d\'utilisateur" required>
                </p>
                <p>
                  <input id="mdp" type="password" name="mdp" maxlength="45" placeholder="Mot de Passe" required>
                </p>
                <p>
                  <input type="submit" 	name="valider"		value="connexion"> <input type="reset" 	name="annuler" 		value="Annuler">
                </p>
            </form>
          </div>
          <div id="bandeauConnexion">
            <p>
              En vous connectant sur ce site, vous acceptez que nous retenions votre pays ainsi qu\'un historique de vos connexion pour des raisons de sécurité.
            </p>
          </div>
        </div>');
        break;
      }

    default:{
      //case sans erreurs
      break;
    }
  }
}
else {
  //case sans erreurs
  echo('<div id="boiteConnexion">
    <div class="erreur visibilite"><p>Je suis invisible!</p></div>
    <div id="contenuConnection">
      <form name="frmIdentification" method="POST" action="index.php?choixTraitement=connexion&action=valideConnexion">
          <p>
           <input id="login" type="text" name="login" maxlength="45" placeholder="Nom d\'utilisateur" required>
          </p>
          <p>
            <input id="mdp" type="password" name="mdp" maxlength="45" placeholder="Mot de Passe" required>
          </p>
          <p>
            <input type="submit" 	name="valider"		value="connexion"> <input type="reset" 	name="annuler" 		value="Annuler">
          </p>
      </form>
    </div>
    <div id="bandeauConnexion">
      <p>
        En vous connectant sur ce site, vous acceptez que nous retenions votre pays ainsi qu\'un historique de vos connexion pour des raisons de sécurité.
      </p>
    </div>
  </div>');
}
?>
