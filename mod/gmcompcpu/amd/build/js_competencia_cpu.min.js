
define(['jquery'], function($) {
    return {
        init: function() {

            $('#gmcompcpu-container-intentos').hide();
            $('#gmcompcpu-container-inicio').show();
            $('#gmcompcpu-container-posiciones').hide();

            $("#gmcompcpu-ver-scores").click(function() {
                $('#gmcompcpu-container-intentos').hide();
                $('#gmcompcpu-container-inicio').hide();
                $('#gmcompcpu-container-posiciones').show();
            });
            $("#gmcompcpu-ver-intentos").click(function() {
                $('#gmcompcpu-container-intentos').show();
                $('#gmcompcpu-container-inicio').hide();
                $('#gmcompcpu-container-posiciones').hide();
            });
            $("#gmcompcpu-volver-intentos").click(function() {
                $('#gmcompcpu-container-intentos').hide();
                $('#gmcompcpu-container-inicio').show();
                $('#gmcompcpu-container-posiciones').hide();
            });
            $("#gmcompcpu-volver-posiciones").click(function() {
                $('#gmcompcpu-container-intentos').hide();
                $('#gmcompcpu-container-inicio').show();
                $('#gmcompcpu-container-posiciones').hide();
            });
        }
    };
});
