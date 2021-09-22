<?php
include('../connexion/connect.php');
$name= htmlspecialchars($_POST['name']) ;
$price= htmlspecialchars($_POST['price']) ;
$description= htmlspecialchars($_POST['description']) ;
$category= htmlspecialchars($_POST['category']) ;

$target="uploads/";
$file_path=$target.basename($_FILES['file']['name']);
$file_name=$_FILES['file']['name'];
$file_tmp=$_FILES['file']['tmp_name'];
$file_vinyl="uploads/".$file_name;

move_uploaded_file($file_tmp, $file_vinyl);

$database->insert('vinyl',[
    'nomVinyl'=>$name,
    'prixHT'=>$price,
    'photo'=>$file_path,
    'description'=>$description,
    'idCategorie'=>$category
]);

header('location: productsshow.php');
?>