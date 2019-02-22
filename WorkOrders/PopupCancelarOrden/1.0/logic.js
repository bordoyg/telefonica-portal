RightNow.namespace('Custom.Widgets.WorkOrders.PopupCancelarOrden');
Custom.Widgets.WorkOrders.PopupCancelarOrden = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
        createdEvents=false;
        document.addEventListener("cancelarMenuBtnClicked", function(){
            document.getElementById("PopupCancelarOrden").style.display="block";
            if(!createdEvents){
                document.getElementById("confirmarPopupCancelarOrden").addEventListener("click",function(){
                    $("button[value='cancelar']")[0].click();
                    document.getElementById("PopupCancelarOrden").style.display="none";
                });
                document.getElementById("cancelarPopupCancelarOrden").addEventListener("click",function(){
                    document.getElementById("PopupCancelarOrden").style.display="none";
                });
                createdEvents=true;
            }
        })
        
    },
});