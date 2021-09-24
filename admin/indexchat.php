<?php
include ("../connexion/connect.php");
include('adminpartials/session.php');
include('adminpartials/head.php');
?>

<!doctype html>
<html lang="en">
<?php
include ("../partials/head.php");
?>
<?php

    if(isset($_SESSION["nomAdmin"]) && isset($_POST['message']))
    {
        $name = $_SESSION["nomAdmin"];
        $message = $_POST['message'];

        $database->insert('chat_info',['name'=>$name,'msg'=>$message]);

    }
?>
<body onload="ajax();" class="animsition">
	<?php
    include('adminpartials/header.php');
    include('adminpartials/aside.php');

?>

<div class="container" style="" >
    <div id="chat_box">

        <div id="chat" style="overflow: scroll; height: 300px">


        </div>


        <form id="formchat" class="form-horizontal" style="margin-top:150px;">
            <!--                                <div class="form-group">-->
            <!--                                    <label for="inputEmail3" class="col-sm-2 control-label">Nom</label>-->
            <!--                                    <div class="col-sm-10">-->
            <!--                                        <input type="text" class="form-control" id="" placeholder="Name" name="username">-->
            <!--                                    </div>-->
            <!--                                </div>-->
            <div class="form-group">
                <label for="comment" class="col-sm-2 control-label">Message:</label>
                <div class = "col-sm-10">
                    <textarea name = "message" class="form-control" rows="2" id="comment"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name = "submit" class="btn btn-primary">Envoyer</button>
                </div>
            </div>
        </form>

    </div>
</div>
</body>

<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/bootstrap/js/popper.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="../vendor/select2/select2.min.js"></script>
<script>
    $("#formchat").submit(function(e) {
        e.preventDefault();
        data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: 'indexchat.php',
            data: data
        });


    });


    function ajax()
    {

        $.ajax({url: "../chat.php", success: function(result)
            {
                $("#chat").html(result);

            }});

    }

    setInterval(function(){ajax();},500);
</script>

</html>