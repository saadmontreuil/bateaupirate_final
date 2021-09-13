<?php
session_start();

if(isset($_POST['login'])){

include('../connexion/connect.php');
    include('../connexion/tools.php');



$email=$_POST['email'];
$password=$_POST['password'];
//$sql="SELECT * from customers Where username='$email' AND password='$password'";
$query = $database->select('clients','*',['email'=>$email ,'mdp'=>$password]);

//$results=$connect->query($sql);
//$final=$results->fetch_assoc();

$_SESSION['email']=$query[0]['email'];
$_SESSION['password']=$query[0]['mdp'];

$_SESSION['customerid']=$query[0]['id'];



if($email=$query[0]['email'] AND $password=$query[0]['mdp']){
  header('location: ../cart.php');
}else{
  echo "<script> alert('Credentials are wrong');
        window.location.href='../login.php';
        </script>";
}






}



?>