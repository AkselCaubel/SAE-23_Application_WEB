<?php
//$pdo=new PDO('mysql:host=localhost;dbname=db_PAULET;charset=UTF8','22104211','AkselAksel');
$pdo=new PDO('sqlite:auth.sqlite');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
////$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
?>
