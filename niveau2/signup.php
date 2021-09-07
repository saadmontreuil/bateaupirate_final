<?php
    include_once "../connexion/connect.php" ;
    include_once "../connexion/tools.php" ;

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
        $is_pro = htmlspecialchars($_POST["type"]) ;
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
        else if ($database->count(DATABASE_TABLE_UTILISATEURS, DATABASE_TABLE_UTILISATEURS_EMAIL, [DATABASE_TABLE_UTILISATEURS_EMAIL => $_POST["email"] ]))
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
        // idem avec le type de compte - à noter que la valeur pouvant être zéro et que 0 == false == empty, on peut checker la longueur de la chaine pour éviter toute confusion
        else if (! isset($_POST["type"]) || strlen($_POST["type"]) == 0)
        {
            $error_message = "Vous devez choisir un type de compte" ;
        }

        // on vérifie ensuite que password 1 == password 2
        else if ($_POST["pwd"]  !== $_POST["pwd2"])
        {
            $error_message = "Vous devez saisir deux fois le même mot de passe" ;
        }
        // et on vérifie que le mot d epasse valide bien les contraintes de complexité (voir fonction dans tools.php)
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
            $database->insert(DATABASE_TABLE_UTILISATEURS,
                [
                    DATABASE_TABLE_UTILISATEURS_EMAIL => $email,
                    DATABASE_TABLE_UTILISATEURS_LAST_LOGIN => null,
                    DATABASE_TABLE_UTILISATEURS_NOM => $nom,
                    DATABASE_TABLE_UTILISATEURS_PRENOM => $prenom,
                    DATABASE_TABLE_UTILISATEURS_PASSWORD => $password,
                ]);
            // et on redirige automatiquement vers la page login
            header('Location: login.php');
        }
    }
?>


<?php include_once ("templates/header.php") ; ?>

        <div class="container">
            <div class="col-12">
                <form action="signup.php" method="post">
                    <div class="imgcontainer">
                        <img src="img/img_avatar2.png" alt="Avatar" class="avatar">
                    </div>
                    <div class="container">
                        <label for="nom"><b>Nom *</b></label>
                        <input type="text" placeholder="Entrez votre nom" name="nom" required value="<?= $nom ?>">

                        <label for="prenom"><b>Prénom *</b></label>
                        <input type="text" placeholder="Entrez votre prénom" name="prenom" required value="<?= $prenom ?>">

                        <label for="email"><b>Email *</b></label>
                        <input type="email" placeholder="Entrez votre email" name="email" required value="<?= $email ?>">

                        <label for="pwd"><b>Mot de passe *</b></label>
                        <input type="password" placeholder="Entrez votre mot de passe" name="pwd" required value="">

                        <label for="pwd2"><b>Mot de passe *</b></label>
                        <input type="password" placeholder="Retapez votre mot de passe" name="pwd2" required value="">

                        <label class="col-2" for="type"><b>Type de compte *</b></label>
                        <span class="col-5"><input type="radio" id="particulier" name="type" value="0" <?= $is_pro ? "" : "checked" ?>>Particulier</span>
                        <span class="col-5"><input type="radio" id="pro" name="type" value="1" <?= $is_pro ? "checked" : "" ?>>Professionnel</span>

                        <?php if ($error_message != null ) { // si jamais on a rencontré une erreur dans le traitement, on l'affiche ici ?>
                        <div class="alert alert-danger"><?= $error_message ?></div>
                        <?php } ?>
                        <button name="submit" type="submit" value="on">S'inscrire</button>
                        <label>
                            <input type="checkbox" name="rgpd"> Je reconnais avoir pris connaissance des conditions d’utilisation et y adhère totalement
                        </label>
                    </div>
                </form>
            </div>
        </div>
<?php include_once ("templates/footer.php") ; ?>
