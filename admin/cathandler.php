<?php
include("../partials/connect.php");
$category= htmlspecialchars($_POST['name']) ;


$database->insert('categories_musique',['nom'=>$category]);

header('location: productsshow.php');

?>