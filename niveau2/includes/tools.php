<?php

    // Cette fonction renvoie l'IP de l'utilisateur à l'aide de différentes techniques
    // à noter que l'ip de localhost sera ::1
    function get_client_IP()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_X_REAL_IP']) && strlen($_SERVER['HTTP_X_REAL_IP']) > 0)
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        else if (isset($_SERVER['HTTP_CLIENT_IP']) && strlen($_SERVER['HTTP_CLIENT_IP']) > 0)
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strlen($_SERVER['HTTP_X_FORWARDED_FOR']) > 0)
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']) && strlen($_SERVER['HTTP_X_FORWARDED']) > 0)
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && strlen($_SERVER['HTTP_FORWARDED_FOR']) > 0)
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']) && strlen($_SERVER['HTTP_FORWARDED']) > 0)
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']) && strlen($_SERVER['REMOTE_ADDR']) > 0)
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    // fonction qui permet de vérifier si un mot de passe valide bien les contraintes de complexité
    function validate_password($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password); // au moins une majuscule
        $lowercase = preg_match('@[a-z]@', $password); // au moins une minuscule
        $number    = preg_match('@[0-9]@', $password); // au moins un chiffre

        $is_valid = $uppercase && $lowercase && $number && strlen($password) >= 8; // tout ce qui précède + au moins 8 caractères
        return $is_valid ;
    }
?>