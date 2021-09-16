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

          $query=$database->select('vinyl','*',['idVinyl'=>$id])
          ?>

          <h3> Nom : <?php echo $query[0]['nomVinyl']?> </h3><hr><br>

          <h3> Prix : <?php echo $query[0]['prixHT']?> </h3><hr><br>

          <h3> Description : <?php echo $query[0]['description']?> </h3><hr><br>
          <img src="<?php echo $query[0]['photo'] ?>" alt="No File" style="height:300px; width:300px">





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
