<?php
$utilisateurs=$pdo->getUtilisateurs();

$loginPays = $pdo->getLoginPays($_GET['uId']);
include("./vues/v_statsConnexions.php");
?>
