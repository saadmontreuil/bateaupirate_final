<?php
error_reporting(E_ALL ^ E_DEPRECATED);
// On importe les classes de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



/**
$to : adresse email du destinataire (ex : "kevindu93@gmail.com")
$subject : sujet de mail (ex : "Votre lien de réinitialisation du mot de passe")
$body : corps du mail (peut contenir des balises html)
retourne true si le mail a bien été envoyé, false sinon
 **/
function send_mail($to, $subject, $body)
{

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $username = "promodevweb@gmail.com"; // on utilise un compte gmail créé pour l'occasion
    $password = 'awxohmumqfjulbsv'; // vous pourrez utiliser ce compte-ci ou paramétrer le votre si vous le souhaitez
    // dans ce cas pensez à aller dans les paramètres de sécurité de votre compte et diminuer la sécurité pour permettre l'authentification via des applications tierces

    $mail->IsSMTP();
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ),
    );
    $mail->SMTPDebug = 0; // mettez 2 pour avoir toutes les infos concernant l'envoi du mail sous la forme d'un echo
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = $username;
    $mail->Password = $password;

    $mail->SetFrom($username, $username);
    $mail->AddReplyTo($username, $username);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $username);

    return $mail->Send();
}
