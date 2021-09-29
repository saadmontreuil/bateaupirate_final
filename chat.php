
<?php
require 'connexion/connect.php';
function formatDate($date)
{
    return date('g:i a',strtotime($date));
}

$query= $database->select("chat_info","*",["ORDER"=>['id'=>'ASC']]);



foreach ($query as $message){?>

    <div id ="chat_data">
    </div>
    <span style="color:#451dcc;"><?= $message['name'].' : '; ?></span>
    <span ><?= $message['msg']; ?></span>
    <span ><?= formatDate($message['date']); ?></span>

<?php } ?>