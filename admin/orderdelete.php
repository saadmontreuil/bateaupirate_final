<?php
include('../connexion/connect.php');
$newid=$_GET['del_id'];

$query=$database->delete('commandes',['idCommande'=>$newid]);


	header('location: orders.php');











?>