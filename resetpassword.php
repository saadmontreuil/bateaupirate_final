<?php
    include_once "connexion/connect.php" ;

    include_once "connexion/sendemail.php" ; // fonction pour envoyer des emails (nécessite Laragon)

    $message = null ; // message qui servira à indiquer à l'utilisateur si l'email a été envoyé
    if (isset($_POST["username"])) // on check si on trouve bien le username dans le POST
    {
        $email = htmlspecialchars($_POST["username"]) ; // username == email, on récupère l'email
        // Et on essaie de récupérer l'utilisateur correspondant à cet email'
        $user = $database->get("clients", "*", ["email" => $email ]) ;
        if (! empty($user)) // si l'utilisateur a bien été retrouvé on continue
        {
            $token = bin2hex(random_bytes(32)); // on génère un token, des milliers de méthodes sont acceptables
            // on créé le lien qui sera envoyé à l'utilisateur (à adapter suivant vos serveurs)
            $link =  "http://".$_SERVER['SERVER_NAME']."/bateaupirate_final/newpassword.php?id=".$user["idClient"]."&token=".$token ;
            // corps de l'email
            $body = "Merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe : <a href='$link'>$link</a>" ;
            // envoi de l'email à l'utilisateur
            send_mail($email, "Réinitialisation du mot de passe", $body) ;

            // on met aussi à jour l'utilisateur en base de données en rajoutant le token et sa date d'expiration
            $database->update("clients",
                [
                    "last_token" => $token, // on ne retient que le dernier token généré, ainsi les précédents token ne pourront pas être utilisés
                    "expiration_token" => date("Y-m-d H:i:s", strtotime("+30 min")), // le token sera valide 30 minutes
                ], ["email" => $email]);
        }
        $message = "Un lien de réinitialisation du mot de passe a été envoyé par email si cet email correspond a un utilisateur inscrit" ;
    }
?>

<!---->
<?php //include_once ("templates/header.php") ; ?>
<!--        <div class="container">-->
<!--            <div class="col-12">-->
<!--                <form action="resetpassword.php" method="post">-->
<!---->
<!--                    <div class="container">-->
<!--                        <label for="username"><b>Email</b></label>-->
<!--                        <input type="email" placeholder="Entrez votre email" name="username" required>-->
<!--                        --><?php //if ($message != null ) { // on affiche le message pour prévenir l'utilisateur de l'envoi de l'email ?>
<!--                            <div class="alert alert-info">--><?//= $message ?><!--</div>-->
<!--                        --><?php //} ?>
<!--                        <button type="submit">Réinitialiser le mot de passe</button>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
<?php //include_once ("templates/footer.php") ; ?>

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
                <form action="resetpassword.php" method="post">
                    <h4 class="mtext-105 cl2 txt-center p-b-30">
                        réinitialiser le mot de passe
                    </h4>

                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input  class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="email" placeholder="Entrez votre email" name="username" required>
                        <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                    </div>

                    <?php if ($message != null ) { // on affiche le message pour prévenir l'utilisateur de l'envoi de l'email ?>
                        <div class="alert alert-info"><?= $message ?></div>
                    <?php } ?>

                    <button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer" type="submit">
                        Réinitialiser le mot de passe
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
