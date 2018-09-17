<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
    <title>Portail de connexion</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link href="./styles/style.css" rel="stylesheet">
    <script src="./include/proceduresJava.js"></script>
    <script>
      function menu(x,y) {
        document.getElementById(x).classList.toggle("change");
        document.getElementById(y).classList.toggle("menuHide");
      }
    </script>
  <body>
    <div id="bandeau">
      <div id="boutonMenu" onclick="menu('boutonMenu','enteteBackground')">
        <div id="menuBar1" class="change"></div>
        <div id="menuBar2" class="change"></div>
        <div id="menuBar3" class="change"></div>
      </div>
    </div>
    <div id="enteteBackground">
      <div class="menu-dropdown button">
        <button>Liens utiles</button>
        <!--<div class="menu-dropdown-content"><button>coucou</button></div>-->
      </div>
      <button onclick="location.href='index.php?choixTraitement=admin&action=voir'">Voir les utilisateurs</button>
      <button onclick="location.href='index.php?choixTraitement=admin&action=nouveau'">Ajouter un utilisateur</button>
      <button onclick="location.href='index.php?choixTraitement=admin&action=ajoutBDD'">Ajouter une base de donn&eacute;es</button>
      <button class="negatif" onclick="location.href='index.php?choixTraitement=deconnexion'">d&eacute;connexion</button>
    </div>
    <div id="viewport">
<!-- fin affichage du menu -->
