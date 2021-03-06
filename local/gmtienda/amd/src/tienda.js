
define(['jquery'], function($) {
    return {
        init: function() {

            var clasesPorDefecto = 'gmtienda-objeto-imagen ';
            var enMuestraColor = '';
            var enMuestraTipo = '';

            var disponibles = [1 , 1, 1];
            var dataObjeto = JSON.parse($('#gmtienda-objeto-muestra-ant').attr('data-id'));

            $('.gmtienda-container-objeto').on('click', function() {
                var tipo = $(this).attr('data-tipo');
                var valor = $(this).attr('value');
                //alert(dataObjeto[2]);
                if(tipo == 1)
                    {
                        $('#gmtienda-objeto-muestra').attr('src', valor);
                        disponibles[tipo-1] =  $(this).attr('data-disponible');
                        dataObjeto[tipo-1] =  $(this).attr('data-objeto');
                    }
                else if(tipo == 2)
                    {
                        enMuestraTipo = valor;
                        $('#gmtienda-objeto-muestra').attr('class', clasesPorDefecto +' '+ enMuestraTipo +' '+  enMuestraColor+'' );
                        dataObjeto[tipo-1] =  $(this).attr('data-objeto');
                    }
                else if(tipo == 3)
                    {
                        enMuestraColor = valor+'-solo';
                        $('#gmtienda-objeto-muestra').attr('class', clasesPorDefecto +' '+ enMuestraTipo +' '+ enMuestraColor );
                        dataObjeto[tipo-1] =  $(this).attr('data-objeto');
                    }
                if(disponibles[0]*1 + disponibles[1]*1 + disponibles[2]*1 == 3)
                    {
                        $('#gmtienda-boton-guardar').show();
                        $('#gmtienda-arreglo-para-cambiar').attr('value', JSON.stringify(dataObjeto));
                    }
                else
                    {
                        $('#gmtienda-boton-guardar').hide();
                    }
                //alert(dataObjeto[2]);
            });

            $('#gmtienda-boton-limpiar').on('click', function() {
                $('#gmtienda-objeto-muestra').attr('src', $('#gmtienda-objeto-muestra-ant').attr('src'));
                $('#gmtienda-objeto-muestra').attr('class', $('#gmtienda-objeto-muestra-ant').attr('class'));
                disponibles = [1 , 1, 1];
                $('#gmtienda-boton-guardar').show();
                $('#gmtienda-arreglo-para-cambiar').attr('value', $('#gmtienda-objeto-muestra-ant').attr('data-id') )
                
            });
            $('.gmtienda-container-muestra-actual').hide();
        }
    };
});
    