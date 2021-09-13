<?php

session_start() ; // il faut démarrer la session
//echo "<h3> PHP List All Session Variables</h3>";
//foreach ($_SESSION as $key=>$val){
//
//    print_r($key);
//    print_r($val) ;
//}
//    print_r($key);
//    print_r($val) ;
//echo $_SESSION['password'];
include "connexion/connect.php" ;
include_once "connexion/tools.php" ;

$error_message = null ; // cette variable servira à savoir à la fois si une erreur a été détectée et si oui, son message
if ((isset($_GET["action"]) && $_GET["action"] === "logout") // bouton logout
    || ! isset($_SESSION["email"])
    || empty($_SESSION["email"]))
{
    // on vérifie si on a bien un email en session, sinon ça veut dire que l'utilisateur n'est pas connecté
    session_destroy();
    header('Location:login.php'); // on le renvoie au login
}

// on sait que l'utilisateur est bien connecté, du coup on commence le traitement backend

// cas simple : l'utilisateur a cliquer sur un lien delete et a confirmé la suppression, on supprime donc l'utilisateur
if (isset($_GET["delete"]) && ! empty($_GET["delete"]))
{
    $user = $database->delete(DATABASE_TABLE_UTILISATEURS, [DATABASE_TABLE_UTILISATEURS_ID => $_GET["delete"]]) ;
}

// Traitement des formulaires
// pour rappel il peut y avoir soit la création d'un nouvel utilisateur soit l'edition d'un utilisateur existants
// les 2 passent par un formulaire en modal
if (isset($_POST["submit"]))
{
    // on intialise des variables qui serviron plus tard pour le traitement
    $id = null ;
    $pwd = null ;
    $nom = null ;
    $prenom = null ;
    $email = null ;
    $is_pro = false ;

    // il n'y aura un id que pour une édition, ça nous permettra donc de savoir si on est en edition u création
    if (isset($_POST["id"]))
    {
        $id = htmlspecialchars($_POST["id"]) ;
    }

    // si on a le password qui est dans le formulaire
    if (isset($_POST["pwd"]) && !empty($_POST["pwd"]))
    {
        $pwd = htmlspecialchars($_POST["pwd"]) ;
        if (! validate_password($_POST["pwd"])) // on vérifie s'il correspond aux attentes en terme de complexité
        {
            $error_message = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
        }
    }

    // cas particulier, le password peut etre null dans le cas d'une edition, mais uniquement dans ce cas là
    // si le password est nul dans le cas d'une création c'est une erreur
    if ($id == null && $pwd == null)
    {
        $error_message = "Vous devez saisir un mot de passe lors de la création d'un compte" ;
    }

    // dans le cas création aussi, l'email ne doit pas déjà exister en base (e édition il ne faut pas faire ce test)
    if ($id == null && $database->count(DATABASE_TABLE_UTILISATEURS, DATABASE_TABLE_UTILISATEURS_EMAIL, [DATABASE_TABLE_UTILISATEURS_EMAIL => $_POST["email"] ]))
    {
        $error_message = "Un utilisateur est déjà inscrit avec cet email" ;
    }

    // en édition ou création, on doit avoir un email
    if (isset($_POST["email"]))
    {
        $email = htmlspecialchars($_POST["email"]) ;
    }
    else{
        $error_message = "Vous devez saisir un email" ;
    }

    // en édition ou création, on doit avoir un nom
    if (isset($_POST["nom"]))
    {
        $nom = htmlspecialchars($_POST["nom"]) ;
    }
    else{
        $error_message = "Vous devez saisir un nom" ;
    }

    // en édition ou création, on doit avoir un prénom
    if (isset($_POST["prenom"]))
    {
        $prenom = htmlspecialchars($_POST["prenom"]) ;
    }
    else{
        $error_message = "Vous devez saisir un prénom" ;
    }

    // en édition ou création, on doit avoir un type de compte
    if (isset($_POST["type"]) && strlen($_POST["type"]) > 0)
    {
        $is_pro = htmlspecialchars($_POST["type"]) ;
    }
    else{
        $error_message = "Vous devez choisir un type de compte" ;
    }

    // on a fini toutes les vérifications, maintenant on peut effectuer le traitement
    if ($error_message == null) // si on a décelé aucune erreur, on fera une requete en DB
    {
        if ($id == null) // insertion d'un nouvel utilisateur
        {
            // $id == null signifie qu'on est en mode création donc on fait un insert
            $password = password_hash($pwd, PASSWORD_BCRYPT) ;

            $database->insert(DATABASE_TABLE_UTILISATEURS,
                [
                    DATABASE_TABLE_UTILISATEURS_EMAIL => $email,
                    DATABASE_TABLE_UTILISATEURS_IS_PRO => $is_pro,
                    DATABASE_TABLE_UTILISATEURS_LAST_LOGIN => null,
                    DATABASE_TABLE_UTILISATEURS_NOM => $nom,
                    DATABASE_TABLE_UTILISATEURS_PRENOM => $prenom,
                    DATABASE_TABLE_UTILISATEURS_PASSWORD => $password,
                ]);
        }
        else{ // update de l'utilisateur
            $datas =  [
                DATABASE_TABLE_UTILISATEURS_EMAIL => $email,
                DATABASE_TABLE_UTILISATEURS_IS_PRO => $is_pro,
                DATABASE_TABLE_UTILISATEURS_NOM => $nom,
                DATABASE_TABLE_UTILISATEURS_PRENOM => $prenom,
            ] ;
            if ($pwd != null) // si le mot de passe est présent dans le formulaire alors on l'ajoute dans les champs à updater
            {
                $datas[DATABASE_TABLE_UTILISATEURS_PASSWORD] = password_hash($pwd, PASSWORD_BCRYPT) ;
            }
            $database->update(DATABASE_TABLE_UTILISATEURS, $datas, [DATABASE_TABLE_UTILISATEURS_ID => $id]) ;
        }

        // on réinitialise les variables pour vider les champs du formulaire
        $id = null ;
        $pwd = null ;
        $nom = null ;
        $prenom = null ;
        $email = null ;
//        $is_pro = false ;
    }

}

