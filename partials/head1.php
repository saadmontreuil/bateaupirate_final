<?php

session_start() ;

include "connexion/connect.php" ;


$error_message = null ;
if ((isset($_GET["action"]) && $_GET["action"] === "logout")
    || ! isset($_SESSION["email"])
    || empty($_SESSION["email"]))
{

    session_destroy();
    header('Location:login.php');
}


if (isset($_GET["delete"]) && ! empty($_GET["delete"]))
{
    $user = $database->delete("clients", ["idClient" => $_GET["delete"]]) ;
}


if (isset($_POST["submit"]))
{

    $id = null ;
    $pwd = null ;
    $nom = null ;
    $prenom = null ;
    $email = null ;
    $is_pro = false ;


    if (isset($_POST["id"]))
    {
        $id = htmlspecialchars($_POST["id"]) ;
    }


    if (isset($_POST["pwd"]) && !empty($_POST["pwd"]))
    {
        $pwd = htmlspecialchars($_POST["pwd"]) ;
        if (! validate_password($_POST["pwd"]))
        {
            $error_message = "Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre, et faire 8 caractères ou plus" ;
        }
    }


    if ($id == null && $pwd == null)
    {
        $error_message = "Vous devez saisir un mot de passe lors de la création d'un compte" ;
    }

    if ($id == null && $database->count("clients", "email", ["email" => $_POST["email"] ]))
    {
        $error_message = "Un utilisateur est déjà inscrit avec cet email" ;
    }

    if (isset($_POST["email"]))
    {
        $email = htmlspecialchars($_POST["email"]) ;
    }
    else{
        $error_message = "Vous devez saisir un email" ;
    }

    if (isset($_POST["nom"]))
    {
        $nom = htmlspecialchars($_POST["nom"]) ;
    }
    else{
        $error_message = "Vous devez saisir un nom" ;
    }

    if (isset($_POST["prenom"]))
    {
        $prenom = htmlspecialchars($_POST["prenom"]) ;
    }
    else{
        $error_message = "Vous devez saisir un prénom" ;
    }


    if (isset($_POST["type"]) && strlen($_POST["type"]) > 0)
    {
        $is_pro = htmlspecialchars($_POST["type"]) ;
    }
    else{
        $error_message = "Vous devez choisir un type de compte" ;
    }

    if ($error_message == null)
    {
        if ($id == null)
        {
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
        else{
            $datas =  [
                "email" => $email,
                "nom" => $nom,
                "prenom" => $prenom,
            ] ;
            if ($pwd != null)
            {
                $datas["mdp"] = password_hash($pwd, PASSWORD_BCRYPT) ;
            }
            $database->update("clients", $datas, ["idClient" => $id]) ;
        }

        $id = null ;
        $pwd = null ;
        $nom = null ;
        $prenom = null ;
        $email = null ;

    }

}

$users = $database->select("clients", "*") ;

?>