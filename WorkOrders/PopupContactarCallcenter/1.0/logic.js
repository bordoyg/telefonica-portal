RightNow.namespace('Custom.Widgets.WorkOrders.PopupContactarCallcenter');
Custom.Widgets.WorkOrders.PopupContactarCallcenter = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
        createdEvents=false;
        document.addEventListener("callCallCenterShown", function(){
            document.getElementById("PopupContactarCallCenter").style.display="block";
            if(!createdEvents){
                document.getElementById("confirmarPopupContactarCallCenter").addEventListener("click",function(){
                    $("button[value='callCenterContact']")[0].click();
                    document.getElementById("PopupContactarCallCenter").style.display="none";
                });
                document.getElementById("cancelarPopupContactarCallCenter").addEventListener("click",function(){
                    document.getElementById("PopupContactarCallCenter").style.display="none";
                });
                createdEvents=true;
            }
        })
    },
});