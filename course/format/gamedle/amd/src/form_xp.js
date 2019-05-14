/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 *		
*/

define(['jquery'], function($) {
 
    return {
        form: function(totalxp){
        
            console.log("TODO: Pasar como parametros los strings para mensajes");
            
            $('#submitXP').on('click',function(){
            
                totalxp = parseInt(totalxp);
                var a = 0;
                $('.sectionXP').each(function(){
                    a += parseInt($(this).val());
                    console.log("Section #" + $(this).attr('data-section') + ":" + $(this).val() );
                })
                
                if( a == totalxp )
                    alert("TODO: Send data to server");
                else
                    alert("TODO: Send error message to form");
            });
            
            $('#defaultXP').on('click',function(){
                alert("TODO: Send request to server");
            });
        }
    };
});
