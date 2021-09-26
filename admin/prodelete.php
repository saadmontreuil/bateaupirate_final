<?php
include('../connexion/connect.php');
$newid= htmlspecialchars($_GET['del_id']) ;
$database->delete('vinyl',['idVinyl'=>$newid]);

	header('location: productsshow.php');

?>