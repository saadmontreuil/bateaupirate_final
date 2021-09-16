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
        <div class="col-sm-3">
        </div>

        <div class="col-sm-6">
        <form role="form" action="producthandler.php" method="post" enctype="multipart/form-data">
          <h1>Vinyles</h1>
              <div class="box-body">
                <div class="form-group">
                  <label for="name">Nom</label>
                  <input type="text" class="form-control" id="name" placeholder="Enter Vinyle Nom" name="name">
                </div>
                <div class="form-group">
                  <label for="price">Prix</label>
                  <input type="text" class="form-control" id="price" placeholder="Prix" name="price">
                </div>
                <div class="form-group">
                  <label for="picture">Photo</label>
                  <input type="file" id="photo" name="file">
                </div>
                <div class="form-group">
                  <label for="description">Déscription</label>
                  <textarea id="description" class="form-control" rows="10" placeholder="Enter Description" name="description"></textarea>
                </div>
                <div class="form-group">
                  <label for="category">Catégorie</label>
                  <select id="category" name="category">
                    <?php
                    include('../connexion/connect.php');

                    $query = $database->select('categories_musique','*');

                   foreach ($query as $cat){
                    echo "<option value=".$cat['idCategorie'].">".$cat['nom']."</option>";
                  }
                    ?>
                  </select>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Ajouter</button>
              </div>
            </form>
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
