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
  


    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-sm-9">
          <a href="products.php">
          <button style="color:green">Ajouter un nouveau</button>
        </a>
        
          <?php
          include('../connexion/connect.php');


          $query = $database->select('vinyl','*');
         foreach ($query as $vinyl){ ?>

            <a href="proshow.php?pro_id=<?php echo $vinyl['idVinyl']?>">
            <h3><?php echo $vinyl['idVinyl'] ?>: <?php echo $vinyl['nomVinyl']?></h3><br>

          </a>

          <a href="proupdate.php?up_id=<?php echo $vinyl['idVinyl'] ?>">
            <button>Mettre à jour</button>
          </a>

          <a href="prodelete.php?del_id=<?php echo $vinyl['idVinyl'] ?>">
            <button style="color:red">Supprimer</button>
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
