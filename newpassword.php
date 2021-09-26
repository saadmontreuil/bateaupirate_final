<?php
    include_once "connexion/connect.php" ;


    $error_message = null ;
    $valid_message = null ;
    $token = null ;


    if (!isset($_GET["id"]))
    {
        $error_message = "Identifiant utilisateur invalide" ;
    }

    else if (!isset($_GET["token"]))
    {
        $error_message = "Token invalide" ;
    }
    else
    {
        $id = htmlspecialchars($_GET["id"]) ;
        $token = htmlspecialchars($_GET["token"]) ;
        // on récupère l'utilisateur correspondant à l'id
        $user_db = $database->get("clients", "*", ["idClient" => $id ]) ;

        if (empty($user_db))
        {
            $error_message = "Utilisateur inexistant" ;
        }
        else{

            if ($token !== $user_db["last_token"])
            {
                $error_message = "Le token ne correspond pas" ;
            }
            else
            {

                $date1 = new DateTime("now");
                $date2 = new DateTime($user_db["expiration_token"]);
                if ($date1 > $date2)
                {

                    $error_message = "Le token a expiré, vous devez refaire une demande de réinitialisation de mot de passe" ;
                }
                else{

                    // on check si on a bien les 2 mots de passe en POST
                    if (isset($_POST["pwd"]) && isset($_POST["pwd2"]))
                    {
                        // on les récupère
                        $pwd = htmlspecialchars($_POST["pwd"]) ;
                        $pwd2 = htmlspecialchars($_POST["pwd2"]) ;
                        if (empty($pwd) || empty($pwd2))
                        {

                            $error_message = "Le mot de passe saisit ne doit pas être vide" ;
                        }
                        else if ($pwd  !== $pwd2)
                        {

                            $error_message = "Vous devez saisir deux fois le même mot de passe" ;
                        }
                        else if (! validate_password($pwd))
                        {

                            $error_message = "Votre mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
                        }
                        else {
                            $password = password_hash($pwd, PASSWORD_BCRYPT) ;

                            $database->update("clients",
                                [
                                    "mdp" => $password,
                                    "last_token" => null ,
                                    "expiration_token" => null
                                ], ["idClient" => $id]);
                            // enfin, on définit un message de succès
                            $valid_message = "Mot de passe réinitialisé, retour à la <a href='login.php'>page login</a>" ;
                        }
                    }
                }
            }
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
                <form action="newpassword.php?id=<?= $id ?>&token=<?= $token ?>" method="post">
                    <h4 class="mtext-105 cl2 txt-center p-b-30">
                        Nouveau mot de passe
                    </h4>

                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input  class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="password" placeholder="Entrez votre mot de passe" name="pwd" required>
                        <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                    </div>

                    <div class="bor8 m-b-30">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"  type="password" placeholder="Entrez votre mot de passe" name="pwd2" required>
                    </div>
                    <?php if ($error_message != null ) { // en cas d'erreur, on l'affiche ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php } else if ($valid_message != null) { // si la réinitialisation a réussi on l'indique aussi à l'utilisateur ?>
                        <div class="alert alert-info"><?= $valid_message ?></div>
                    <?php } ?>

                    <button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer" type="submit">
                        Modifier
                    </button>

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
