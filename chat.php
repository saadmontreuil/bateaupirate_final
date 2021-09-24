
<?php
require 'connexion/connect.php';
function formatDate($date)
{
    return date('g:i a',strtotime($date));
}
//                    $query = "SELECT * FROM chat_info ORDER BY id DESC";
$query= $database->select("chat_info","*",["ORDER"=>['id'=>'ASC']]);

//                    $query_run   = mysqli_query($con,$query);


foreach ($query as $message){?>

    <div id ="chat_data">
    </div>
    <span style="color:#451dcc;"><?php echo $message['name'].' : '; ?></span>
    <span ><?php echo $message['msg']; ?></span>
    <span ><?php echo formatDate($message['date']); ?></span>
    <!--
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
    -->

<?php } ?>