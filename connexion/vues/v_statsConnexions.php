<?php
include("vues/v_enteteIframe.php");
echo ("
    <body id='SC_body'>
      <div class='SC_titre'>
        <div><p class='UU_label'>Pays</p></div>
        <div><p class='UU_label'>Date<p></div>
      </div>
      <div class='SC'>");
      if ($loginPays != NULL) {
        foreach ($loginPays as $unPays) {
          echo ("
        <div><p>".$unPays['pPays']."</p></div>
        <div><p>".$unPays['cDate']."</p></div>");
        }
        echo"</div>";
      }
      else {
        echo ("
        <div class='UU'>
          Il n'y a aucun utilisateur
        </div>");
      }
?>
