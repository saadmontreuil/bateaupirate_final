<?php
// fonction qui permet de vérifier si un mot de passe valide bien les contraintes de complexité
function validate_password($password)
{
    $uppercase = preg_match('@[A-Z]@', $password); // au moins une majuscule
    $lowercase = preg_match('@[a-z]@', $password); // au moins une minuscule
    $number    = preg_match('@[0-9]@', $password); // au moins un chiffre

    $is_valid = $uppercase && $lowercase && $number && strlen($password) >= 8; // tout ce qui précède + au moins 8 caractères
    return $is_valid ;
}

    include_once ("Medoo.php");
    date_default_timezone_set('Europe/Paris');
    use Medoo\Medoo;

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' =>  "bateaupirate",
        'server' => "localhost",
        'username' => "root",
        'password' => "",
        "charset" => "utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

?>



