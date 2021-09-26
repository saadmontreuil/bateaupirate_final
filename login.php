<?php
    session_start() ; // on démarre une session qui servira en cas de login réussit
    include_once "connexion/connect.php" ;


    $error_message = null ; // cette variable servira à la fois à savoir si on a trouvé une erreur et à définir un message d'erreur

    // dans le post on a à la fois le username et le password
    if (isset($_POST["username"]) && isset($_POST["pwd"]))
    {


        $date = date("Y-m-d H:i:s");
        $email = htmlspecialchars($_POST["username"]) ;
        $pwd = htmlspecialchars($_POST["pwd"]) ;
        $success = 0 ; // on enregistrera si la connexion a réussi ou échoué



            // requete pour savoir si l'email existe dans la DB
            $user_db = $database->get("clients", "*", ["email" => $email ]) ;
            if (empty($user_db))
            {
                $error_message = "Utilisateur inexistant" ; // l'utilisateur n'as pas été trouvé, le login échoue ici
            }
            else{ // l'utilisateur existe, on continue

                // récupération du hash pour vérification
                $hash = $user_db["mdp"];
                if (password_verify($pwd, $hash)) // on check si le hash match avec le password saisi
                {
                    // ça matche !
                    $success = 1 ;
                    // MAJ de l'utilisateur, on modifie sa date de "last_login" (ça ne sert pas dans le cadre de l'exercice)
                    $database->update("clients",
                        [
                            "last_login" => $date,
                        ], ["email" => $email]);
                    // on met l'email en session
                    $_SESSION["email"] = $email ;
                    $_SESSION["password"] = $pwd ;
                    $query=$database->select('clients','*',['email'=>$email]);

                    $_SESSION["customerid"] = $query[0]['idClient'];
                    $_SESSION["customernom"] = $query[0]['prenom'];
                    // et on redirige vers la page home.php
                    header('Location: index.php');
                }
                else{ // le hash ne matche pas = mauvais password saisi
                    $error_message = "Mot de passe incorrect" ;
                }

            }

            // que le login est réussi ou non, on enregistre la tentative de connexion en base de données
            $database->insert("connexions",
                [

                    "date" => $date,
                    "login" => $email,
                    "success" => $success,
                ]);


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
                <form action="login.php" method="post">
                    <h4 class="mtext-105 cl2 txt-center p-b-30">
                        Log in
                    </h4>

                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input  class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="email" placeholder="Entrez votre email" name="username" required>
                        <img class="how-pos4 pointer-none" src="images/icons/icon-email.png" alt="ICON">
                    </div>

                    <div class="bor8 m-b-30">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30"  type="password" placeholder="Entrez votre mot de passe" name="pwd" required>
                    </div>
                    <?php if ($error_message != null ) { ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php } // le message d'erreur ne s'affichera que si $error_message a été renseigné durant le traitement ?>

                    <button class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer" name="login" type="submit">
                        Log in
                    </button>
                    <div>
                        <span class="psw"><a href="resetpassword.php">Mot de passe oublié ?</a></span>
                        <span class="psw">&bull;</span>
                        <span class="psw"><a href="signup.php">Création de compte</a></span>
                    </div>
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
