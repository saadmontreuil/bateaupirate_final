<?php
include_once "connexion/connect.php" ;

// de base on définit des variables avec les valeurs par défaut de chaque champ
$email = "" ;
$pwd = "" ;
$nom = "" ;
$prenom = "" ;
$is_pro = "0" ;
$rgpd = false ;

$error_message = null ; // on créé aussi une variable qui permettra de savoir si une erreur a été détectée et dans ce cas, son message d'erreur

if (count($_POST) > 0) // une façon de savoir si on a des champs dans le POST
{
    // POST = formulaire soumis, on récupère les valeurs du formulaire
    $email = htmlspecialchars($_POST["email"]) ;
    $nom = htmlspecialchars($_POST["nom"]) ;
    $prenom = htmlspecialchars($_POST["prenom"]) ;
    $pwd = htmlspecialchars($_POST["pwd"]) ;

    // Et on va vérifier chaque valeur à travers une série de tests

    // on commence par checker si le nom est bien présent, sinon erreur
    if (! isset($_POST["nom"]) || empty($_POST["nom"]))
    {
        $error_message = "Vous devez saisir un nom" ;
    }
    // il faut aussi que le prénom soit défini sinon erreur
    else if (! isset($_POST["prenom"]) || empty($_POST["prenom"]))
    {
        $error_message = "Vous devez saisir un prénom" ;
    }
    // idem pour l'email
    else if (! isset($_POST["email"]) || empty($_POST["email"]))
    {
        $error_message = "Vous devez saisir un email" ;
    }
    // on vérifie ensuite que l'email soit bien valide
    else if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error_message = "Vous devez saisir un email valide" ;
    }
    // on check ensuite si l'email n'est pas déjà utilisée par un autre utilisateur
    else if ($database->count("clients", "email", ["email" => $_POST["email"] ]))
    {
        $error_message = "Un utilisateur est déjà inscrit avec cet email" ;
    }
    // on check ensuite si les deux champs de password sont bien présents
    else if (! isset($_POST["pwd"]) || empty($_POST["pwd"]))
    {
        $error_message = "Vous devez saisir un mot de passe" ;
    }
    else if (! isset($_POST["pwd2"]) || empty($_POST["pwd2"]))
    {
        $error_message = "Vous devez retaper votre mot de passe" ;
    }

    // on vérifie ensuite que password 1 == password 2
    else if ($_POST["pwd"]  !== $_POST["pwd2"])
    {
        $error_message = "Vous devez saisir deux fois le même mot de passe" ;
    }
    // et on vérifie que le mot d epasse valide bien les contraintes de complexité
    else if (! validate_password($_POST["pwd"]))
    {
        $error_message = "Votre mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
    }
    // enfin, il faut que le'utilisateur ait checké la case pour le RGPD
    else if (! isset($_POST["rgpd"]))
    {
        $error_message = "Vous devez accepter les conditions d'utilisation du service" ;
    }

    // si on est rentré dans aucune des conditions précédentes, ça veut dire qu'aucune erreur n'a été détexté !
    else // toutes les conditions sont donc réunies, on créé le compte
    {
        // on hashe le password
        $password = password_hash($pwd, PASSWORD_BCRYPT) ;
        // et on fait l'insertion en DB
        $database->insert("clients",
            [
                "email" => $email,
                "last_login" => null,
                "nom" => $nom,
                "prenom" => $prenom,
                "mdp" => $password,
            ]);
        // et on redirige automatiquement vers la page login
        header('Location: login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
include ("partials/head.php");
?>
<body class="animsition">
	<?php
	include ("partials/header.php");


?>

	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/about1.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
		Customers
		</h2>
	</section>	


	<!-- Content page -->
	<section class="bg0 p-t-104 p-b-116">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
					<form  action="signup.php" method="post">
						<h4 class="mtext-105 cl2 txt-center p-b-30">
							Register
						</h4>

						<div class="bor8 m-b-20 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" placeholder="Entrez votre nom" name="nom" required value="<?= $nom ?>">
							<img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
						</div>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" placeholder="Entrez votre prénom" name="prenom" required value="<?= $prenom ?>">
                            <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                        </div>
                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="email" placeholder="Entrez votre email" name="email" required value="<?= $email ?>">
                            <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                        </div>

						<div class="bor8 m-b-30">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"  type="password" placeholder="Entrez votre mot de passe" name="pwd" required value="">
						</div>
						<div class="bor8 m-b-30">
							<input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="password" placeholder="Retapez votre mot de passe" name="pwd2" required value="">
						</div>
                        <?php if ($error_message != null ) { // si jamais on a rencontré une erreur dans le traitement, on l'affiche ici ?>
                            <div class="alert alert-danger"><?= $error_message ?></div>
                        <?php } ?>

						<button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer" name="submit" type="submit" value="on">
                            S'inscrire
						</button>
                        <label>
                            <input type="checkbox" name="rgpd"> Je reconnais avoir pris connaissance des conditions d’utilisation et y adhère totalement
                        </label>
					</form>
				</div>

			</div>
		</div>
	</section>	
	
	
	<!-- Map -->
	<div class="map">
		<div class="size-303" id="google_map" data-map-x="40.691446" data-map-y="-73.886787" data-pin="images/icons/pin.png" data-scrollwhell="0" data-draggable="1" data-zoom="11"></div>
	</div>



	<!-- Footer -->
	<?php
	include('partials/footer.php');
	?>

</body>
</html>
