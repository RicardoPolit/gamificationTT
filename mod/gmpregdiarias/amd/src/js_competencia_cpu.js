
define(['jquery'], function($) {
    return {
        init: function() {

            
            $('.gmpregdiarias-section-contianer-menu-opcion').hide();
            $('#gmpregdiarias-contianer-menu-opcion-inicio-container').show();
            $('.gmpregdiarias-contianer-menu-opcion-js').click(function() {
                $('.gmpregdiarias-contianer-menu-opcion-activa').attr('class', 'gmpregdiarias-contianer-menu-opcion');
                $(this).attr('class', 'gmpregdiarias-contianer-menu-opcion-activa');
                $('.gmpregdiarias-section-contianer-menu-opcion').hide();
                $('#'+$(this).attr('id')+'-container').show();
            });

            $('.gmpregdiarias-container-niveles').hide();
            $('#gmpregdiarias-container-nivel-1').show();
            $('#gmpregdiarias-select-scores-dificultad').on('change', function() {
                $('.gmpregdiarias-container-niveles').hide();
                $('#gmpregdiarias-container-nivel-'+this.value).show();
              });
        }
    };
});
