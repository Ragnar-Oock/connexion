<?php
echo ('
<!DOCTYPE html>
<html lang="fr">
  <head>
    <link href="./styles/style.css" rel="stylesheet">
    <script src="./include/proceduresJava.js"></script>
  <body onload="'.$pdo->ficheConsulte().'" onbeforeunload="'.$pdo->statTemps().'; '.deconnecter().'">
  <div id="enteteBackgroundLecteur">
    <button class="negatif" onclick="location.href=\'index.php?choixTraitement=deconnexion\'">d&eacute;connexion</button>
  </div>
  <div id="viewport">
  ');
  ?>
