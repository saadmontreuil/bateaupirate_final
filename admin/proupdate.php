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
        <form role="form" action="proupdatehandler.php" method="post" enctype="multipart/form-data">
          <?php
          $newid=htmlspecialchars($_GET['up_id']) ;

          include('../connexion/connect.php');


$query=$database->select('vinyl','*',['idVinyl'=>$newid])

          ?>
          <h1>Vinyles</h1>
              <div class="box-body">
                <div class="form-group">
                  <label for="name">Nom</label>
                  <input type="text" class="form-control" id="name" placeholder="Enter Vinyle Nom" value="<?php echo $query[0]['nomVinyl'] ?>" name="name">
                </div>
                <div class="form-group">
                  <label for="price">Prix</label>
                  <input type="text" class="form-control" id="price" placeholder="Prix" value="<?php echo $query[0]['prixHT'] ?>" name="price">
                </div>
                <div class="form-group">
                  <label for="picture">Photo</label>
                  <input type="file" id="picture" name="file" value="<?php echo $query[0]['photo'] ?>">
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" rows="10" placeholder="Enter Déscription" value="<?php echo $query[0]['description'] ?>" name="description"></textarea>
                </div>
                <div class="form-group">
                  <label for="category">Catégorie</label>
                  <select id="category" name="category" value="<?php echo $query[0]['idCategorie'] ?>">
                    <?php

                    $query2=$database->select('categories_musique','*');
                   foreach ($query2 as $categorie){
                    echo "<option value=".$categorie['idCategorie'].">".$categorie['nom']."</option>";
                  }
                    ?>
                  </select>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <input type="hidden" value="<?php echo $query[0]['idVinyl'] ?>" name="form_id">
                <button type="submit" class="btn btn-primary" name="update">mettre à jour</button>
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
