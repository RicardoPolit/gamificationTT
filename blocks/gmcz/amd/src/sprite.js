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
 
    function printObj(obj){
        console.log(obj);
    }
 
    return {
        init: function(obj, value){
            printObj(obj);
            console.log(value);
        }
    };
});
