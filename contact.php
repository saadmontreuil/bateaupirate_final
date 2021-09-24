<!DOCTYPE html>
<html lang="en">

<?php
include ("partials/head1.php");
include ("partials/head.php");



    if(isset($_SESSION["customernom"]) && isset($_POST['message']))
    {
        $name = $_SESSION["customernom"];
        $message = $_POST['message'];

        $database->insert('chat_info',['name'=>$name,'msg'=>$message]);

    }
?>
<body onload="ajax();" class="animsition">
	<?php
	include ("partials/header.php");


?>

	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('images/about1.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
			Contact
		</h2>
	</section>	


	<!-- Content page -->
	<section class="bg0 p-t-104 p-b-116">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">

                    <div class="container" style="" >
                        <div id="chat_box">

                            <div id="chat" style="overflow-y: scroll; height: 300px">


                            </div>


                            <form id="formchat" class="form-horizontal" style="margin-top:50px;">

                                <div class="form-group">
                                    <label for="comment" class="col-sm-2 control-label">Message:</label>
                                    <div class = "col-sm-10">
                                        <textarea name = "message" class="form-control" rows="2" id="comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="submit" name = "submit" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">Envoyer</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

				<div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-map-marker"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Adresse
							</span>

							<p class="stext-115 cl6 size-213 p-t-18">
                                Lycée saint exupery saint Raphaël, Lycée saint exupery Avenue de Valescure, 83700, Saint-Raphaël
							</p>
						</div>
					</div>

					<div class="flex-w w-full p-b-42">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-phone-handset"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Parlons
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								+33 77 8324501
							</p>
						</div>
					</div>

					<div class="flex-w w-full">
						<span class="fs-18 cl5 txt-center size-211">
							<span class="lnr lnr-envelope"></span>
						</span>

						<div class="size-212 p-t-2">
							<span class="mtext-110 cl2">
								Support Vente
							</span>

							<p class="stext-115 cl1 size-213 p-t-18">
								saad@bateaupirate.com
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
	
	



	<!-- Footer -->
	<?php
	include('partials/footer.php');
	?>

</body>
<script>
    $("#formchat").submit(function(e) {
        e.preventDefault();
        data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: 'contact.php',
            data: data
        });


    });


    function ajax()
    {

        $.ajax({url: "chat.php", success: function(result)
            {
                $("#chat").html(result);

            }});

    }

    setInterval(function(){ajax();},500);
</script>
</html>