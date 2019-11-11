
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

            var clasesPorDefecto = 'gmtienda-objeto-imagen ';
            var enMuestraColor = '';
            var enMuestraTipo = '';

            var disponibles = [1 , 1, 1];

            $('.gmtienda-container-objeto').on('click', function() {
                var tipo = $(this).attr('data-tipo');
                var valor = $(this).attr('value');
                if(tipo == 1)
                    {
                        $('#gmtienda-objeto-muestra').attr('src', valor);
                        disponibles[tipo-1] =  $(this).attr('data-disponible');
                    }
                else if(tipo == 2)
                    {
                        enMuestraTipo = valor;
                        $('#gmtienda-objeto-muestra').attr('class', clasesPorDefecto + enMuestraTipo +' '+  enMuestraColor+'' );
                        disponibles[tipo-1] =  $(this).attr('data-disponible');
                    }
                else if(tipo == 3)
                    {
                        enMuestraColor = valor+'-solo';
                        $('#gmtienda-objeto-muestra').attr('class', clasesPorDefecto + enMuestraTipo +' '+ enMuestraColor );
                        disponibles[tipo-1] =  $(this).attr('data-disponible');
                    }
                    if(disponibles[0] + disponibles[1] + disponibles[2] == 3)
                    {
                        $('#gmtienda-boton-guardar').show();
                    }
                else
                    {
                        $('#gmtienda-boton-guardar').hide();
                    }
            });

            $('#gmtienda-boton-limpiar').on('click', function() {
                $('#gmtienda-objeto-muestra').attr('src', $('#gmtienda-objeto-muestra-ant').attr('src'));
                $('#gmtienda-objeto-muestra').attr('class', $('#gmtienda-objeto-muestra-ant').attr('class'));
                disponibles = [1 , 1, 1];
                if(disponibles[0] + disponibles[1] + disponibles[2] == 3)
                    {
                        $('#gmtienda-boton-guardar').show();
                    }
                else
                    {
                        $('#gmtienda-boton-guardar').hide();
                    }
            });
            
        }
    };
});
