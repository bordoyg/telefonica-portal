<!DOCTYPE html>
<html>
<head></head>
<?php require_once(APPPATH . 'widgets/custom/library/header.php'); ?>
<body>
	<div id="appointment-confirmation">
		<div class="container">
			<div class="row">
				<div class="col-xs-offset-0 col-sm-offset-2 col-md-offset-4 col-lg-offset-4 col-xs-12 col-sm-8 col-md-4 col-lg-4 text-center">
                    <form action="" method="post">
                        <div class="row header-image">
                            <img class="img " src="/euf/assets/others/telefonica/images/logo-movistar.png">
                        </div>
                        <?php
                            $dispatcher = $GLOBALS['dispatcher'];
                            $Controlador = $dispatcher->getControlador();
                            if($Controlador->showConfirm()) {
                            $activityID=$_COOKIE[Controlador::ACTIVITY_PARAM];
                            $activity=$dispatcher->getControlador()->findActivityData($activityID);
                            $dateStart = new DateTime($activity->date . ' ' . $activity->serviceWindowStart);
                            $dateEnd = new DateTime($activity->date . ' ' . $activity->serviceWindowEnd);
                        ?>
                        <div class="row appointment-info">
                            <p>
                                <span>Tu cita para</span>
                            </p>
                            <p>
                                <span>Instalación/Reparación </span>
                            </p>
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
                                if($Controlador->showSchedule()) { ?>

                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                            value="<?php echo Dispatcher::SCHEDULE_DATE_LABEL; ?>"
                                            class="btn btn-lg btn-block btn-secondary pull-left">Modificarla</button>
                                </div>

                            <?php } ?>
                            <?php
                                $dispatcher = $GLOBALS['dispatcher'];
                                $Controlador = $dispatcher->getControlador();
                                if($Controlador->showCancel()){ ?>

                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <button type="submit" name="<?php echo Dispatcher::OPTION_PARAM; ?>"
                                            value="<?php echo Dispatcher::CANCELAR_LABEL; ?>"
                                            class="btn btn-lg btn-block btn-secondary pull-right">Cancelarla</button>
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