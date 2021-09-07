<?php
include("../partials/connect.php");
$category=$_POST['name'];


$database->insert('categories_musique',['nom'=>$category]);

header('location: productsshow.php');

?>