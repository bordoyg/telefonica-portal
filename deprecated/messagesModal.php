<div id="dialog-msjs" title="" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><?php echo $_REQUEST[Controlador::MESSAGE_PARAM] ?></p>
</div>
<script>
var showModal= <?php echo (isset($_REQUEST[Controlador::MESSAGE_PARAM]))?'true;':'false;'?>

if(showModal){
	$( "#dialog-msjs" ).dialog({
	    resizable: false,
	    height: "auto",
	    width: 400,
	    modal: true,
	    buttons:[
		    {
		      text: "Aceptar",
		      click: function() {
		        $( this ).dialog( "close" );
		        $( this ).dialog( "destroy" );
		      }
		    }]
	  });	
}

</script>