
define(['block_gmxp/experienceUp'], function(experienceUp) {

// Get the modal
var modal = document.getElementById('myModal');
var btn = document.getElementById("myBtn");

    var popup = $("#gmxp-popup")[0];
    console.log(popup)

    var a = Object
        a.inicio = 4
        a.final = 100

    function hidePopup(){ popup.style.display = "none"; experienceUp.parametrized($("gmxp-progress")[0],a);  }
 
    return {
        init: function(){
            popup.style.display = "block";
            $("#gmxp-popup").on("click", hidePopup);
            
            window.onkeydown = function(e) {
                hidePopup()
                if (e.keyCode == 32 && e.target == document.body)
                    e.preventDefault();
            };
            
        }
        
    };
});
