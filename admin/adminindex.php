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
         <a href="products.php">
            <button style="color: green">Ajouter des vinyles</button>
          </a>
          <hr>
        </div>
        <div class="col-sm-9">
         <a href="categories.php">
            <button style="color: green">Ajouter des catégories</button>
          </a>
          <hr>
        </div>
         <div class="col-sm-9">
         <a href="orders.php">
            <button style="color: green">Voir les commandes</button>
          </a>
          <hr>
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
