<!DOCTYPE html>
<html>
<head></head>
<?php 
require_once(APPPATH . 'widgets/custom/library/header.php'); 
$dispatcher = $GLOBALS['dispatcher'];
$Controlador = $dispatcher->getControlador();
?>
<body>
	<div id="appointment-confirmation">
		<div class="container">
			<div class="row">
				<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-4 col-lg-offset-4 col-xs-12 col-sm-8 col-md-4 col-lg-4 text-center">
                    <form id="myForm" action="" method="post">
                        <div class="row header-image">
                            <img class="img " src="/euf/assets/others/telefonica/images/logo-movistar.png">
                        </div>
                        <?php
                            $dispatcher = $GLOBALS['dispatcher'];
                            $Controlador = $dispatcher->getControlador();
                            if($Controlador->showCancel() && !$Controlador->showTechnicanLocation()) {
                                
                                $activityID=$dispatcher->getControlador()->getActivityIdFromContext();
                                $activity=$dispatcher->getControlador()->findActivityData($activityID);
                                $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
                                $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
                            
                        ?>
                                <div class="row appointment-info">
                                    <p>
                                        <span>Tu cita para </span>
                                        <?
                                            if(strpos($activity->activityType, 'PRO')===0){
                                                echo "<span>instalación</span>";
                                            }else if(strpos($activity->activityType, 'REP')===0){
                                                echo "<span>reparación</span>";
                                            }
                                        ?>
                                    </p>
                                    <!-- <p>
                                        
                                        <span>Instalación/Reparación </span>
                                    </p> -->
                                    <p>
                                        <span>est&aacute; progamada para el </span>
                                    </p>
                                    <p>
                                        <span class="appointment-date-formatted"><?php echo $dateStart->format('d-M-Y'); ?></span>
                                        <span> </span>
                                    </p>
                                    <p>
                                        <span>entre las <?php echo $dateStart->format('H'); ?> y las <?php echo $dateEnd->format('H\h\s'); ?>.</span>
                                    </p>
                                </div>
                        <?php } ?>
                        <?php if($Controlador->showConfirm() && !$Controlador->showTechnicanLocation()) { ?>
                        <div class="row action-confirm">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                        value="<?php echo Dispatcher::CONFIRMAR_LABEL; ?>"
                                        class="btn btn-lg btn-block btn-primary">Confirmar Cita</button>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row action-modify">
                            <?php
                                $dispatcher = $GLOBALS['dispatcher'];
                                $Controlador = $dispatcher->getControlador();
                                if($Controlador->showSchedule() && !$Controlador->showTechnicanLocation()) { ?>

                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                            value="<?php echo Dispatcher::SCHEDULE_DATE_LABEL; ?>"
                                            class="btn btn-lg btn-block btn-secondary pull-left">Modificarla</button>
                                </div>

                            <?php } ?>
                            <?php
                                $dispatcher = $GLOBALS['dispatcher'];
                                $Controlador = $dispatcher->getControlador();
                                if($Controlador->showCancel() && !$Controlador->showTechnicanLocation()){
                                    if(!$Controlador->showSchedule()){
                                        echo '<style>
                                            #cancelarMenuBtn{
                                                width:100%;
                                                margin-top:20px;
                                            }
                                            #cancelarMenuDiv{
                                                width:100%;
                                                margin-top:20px;
                                            }

                                        </style>';
                                    }
                                    ?>

                                

                                <div id="cancelarMenuDiv" class="col-xs-6 col-sm-6 col-md-6 col-lg-6 cancelarBtnDiv">
                                    <input  id ="cancelarMenuBtn" type="button" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                            value="Cancelarla"
                                            class="btn btn-lg btn-block btn-secondary pull-right" 
                                            onclick='
                                                var event = new CustomEvent("cancelarMenuBtnClicked", { "detail": "Example of an event" });
                                                document.dispatchEvent(event);'>
                                    <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                            value="<?php echo Dispatcher::CANCELAR_LABEL; ?>"
                                            class="btn btn-lg btn-block btn-secondary pull-right" style="display:none;">Cancelarla</button>
                                </div>

                            <?php } ?>
                            <?php
                                $dispatcher = $GLOBALS['dispatcher'];
                                $Controlador = $dispatcher->getControlador();
                                if($Controlador->showTechnicanLocation()){
                                echo 'Puedes consultar la ubicaci&oacute;n del t&eacute;cnico aca';

                                echo '<div class="col-lg-12">';
                                echo '<button type="submit" name="' . Dispatcher::OPTION_PARAM . '" value="' . Dispatcher::UBICACION_LABEL . '"';
                                echo 'class="button btn btn-lg btn-primary" >Ver t&eacute;cnico</button>';
                                echo '</div>';
                                }
                            ?>
                        </div>
                        <div class="row separator">
                            <img id="u7_img" class="img " src="/euf/assets/others/telefonica/images/separator.png"/>
                        </div>
                        <div class="row footer-image">
                            <img id="u6_img" class="img " src="/euf/assets/others/telefonica/images/footer-movistar.png"/>
                        </div>
                    </form>
                </div>
			</div>
		</div>
	</div>
    
    
</body>
</html>