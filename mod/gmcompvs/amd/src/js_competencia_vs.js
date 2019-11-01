
define(['jquery'], function($) {
    return {
        init: function() {
            $('.gmcompvs-container-rival').hide();
            var elementos = $('.gmcompvs-container-rival');
            var ocultos = elementos.length;
            $('#gmcompvs-buscador').on('input', function() {
                ocultos = elementos.length;
                if(!$('#gmcompvs-buscador').val().length == 0)
                    {
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
                    }
                else
                    {
                        $('.gmcompvs-container-rival').hide();
                    }
                if(ocultos == elementos.length)
                    {
                        $('#gmdl-container-sin-resultados').show();
                    }
                else
                    {
                        $('#gmdl-container-sin-resultados').hide();
                    }
            });
        }
    };
});
