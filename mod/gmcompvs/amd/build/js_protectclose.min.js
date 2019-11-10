define(['jquery'], function($) {

    return {
        init: function() {
            // Put whatever you like here. $ is available
            // to you as normal.
            window.onbeforeunload = function(){
                return "No podras volver a este intento";
            };
            $("form").on('submit', function() {
                window.onbeforeunload = null;
            });
        }
    };
});