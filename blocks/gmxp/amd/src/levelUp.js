
define(['block_gmxp/experienceUp'], function(experienceUp) {

    var popup = $("#gmxp-popup")[0];
    var flag = true;
    
    function hidePopup(params){
        popup.style.display = "none";
        experienceUp.parametrized($(".gmxp-progress")[0],params);
    }
 
    return {
        init: function(params){
            params = Object
            params.inicio = 12;
            params.final  = 134;
            
            popup.style.display = "block";
            $("#gmxp-popup").on("click", function(){
                hidePopup(params)
            });
            window.onkeydown = function(e) {
                if(flag){
                    flag = false
                    hidePopup(params);
                    if (e.keyCode == 32 && e.target == document.body)
                        e.preventDefault();
                }
            };
        }
        
    };
});
