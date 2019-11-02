
define(['jquery'], function($) {
    return {
        init: function() {
            //$('.gmcompvs-container-rival').hide();
            var elementos = $('.gmcompvs-container-rival');
            $('#gmdl-container-sin-resultados').hide();
            var ocultos = elementos.length;
            $('#gmcompvs-buscador').on('input', function() {
                ocultos = elementos.length;
                
                $('.gmcompvs-container-rival').each(function(i, obj) {
                    if($(this).attr('nombre').search($('#gmcompvs-buscador').val())>=0)
                        {
                            $(this).show();
                            ocultos--;
                        }
                    else
                        {
                            $(this).hide();
                        }
                });
                if(ocultos == elementos.length)
                    {
                        $('#gmdl-container-sin-resultados').show();
                    }
                else
                    {
                        $('#gmdl-container-sin-resultados').hide();
                    }
            });
        
            $('.gmcompvs-section-contianer-menu-opcion').hide();
            $('#gmcompvs-contianer-menu-opcion-inicio-container').show();
            $('.gmcompvs-contianer-menu-opcion-js').click(function() {
                $('.gmcompvs-contianer-menu-opcion-activa').attr('class', 'gmcompvs-contianer-menu-opcion');
                $(this).attr('class', 'gmcompvs-contianer-menu-opcion-activa');
                $('.gmcompvs-section-contianer-menu-opcion').hide();
                $('#'+$(this).attr('id')+'-container').show();
            });


        }
    };
});
