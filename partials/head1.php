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
    $user = $database->delete("clients", ["idClient" => $_GET["delete"]]) ;
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
    if ($id == null && $database->count("clients", "email", ["email" => $_POST["email"] ]))
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

            $database->insert("clients",
                [
                    "email" => $email,
                    "last_login" => null,
                    "nom" => $nom,
                    "prenom" => $prenom,
                    "mdp" => $password,
                ]);
        }
        else{ // update de l'utilisateur
            $datas =  [
                "email" => $email,
                "nom" => $nom,
                "prenom" => $prenom,
            ] ;
            if ($pwd != null) // si le mot de passe est présent dans le formulaire alors on l'ajoute dans les champs à updater
            {
                $datas["mdp"] = password_hash($pwd, PASSWORD_BCRYPT) ;
            }
            $database->update("clients", $datas, ["idClient" => $id]) ;
        }

        // on réinitialise les variables pour vider les champs du formulaire
        $id = null ;
        $pwd = null ;
        $nom = null ;
        $prenom = null ;
        $email = null ;

    }

}

// Traitement terminé, on charge la liste de tous les users pour les afficher
$users = $database->select("clients", "*") ;

?>