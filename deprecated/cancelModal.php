
<div id="dialog-cancel" title="&iquest;Cancelar cita?" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>&iquest;Quires cancelar la cita?</p>
</div>
<button id="button-cancel" style="display:none;" type="submit" name="<?php echo Dispatcher::OPTION_PARAM ?>" value="<?php echo Dispatcher::CANCEL_CONFIRM_LABEL ?>">Cancelar cita</button>
  
<script>
  function showCancelModal(){
	  $( "#dialog-cancel" ).dialog({
		    resizable: false,
		    height: "auto",
		    width: 400,
		    modal: true,
		    buttons:[
				    {
				      text: "Cancelar cita",
				      click: function() {
				    	$("#button-cancel").click();
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
  