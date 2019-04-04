
define(['block_gmxp/experienceUp'], function(experienceUp) {

// Get the modal
var modal = document.getElementById('myModal');
var btn = document.getElementById("myBtn");

    var popup = $(".gmxp-popup")[0];

    function hidePopup(){
        popup.style.display = "none";
    }
 
    return {
        init: function(){
            var a = Object
                a.inicio = 4
                a.final = 100
                
            popup.style.display = "block";
            experienceUp.parametrized($(".gmxp-progress")[1],a)
            alert("Felicitaciones");
            
            setTimeout(hidePopup,10000);
        }
        
    };
});
