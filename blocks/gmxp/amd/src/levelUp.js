
define(['block_gmxp/experienceUp'], function(experienceUp) {

    var popup = $("#gmxp-popup")[0];
    var content = $('#gmxp-content')[0];
    var flag = true;

    function hidePopup(params) {
        popup.style.display = "none";
        experienceUp.parametrized($(".gmxp-progress")[0],params);
    }

    function showPopup() {
        var width = 0;
        popup.style.display = "block";
        /*var id = setInterval(draw,30);

        function draw() {
            width++;
            content.style.width = width+'%';
            if (width >= 40) {
                clearInterval(id);
            }
        }*/
    }

    return {
        init: function(params) {

            showPopup();
            $("#gmxp-popup").on("click", function(){
                hidePopup(params);
            });
            window.onkeydown = function(e) {
                if(flag){
                    flag = false;
                    hidePopup(params);
                    if (e.keyCode == 32 && e.target == document.body)
                        e.preventDefault();
                }
            };
        }
    };
});
