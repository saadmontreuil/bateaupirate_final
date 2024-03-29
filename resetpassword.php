<?php
    include_once "connexion/connect.php" ;

    include_once "connexion/sendemail.php" ;

    $message = null ;
    if (isset($_POST["username"]))
    {
        $email = htmlspecialchars($_POST["username"]) ;

        $user = $database->get("clients", "*", ["email" => $email ]) ;
        if (! empty($user))
        {
            $token = bin2hex(random_bytes(32));
            $link =  "http://".$_SERVER['SERVER_NAME']."/bateaupirate_final/newpassword.php?id=".$user["idClient"]."&token=".$token ;
            $body = "Merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe : <a href='$link'>$link</a>" ;

            send_mail($email, "Réinitialisation du mot de passe", $body) ;
            $database->update("clients",
                [
                    "last_token" => $token,
                    "expiration_token" => date("Y-m-d H:i:s", strtotime("+20 min")),
                ], ["email" => $email]);
        }
        $message = "Un lien de réinitialisation du mot de passe a été envoyé par email si cet email correspond a un utilisateur inscrit" ;
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
                <form action="resetpassword.php" method="post">
                    <h4 class="mtext-105 cl2 txt-center p-b-30">
                        réinitialiser le mot de passe
                    </h4>

                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input  class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="email" placeholder="Entrez votre email" name="username" required>
                        <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                    </div>

                    <?php if ($message != null ) {  ?>
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
