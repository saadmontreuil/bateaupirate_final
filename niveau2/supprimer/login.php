<?php
    session_start() ; // on démarre une session qui servira en cas de login réussit
    include_once "../connexion/connect.php" ;
    include_once "../connexion/tools.php" ;

    $error_message = null ; // cette variable servira à la fois à savoir si on a trouvé une erreur et à définir un message d'erreur

    // dans le post on a à la fois le username et le password
    if (isset($_POST["username"]) && isset($_POST["pwd"]))
    {

        $ip = get_client_IP() ; // on récupère l'IP pour l'enregistrer dans la connexion (voir dans tools.php)
        $date = date("Y-m-d H:i:s");
        $email = htmlspecialchars($_POST["username"]) ;
        $pwd = htmlspecialchars($_POST["pwd"]) ;
        $success = 0 ; // on enregistrera si la connexion a réussi ou échoué

        // requete pour savoir si l'utilisateur est banni (on a fait une table Ban exprès pour ça)
        // on checke l'IP pour savoir si l'ip est bannie, la date de fin de ban doit être > à la date actuelle
        $is_banned = $database->count(DATABASE_TABLE_BANS, "*" , [DATABASE_TABLE_BANS_IP => $ip, DATABASE_TABLE_BANS_BANNED_TILL."[>]" => date("Y-m-d H:i:s")]) > 0;
        if ($is_banned)
        {
            $error_message = "Votre adresse IP est momentanément bloquée" ;
        }
        else{ // si l'ip n'est pas bannie on continue le traitement
            // requete pour savoir si l'email existe dans la DB
            $user_db = $database->get(DATABASE_TABLE_UTILISATEURS, "*", [DATABASE_TABLE_UTILISATEURS_EMAIL => $email ]) ;
            if (empty($user_db))
            {
                $error_message = "Utilisateur inexistant" ; // l'utilisateur n'as pas été trouvé, le login échoue ici
            }
            else{ // l'utilisateur existe, on continue

                // récupération du hash pour vérification
                $hash = $user_db[DATABASE_TABLE_UTILISATEURS_PASSWORD];
                if (password_verify($pwd, $hash)) // on check si le hash match avec le password saisi
                {
                    // ça matche !
                    $success = 1 ;
                    // MAJ de l'utilisateur, on modifie sa date de "last_login" (ça ne sert pas dans le cadre de l'exercice)
                    $database->update(DATABASE_TABLE_UTILISATEURS,
                        [
                            DATABASE_TABLE_UTILISATEURS_LAST_LOGIN => $date,
                        ], [DATABASE_TABLE_UTILISATEURS_EMAIL => $email]);
                    // on met l'email en session
                    $_SESSION["email"] = $email ;
                    // et on redirige vers la page home.php
                    header('Location: ../index.php');
                }
                else{ // le hash ne matche pas = mauvais password saisi
                    $error_message = "Mot de passe incorrect" ;
                }

            }

            // que le login est réussi ou non, on enregistre la tentative de connexion en base de données
            $database->insert(DATABASE_TABLE_CONNEXIONS,
                [
                    DATABASE_TABLE_CONNEXIONS_IP => $ip,
                    DATABASE_TABLE_CONNEXIONS_DATE => $date,
                    DATABASE_TABLE_CONNEXIONS_LOGIN => $email,
                    DATABASE_TABLE_CONNEXIONS_SUCCESS => $success,
                ]);

            // On effectue à présent la vérification pour savoir si il faut bannir l'IP ou pas
            $date_range = date("Y-m-d H:i:s", strtotime("-15 min")); // on va checker sur les 15 dernières minutes uniquement
            // on récupère les connexions des 15 dernières minutes pour l'ip de l'utilisateur
            $last_connexions = $database->select(DATABASE_TABLE_CONNEXIONS, [DATABASE_TABLE_CONNEXIONS_SUCCESS] , [DATABASE_TABLE_CONNEXIONS_IP => $ip,
                DATABASE_TABLE_CONNEXIONS_DATE."[>]" => $date_range]) ;

            $fails = 0 ; // on initialise les "échecs successifs" à 0, on bannira si on arrive à 5 échecs successifs
            foreach ($last_connexions as $connexion) // on parcourt les connexions
            {
                if ($connexion[DATABASE_TABLE_CONNEXIONS_SUCCESS] == 1)
                {
                    // si la connexion avait réussi, on remet à 0 le compteur d'échecs successifs
                    $fails = 0 ;
                }
                else{
                    // si la connexion avait échoué, on incrémente le compteur
                    $fails ++ ;
                }
                // si à un moment donné on arrive à 5, ça signifie qu'il y a eu 5 échecs de connexions successifs, donc on ban
                if ($fails >= 5)
                {
                    // on insère en DB le nouveau ban pour l'ip de l'utilisateur, et ce ban va durer 15 minutes à compter de maintenant
                    $database->insert(DATABASE_TABLE_BANS,
                        [
                            DATABASE_TABLE_BANS_BANNED_TILL => date("Y-m-d H:i:s", strtotime("+15 min")),
                            DATABASE_TABLE_BANS_IP => $ip
                        ], [DATABASE_TABLE_BANS_IP => $ip]);
                    break ; // on quitte la boucle, pas besoin d'aller plus loin
                }
            }
        }
    }

?>


<?php include_once ("templates/header.php") ; ?>
        <div class="container">
            <div class="col-12">
                <form action="login.php" method="post">
                    <div class="imgcontainer">
                        <img src="img/img_avatar2.png" alt="Avatar" class="avatar">
                    </div>

                    <div class="container">
                        <label for="username"><b>Email</b></label>
                        <input type="email" placeholder="Entrez votre email" name="username" required>

                        <label for="pwd"><b>Mot de passe</b></label>
                        <input type="password" placeholder="Entrez votre mot de passe" name="pwd" required>

                    <?php if ($error_message != null ) { ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                    <?php } // le message d'erreur ne s'affichera que si $error_message a été renseigné durant le traitement ?>

                        <button type="submit">Connexion</button>
                    </div>

                    <div>
                        <span class="psw"><a href="resetpassword.php">Mot de passe oublié ?</a></span>
                        <span class="psw">&bull;</span>
                        <span class="psw"><a href="signup.php">Création de compte</a></span>
                    </div>
                </form>
            </div>
        </div>
<?php include_once ("templates/footer.php") ; ?>
