<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<link href="/euf/assets/others/telefonica/css/tecnico/jquery-ui-themes.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/axure_rp_page.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/styles.css" type="text/css" rel="stylesheet"/>
    <link href="/euf/assets/others/telefonica/css/tecnico/styles2.css" type="text/css" rel="stylesheet"/>
   
   
   	<div id="appointment-confirmation">
		<div class="container">
			<div class="row">
				<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-4 col-lg-offset-4 col-xs-12 col-sm-8 col-md-4 col-lg-4">
					
						<div class="row header-image text-center">
                            <img class="img " src="/euf/assets/others/telefonica/images/logo-movistar.png">
                        </div>
                        <div class="row appointment-info text-center">
                            <p><span>Tu técnico esta en camino</span></p>
                    	</div>
                    	<div class="row separator">
	                        <img id="u7_img" class="img " src="/euf/assets/others/telefonica/images/separator-large.png"/>
	                    </div>
                        <div class="row text-center">
                        	<div id="u288_img" style="margin-left:15px; float:left;">
                        	<?php 
                            if(isset($_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData)){
                                $mediaType=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->mediaType;
                                $imageData=$_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->avatar->imageData;
                                echo '<img id="u288_img" class="img " src="data: ' . $mediaType . ';base64,' . $imageData . '" />';
                            }else{
                                echo '<img id="u288_img" class="img" src="/euf/assets/others/telefonica/images/no_photo.jpg" ></img>';
                            }
                            ?>
                            </div>
                            <div style="margin-top:20px; float:left;">
                            <p><span style="color:#00CC00;">Técnico:</span><span style="color:#666666;"><?php echo $_REQUEST[Controlador::LOCATION_TECHNICAN]->resourceDetails->name; ?></span></p>
                        	</div>
                        </div>
    
                        <div class="row" style="margin-top:8px;">
	                        <img id="u7_img" class="img " src="/euf/assets/others/telefonica/images/separator-large.png"/>
	                    </div>
	                    
	                    
	                    <div class="row" style="margin-top:8px;">
                            <?php require_once(APPPATH . 'widgets/custom/library/map.php'); ?>
	                    </div>
	                    
						<div class="row appointment-thanks-msg">
					        <div class="row action-back">
	                            <div class="col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6">
	                            <form action="" method="post">
	                                <button type="button" " 
	                                        class="btn btn-lg btn-block btn-primary">Volver</button>
	                            </form>
	                            </div>
                        	</div>
            			</div>
                        <div class="row separator">
                            <img id="u7_img" class="img " src="/euf/assets/others/telefonica/images/separator-large.png"/>
                        </div>
                        <div class="row footer-image text-center">
                            <img id="u6_img" class="img " src="/euf/assets/others/telefonica/images/footer-movistar.png"/>
                        </div>
					
				</div>
			</div>
		</div>
	</div>
</body>
</html>
