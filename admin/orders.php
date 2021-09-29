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


          $query= $database->select('commandes','*');

          foreach ($query as $command){ ?>

            <a href="ordershow.php?pro_id=<?= $command['idCommande']?>">
            <h3>Numero de commande :<?= $command['idCommande'] ?>: </h3><br>
             <h3>Date :<?= $command['date_commande']?></h3><br>
            <h3>Total: <?= $command['total']?></h3><br>

          </a>


          <a href="orderdelete.php?del_id=<?= $command['idCommande'] ?>">
            <button >Supprimer</button>
          </a><hr>


         <?php }
          ?>





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
