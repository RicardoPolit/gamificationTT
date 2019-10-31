
define(['jquery'], function($) {
    return {
        init: function() {

            $('#gmcompvs-container-intentos').hide();
            $('#gmcompvs-container-inicio').show();
            $('#gmcompvs-container-posiciones').hide();

            $("#gmcompvs-ver-scores").click(function() {
                $('#gmcompvs-container-intentos').hide();
                $('#gmcompvs-container-inicio').hide();
                $('#gmcompvs-container-posiciones').show();
            });
            $("#gmcompvs-ver-intentos").click(function() {
                $('#gmcompvs-container-intentos').show();
                $('#gmcompvs-container-inicio').hide();
                $('#gmcompvs-container-posiciones').hide();
            });
            $("#gmcompvs-volver-intentos").click(function() {
                $('#gmcompvs-container-intentos').hide();
                $('#gmcompvs-container-inicio').show();
                $('#gmcompvs-container-posiciones').hide();
            });
            $("#gmcompvs-volver-posiciones").click(function() {
                $('#gmcompvs-container-intentos').hide();
                $('#gmcompvs-container-inicio').show();
                $('#gmcompvs-container-posiciones').hide();
            });
        }
    };
});
