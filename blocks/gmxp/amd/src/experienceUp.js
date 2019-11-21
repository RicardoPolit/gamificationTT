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

    function experienceUp(ini,fin,lvl,tot){

        progressBar = $(".gmxp-progress")[0];
        inicio = $(".gmxp-ini")[0];
        total  = $(".gmxp-tot")[0];
        
        let width = (ini / lvl)*100;
        let id = setInterval(draw,30);

        function draw(){
            ini++;
            tot++;
            total.innerHTML = tot;
            inicio.innerHTML = `${ini}/${lvl}`;
            width = (ini / lvl)*100;
            progressBar.style.width = width+'%';
            
            if( ini >= fin )
                clearInterval(id);
        }
    }
 
    return {
        init: function(params) {

            console.log(params);

            if(params.start)
            experienceUp(
                params.inicio,
                params.final,
                params.nivel,
                params.total
            );
        },
        parametrized: function(progressBar,params){
            experienceUp(progressBar,params.inicio,params.final);
        }
    };
});
