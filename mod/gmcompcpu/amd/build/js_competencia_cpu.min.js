
define(['jquery'], function($) {
    return {
        init: function() {

            
            $('.gmcompcpu-section-contianer-menu-opcion').hide();
            $('#gmcompcpu-contianer-menu-opcion-inicio-container').show();
            $('.gmcompcpu-contianer-menu-opcion-js').click(function() {
                $('.gmcompcpu-contianer-menu-opcion-activa').attr('class', 'gmcompcpu-contianer-menu-opcion');
                $(this).attr('class', 'gmcompcpu-contianer-menu-opcion-activa');
                $('.gmcompcpu-section-contianer-menu-opcion').hide();
                $('#'+$(this).attr('id')+'-container').show();
            });

            $('.gmcompcpu-container-niveles').hide();
            $('#gmcompcpu-container-nivel-1').show();
            $('#gmcompcpu-select-scores-dificultad').on('change', function() {
                $('.gmcompcpu-container-niveles').hide();
                $('#gmcompcpu-container-nivel-'+this.value).show();
              });
        }
    };
});
