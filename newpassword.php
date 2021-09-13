<?php
    include_once "connexion/connect.php" ;
    include_once "connexion/tools.php" ;

    $error_message = null ; // variable qui servira à la fois à savoir s'il y a eu une erreur, et son message d'erreur
    $valid_message = null ; // si l'opération a réussi, on voudra aussi afficher un message de succès
    $id = null ;
    $token = null ;

    // si l'url ne contenait pas d'id --> erreur
    if (!isset($_GET["id"]))
    {
        $error_message = "Identifiant utilisateur invalide" ;
    }
    // l'url doit aussi contenir un token, sinon erreur
    else if (!isset($_GET["token"]))
    {
        $error_message = "Token invalide" ;
    }
    else // On a bien trouvé à la fois l'id et le token dans le GET de la requête, on continue
    {
        $id = htmlspecialchars($_GET["id"]) ;
        $token = htmlspecialchars($_GET["token"]) ;
        // on récupère l'utilisateur correspondant à l'id
        $user_db = $database->get(DATABASE_TABLE_UTILISATEURS, "*", [DATABASE_TABLE_UTILISATEURS_ID => $id ]) ;
        // si jamais l'id ne correspondait à aucun utilisateur, c'est une erreur !
        if (empty($user_db))
        {
            $error_message = "Utilisateur inexistant" ;
        }
        else{ // on a l'utilisateur, on continue le traitement
            // on check maintenant si le token de la requête correspond au token en DB
            if ($token !== $user_db[DATABASE_TABLE_UTILISATEURS_LAST_TOKEN])
            {
                $error_message = "Le token ne correspond pas" ;
            }
            else // le token matche bien, on continue
            {
                // Etape suivante : vérifier que le token n'a pas expiré
                $date1 = new DateTime("now"); // c'est la date de maintenant
                $date2 = new DateTime($user_db[DATABASE_TABLE_UTILISATEURS_EXPIRATION_TOKEN]); // c'est la date d'expiration du token
                if ($date1 > $date2) // on compare les deux dates
                {
                    // si la date de maintenant est supérieur à celle du token, alors le token est expiré
                    $error_message = "Le token a expiré, vous devez refaire une demande de réinitialisation de mot de passe" ;
                }
                else{  // si on est là c'est que tout est bon ! le token est valide

                    // on check si on a bien les 2 mots de passe en POST
                    if (isset($_POST["pwd"]) && isset($_POST["pwd2"]))
                    {
                        // on les récupère
                        $pwd = htmlspecialchars($_POST["pwd"]) ;
                        $pwd2 = htmlspecialchars($_POST["pwd2"]) ;
                        if (empty($pwd) || empty($pwd2))
                        {
                            // si l'un des deux mots de passe est vide, erreur
                            $error_message = "Le mot de passe saisit ne doit pas être vide" ;
                        }
                        else if ($pwd  !== $pwd2)
                        {
                            // si les deux mots de passes sont différents, erreur
                            $error_message = "Vous devez saisir deux fois le même mot de passe" ;
                        }
                        else if (! validate_password($pwd))
                        {
                            // si le nouveau mot de passe ne satisfait pas aux contraintes de complexité, erreur
                            $error_message = "Votre mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
                        }
                        else { // si on est là, c'est que tout est bon ! On termine
                            $password = password_hash($pwd, PASSWORD_BCRYPT) ; // on hashe le password
                            // et on update l'utilisateur en mettant à jour le password et en faisant un reset du token pour le rendre à l'avenir inutilisable
                            $database->update(DATABASE_TABLE_UTILISATEURS,
                                [
                                    DATABASE_TABLE_UTILISATEURS_PASSWORD => $password,
                                    DATABASE_TABLE_UTILISATEURS_LAST_TOKEN => null ,
                                    DATABASE_TABLE_UTILISATEURS_EXPIRATION_TOKEN => null
                                ], [DATABASE_TABLE_UTILISATEURS_ID => $id]);
                            // enfin, on définit un message de succès
                            $valid_message = "Mot de passe réinitialisé, retour à la <a href='login.php'>page login</a>" ;
                        }
                    }
                }
            }
        }
    }

?>

<!---->
<?php //include_once ("templates/header.php") ; ?>
<!--        <div class="container">-->
<!--            <div class="col-12">-->
<!--                <!-- dans l'url il faudra retrouver l'id et le token en GET, et les donénes du formmulaire en POST -->-->
<!--                <form action="newpassword.php?id=--><?//= $id ?><!--&token=--><?//= $token ?><!--" method="post">-->
<!--                    <div class="container">-->
<!--                        <label for="pwd"><b>Mot de passe *</b></label>-->
<!--                        <input type="password" placeholder="Entrez votre mot de passe" name="pwd" required>-->
<!--                        <label for="pwd2"><b>Saisissez à nouveau votre mot de passe *</b></label>-->
<!--                        <input type="password" placeholder="Entrez votre mot de passe" name="pwd2" required>-->
<!--                    --><?php //if ($error_message != null ) { // en cas d'erreur, on l'affiche ?>
<!--                        <div class="alert alert-danger">--><?//= $error_message ?><!--</div>-->
<!--                    --><?php //} else if ($valid_message != null) { // si la réinitialisation a réussi on l'indique aussi à l'utilisateur ?>
<!--                        <div class="alert alert-info">--><?//= $valid_message ?><!--</div>-->
<!--                        --><?php //} ?>
<!--                        <button type="submit">Modifier</button>-->
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
