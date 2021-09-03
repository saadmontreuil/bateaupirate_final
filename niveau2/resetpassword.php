<?php
    include_once "connect.php" ;
    include_once "includes/tools.php" ;
    include_once "includes/sendemail.php" ; // fonction pour envoyer des emails (nécessite Laragon)

    $message = null ; // message qui servira à indiquer à l'utilisateur si l'email a été envoyé
    if (isset($_POST["username"])) // on check si on trouve bien le username dans le POST
    {
        $email = htmlspecialchars($_POST["username"]) ; // username == email, on récupère l'email
        // Et on essaie de récupérer l'utilisateur correspondant à cet email'
        $user = $database->get(DATABASE_TABLE_UTILISATEURS, "*", [DATABASE_TABLE_UTILISATEURS_EMAIL => $email ]) ;
        if (! empty($user)) // si l'utilisateur a bien été retrouvé on continue
        {
            $token = bin2hex(random_bytes(32)); // on génère un token, des milliers de méthodes sont acceptables
            // on créé le lien qui sera envoyé à l'utilisateur (à adapter suivant vos serveurs)
            $link =  "http://".$_SERVER['SERVER_NAME']."/niveau2/newpassword.php?id=".$user[DATABASE_TABLE_UTILISATEURS_ID]."&token=".$token ;
            // corps de l'email
            $body = "Merci de cliquer sur le lien suivant pour réinitialiser votre mot de passe : <a href='$link'>$link</a>" ;
            // envoi de l'email à l'utilisateur
            send_mail($email, "Réinitialisation du mot de passe", $body) ;

            // on met aussi à jour l'utilisateur en base de données en rajoutant le token et sa date d'expiration
            $database->update(DATABASE_TABLE_UTILISATEURS,
                [
                    DATABASE_TABLE_UTILISATEURS_LAST_TOKEN => $token, // on ne retient que le dernier token généré, ainsi les précédents token ne pourront pas être utilisés
                    DATABASE_TABLE_UTILISATEURS_EXPIRATION_TOKEN => date("Y-m-d H:i:s", strtotime("+30 min")), // le token sera valide 30 minutes
                ], [DATABASE_TABLE_UTILISATEURS_EMAIL => $email]);
        }
        $message = "Un lien de réinitialisation du mot de passe a été envoyé par email si cet email correspond a un utilisateur inscrit" ;
    }
?>


<?php include_once ("templates/header.php") ; ?>
        <div class="container">
            <div class="col-12">
                <form action="resetpassword.php" method="post">

                    <div class="container">
                        <label for="username"><b>Email</b></label>
                        <input type="email" placeholder="Entrez votre email" name="username" required>
                        <?php if ($message != null ) { // on affiche le message pour prévenir l'utilisateur de l'envoi de l'email ?>
                            <div class="alert alert-info"><?= $message ?></div>
                        <?php } ?>
                        <button type="submit">Réinitialiser le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
<?php include_once ("templates/footer.php") ; ?>
