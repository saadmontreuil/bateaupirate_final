<?php

    include_once ("Medoo.php");
    date_default_timezone_set('Europe/Paris');
    // Using Medoo namespace
    use Medoo\Medoo;

    // on définit des constantes, comme ça il suffit de modifier une seule fois un changement
    // de nom en DB pour que tout le code continue de fonctionner
//    const DATABASE_NAME = "bateaupirate" ;
if (!defined(DATABASE_NAME)) define(DATABASE_NAME, "bateaupirate" );
    const DATABASE_HOST = "localhost" ;
    const DATABASE_USER = "root" ;
    const DATABASE_PASSWORD = "" ;

    const DATABASE_TABLE_CONNEXIONS = "connexions" ;
    const DATABASE_TABLE_CONNEXIONS_ID = "id" ;
    const DATABASE_TABLE_CONNEXIONS_DATE = "date" ;
    const DATABASE_TABLE_CONNEXIONS_IP = "ip" ;
    const DATABASE_TABLE_CONNEXIONS_LOGIN = "login" ;
    const DATABASE_TABLE_CONNEXIONS_SUCCESS = "success" ;

    const DATABASE_TABLE_UTILISATEURS = "clients" ;
    const DATABASE_TABLE_UTILISATEURS_ID = "idClient" ;
    const DATABASE_TABLE_UTILISATEURS_LAST_LOGIN = "last_login" ;
    const DATABASE_TABLE_UTILISATEURS_NOM = "nom" ;
    const DATABASE_TABLE_UTILISATEURS_PRENOM = "prenom" ;
    const DATABASE_TABLE_UTILISATEURS_EMAIL = "email" ;
    const DATABASE_TABLE_UTILISATEURS_PASSWORD = "mdp" ;
//    const DATABASE_TABLE_UTILISATEURS_IS_PRO = "is_pro" ;
    const DATABASE_TABLE_UTILISATEURS_LAST_TOKEN = "last_token" ;
    const DATABASE_TABLE_UTILISATEURS_EXPIRATION_TOKEN = "expiration_token" ;

    const DATABASE_TABLE_BANS = "bans" ;
    const DATABASE_TABLE_BANS_IP = "ip" ;
    const DATABASE_TABLE_BANS_BANNED_TILL = "banned_till" ;

    // création de la connexion à la DB par Medoo
    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => DATABASE_NAME,
        'server' => DATABASE_HOST,
        'username' => DATABASE_USER,
        'password' => DATABASE_PASSWORD,
        "charset" => "utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

//$connect=mysqli_connect($host,$user,$password,$dbname);
?>



