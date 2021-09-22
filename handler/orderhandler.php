<?php
session_start();
include('../connexion/connect.php');

$total=$_POST['total'];

$phone=$_POST['phone'];

$address=$_POST['address'];
$customerid=$_SESSION['customerid'];
$payment=$_POST['payment'];
$database->query('set forgein_key_cheks = 0');
//$sql="INSERT INTO orders(customer_id, address, phone, total, pay_method) VAlUES('$customerid','$address','$phone','$total', '$payment')";
//$connect->query($sql);
$query = $database->insert('commandes',[
    'idClient'=>$customerid,
    'total'=>$total,

]);


//$sql2="Select id from orders order by id DESC limit 1";
//$result=$connect->query($sql2);
//$final=$result->fetch_assoc();
//$orderid=$final['id'];

$query2 = $database->select('commandes',"idCommande",['ORDER'=>[
    "idCommande"=>'DESC',
    'LIMIT' => 1

]
]);
$orderid = $query2;


foreach ($_SESSION['cart'] as $key => $value) {
	$proid=$value['item_id'];
	$quantity=$value['quantity'];


//	$sql3="INSERT Into order_details(order_id,product_id,quantity) VAlUES('$orderid','$proid','$quantity')";
//	$connect->query($sql3);
	# code...
    $query3 =$database->insert('articles_commande',[

        'idCommande'=>$orderid,
        'idVinyl'=>$proid,
        'quantite'=>$quantity

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