// Traitement terminé, on charge la liste de tous les users pour les afficher
$users = $database->select(DATABASE_TABLE_UTILISATEURS, "*") ;

?>
<!DOCTYPE html>
<html lang="en">
<?php
include ("partials/head.php");
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
					Product Overview
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						All Products
					</button>

                    <?php
                    $query= $database->select('categories_musique','*');
                    $i=1;
                    foreach ($query as $catg){

                     echo   '<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".'.$i.'">'.$catg['nom']

					.'</button>';

                        $i++;
                    }

					?>

<!--					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".2">-->
<!--						Shirts-->
<!--					</button>-->
<!---->
<!--					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".0">-->
<!--						Others-->
<!--					</button>-->

			
					
				</div>
	
				<!-- <div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div>
				</div> -->		 
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Sort By
				</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Default
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Popularity
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Average rating
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Newness
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: Low to High
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: High to Low
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Price
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										All
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$0.00 - $50.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$50.00 - $100.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$100.00 - $150.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$150.00 - $200.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$200.00+
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Color
							</div>

							<ul>
								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #222;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Black
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #4272d7;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Blue
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #b3b3b3;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Grey
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #00ad5f;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Green
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #fa4251;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Red
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #aaa;">
										<i class="zmdi zmdi-circle-o"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										White
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col4 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Tags
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Fashion
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Lifestyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Denim
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Streetstyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Crafts
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>




			<div class="row isotope-grid">
				<?php 


                $query =$database->select("vinyl", "*");

                foreach ($query as $vinyl){ ?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo $vinyl['idCategorie'] ?>">
					<!-- Block2 -->
					<div class="block2">
						<div class="block2-pic hov-img0">
							<img src="images/photos/<?php echo $vinyl['photo'] ?>" alt="IMG-PRODUCT" style="min-height: 400px; max-height: 400px">

							<a href="details.php?details_id=<?php echo $vinyl['idVinyl']?>" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 ">
								Quick View
							</a>
						</div>

						<div class="block2-txt flex-w flex-t p-t-14">
							<div class="block2-txt-child1 flex-col-l ">
								<a href="details.php?details_id=<?php echo $vinyl['idVinyl']?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
									<?php echo $vinyl['nomVinyl'] ?>
								</a>

								<span class="stext-105 cl3">
									$<?php echo $vinyl['prixHT'] ?>
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
					Load More
				</a>
			</div>
		</div>
	</section>

<?php
include ("partials/footer.php");
?>
</body>
</html>