
<!DOCTYPE html>
<html lang="en">
<?php
include ("partials/head.php");
include ("partials/head1.php");
?>
<body class="animsition">
	<?php
	include ("partials/header.php");
	include ("partials/slider.php");

include ("partials/banner.php");


?>

		


	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5">
                    Les Catégories
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                        Tous les vinyles
					</button>

                    <?php
                    $query= $database->select('categories_musique','*');

                    foreach ($query as $catg){

                     echo   '<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".'.$catg['idCategorie'].'">'.$catg['nom']

					.'</button>';


                    }

					?>



				</div>

			</div>




			<div class="row isotope-grid">
				<?php 


                $query =$database->select("vinyl", "*");

                foreach ($query as $vinyl){ ?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?= $vinyl['idCategorie'] ?>">
					<!-- Block2 -->
					<div class="block2">
						<div class="block2-pic hov-img0">
							<img src="admin/<?= $vinyl['photo'] ?>" alt="IMG-PRODUCT" style="min-height: 400px; max-height: 400px">

							<a href="details.php?details_id=<?= $vinyl['idVinyl']?>" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 ">
                                Détails
							</a>
						</div>

						<div class="block2-txt flex-w flex-t p-t-14">
							<div class="block2-txt-child1 flex-col-l ">
								<a href="details.php?details_id=<?= $vinyl['idVinyl']?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
									<?= $vinyl['nomVinyl'] ?>
								</a>

								<span class="stext-105 cl3">
									$<?= $vinyl['prixHT'] ?>
								</span>
							</div>
							<div class="block2-txt-child2 flex-r p-t-3">
								<a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
									<img class="icon-heart1 dis-block trans-04" src="images/icons/icon-heart-01.png" alt="ICON">
									<img class="icon-heart2 dis-block trans-04 ab-t-l" src="images/icons/icon-heart-02.png" alt="ICON">
								</a>
							</div>
						</div>
					</div>
				</div>
<?php } ?>
				
				
			</div>

			<!-- Load more -->
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="product.php" class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                    Charger plus
				</a>
			</div>
		</div>
	</section>

<?php
include ("partials/footer.php");
?>
</body>
</html>