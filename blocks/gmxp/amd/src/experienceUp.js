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

    function experienceUp(ini,fin){
        
        if( fin > 100 ) fin = 100;
        var progressBar = $(".gmxp-progress")[0];
        var width = ini;
        var id = setInterval(draw,30);
        
        function draw(){
            width++;
            progressBar.style.width = width+'%';
            
            if( width > fin )
                clearInterval(id);
        }
    }
 
    return { init: function(params) { experienceUp(params.inicio,params.final); } };
});
