<?php
session_start();
if (isset($_POST['num-product']) ){
    $val = $_POST['num-product'];
}
if (isset($_SESSION['cart'])) {
	$checker=array_column($_SESSION['cart'], 'item_name');
	if(in_array($_GET['cart_name'], $checker)){
		echo "<script>alert('Le Vinyl est déjà dans le panier');
			window.location.href='product.php';
		</script>";
	}else{

	$count=count($_SESSION['cart']);
	$_SESSION['cart'][$count]=array('item_id' => $_GET['cart_id'], 'item_name'=>$_GET['cart_name'], 'item_price'=>$_GET['cart_price'] ,'quantity'=>$val);
	echo "<script>alert('Vinyle ajouté');
	window.location.href='product.php';
	</script>";
	}
} else {
	$_SESSION['cart'][0]=array('item_id'=>$_GET['cart_id'], 'item_name'=>$_GET['cart_name'], 'item_price'=>$_GET['cart_price'] ,'quantity'=>$val);
	echo "<script>alert('Vinyle ajouté');
	window.location.href='product.php';
	</script>";
}
?>