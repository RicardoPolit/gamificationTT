
define(['block_gmxp/experienceUp'], function(experienceUp) {

    /*var popup = $("#gmxp-popup")[0];
    function hidePopup(params){
        popup.style.display = "none";
        experienceUp.parametrized($(".gmxp-progress")[0],params);
    }*/
 
    return {
        init: function(){
            params = Object
            params.inicio = 12;
            params.final  = 134;
            
            experienceUp.parametrized($(".gmxp-progress")[0],params);
            
            /*popup.style.display = "block";
            
            $("#gmxp-popup").on("click", hidePopup(params));
            window.onkeydown = function(e) {
                hidePopup(params);
                if (e.keyCode == 32 && e.target == document.body)
                    e.preventDefault();
            };*/
            
        }
        
    };
});
