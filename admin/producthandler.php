<?php
include('../connexion/connect.php');
$name=$_POST['name'];
$price=$_POST['price'];
$description=$_POST['description'];
$category=$_POST['category'];


$target="uploads/";
$file_path=$target.basename($_FILES['file']['name']);
$file_name=$_FILES['file']['name'];
$file_tmp=$_FILES['file']['tmp_name'];
$file_store="uploads/".$file_name;

move_uploaded_file($file_tmp, $file_store);







//


$database->insert('vinyl',[
    'nomVinyl'=>$name,
    'prixHT'=>$price,
    'photo'=>$file_path,
    'description'=>$description,
    'idCategorie'=>$category
]);

header('location: productsshow.php');
?>