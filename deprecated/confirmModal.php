
<div id="dialog-confirm" title="&iquest;Confirmar cita?" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>&iquest;Quires confirmar la cita?</p>
</div>
<button id="button-confirm" style="display:none;" type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CONFIRM_CONFIRM_LABEL ?>">Cancelar cita</button>
  
<script>
  function showConfirmModal(){
	  $( "#dialog-confirm" ).dialog({
		    resizable: false,
		    height: "auto",
		    width: 400,
		    modal: true,
		    buttons:[
				    {
				      text: "Confirmar cita",
				      click: function() {
				    	$("#button-confirm").click();
				        $( this ).dialog( "close" );
				      }
				    },
				    {
				      text: "Volver",
				      click: function() {
				        $( this ).dialog( "close" );
				        $( this ).dialog( "destroy" );
				      }
				    }]
		  });
  }
  </script>
  