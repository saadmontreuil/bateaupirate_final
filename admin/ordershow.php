<!DOCTYPE html>
<html>
<?php
include('adminpartials/session.php');
include('adminpartials/head.php');
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php
  include('adminpartials/header.php');
  include('adminpartials/aside.php');
  

  ?>
  <!-- Left side column. contains the logo and sidebar -->
  

  <!-- Content Wrapper. Contains page content -->


    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-9">

          <?php
          include('../connexion/connect.php');

          $id= htmlspecialchars($_GET['pro_id']) ;

          $query= $database->select('commandes','*',['idCommande'=>$id])
          ?>

          <h3> Commande : <?= $query[0]['idCommande']?> </h3><hr><br>

          <h3> Total : <?= $query[0]['total']?> </h3><hr><br>

          <h3> Client : <?= $query[0]['idClient']?> </h3><hr><br>
          



        </div>
        <div class="col-sm-9">

          <?php
          


          $query2= $database->select('articles_commande','*',['idCommande'=>$id]);

          foreach ($query2 as $vinyl) {
              $query3=$database->select('vinyl','*',['idVinyl'=>$query2[0]['idVinyl']]);
          ?>

          <h3> vinyl : <?=$query3[0]['nomVinyl'].""?> <?= "quantité :". $vinyl['quantite']?> </h3>

          


<?php }?>
        </div>

      
<div class="col-sm-3">
  
  </div>
</div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 <?php
 include('adminpartials/footer.php');
 ?>
</body>
</html>
