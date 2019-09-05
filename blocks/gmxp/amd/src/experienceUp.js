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

    function experienceUp(progressBar,ini,fin){
        
        if( fin > 100 ) fin = 100;
        var width = ini;
        var id = setInterval(draw,30);
        
        function draw(){
            width++;
            progressBar.style.width = width+'%';
            
            if( width > fin )
                clearInterval(id);
        }
    }    
 
    return { init: function(params){ experienceUp($(".gmxp-progress")[0],params.inicio,params.final,); },
             parametrized: function(progressBar,params){ experienceUp(progressBar,params.inicio,params.final); }
         };
});
