<?php
session_start();
include('../connexion/connect.php');

$total=$_POST['total'];

$phone=$_POST['phone'];

$address=$_POST['address'];
$customerid=$_SESSION['customerid'];
$payment=$_POST['payment'];
$database->query('set forgein_key_cheks = 0');

$query = $database->insert('commandes',[
    'idClient'=>$customerid,
    'total'=>$total,

],'ID');



$orderid = $database->id();


foreach ($_SESSION['cart'] as $key => $value) {
	$proid=$value['item_id'];
	$quantity=$value['quantity'];
    $prixHT=$value['quantity']*$value['item_price'];


    $query3 =$database->insert('articles_commande',[

        'idCommande'=>$orderid,
        'idVinyl'=>$proid,
        'quantite'=>$quantity,
        'montantHT'=>$prixHT

    ]);
}
$database->query('set forgein_key_cheks = 1');
if ($payment=="paypal") {
	$_SESSION['total']=$total;
	header('location: paypal.php');
}else{
	echo "<script> alert('ORDER IS PLACED');
		window.location.href='../index.php';
		</script>";
}
unset($_SESSION['cart']);

?>