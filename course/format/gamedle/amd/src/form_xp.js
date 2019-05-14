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
 
    function validate(totalxp){
        var sectionsXP = Array();
        var a = 0;
        var exit = false;
        
        $('.gmxp-section').each(function(){
        
            var id = $(this).attr('data-section');
            var value = $(this).val();
            var regex = /^\d+$/;
            
            if( !regex.test(value) ){ exit = true; return; }
        
            sectionsXP[id] = value;
            a += parseInt(value);
        });
        
        if( !exit && a == totalxp ){
            $("#gmxp-sum").hide();
            $("#submitError").css('color','#000');
            requestChanges(false,sectionsXP);
            
        } else {
            if(!exit){
                $("#gmxp-sum-val").html(a);
                $("#gmxp-sum").show();
            }
            $("#submitError").css('color','#CF0000');
        }
    }
    
    function requestChanges(auto,data){
    
        var obj = { defaultXP:auto };
        if(!auto)
            obj["data"] = data;
        
        console.log("TODO: requestChanges -> Make AJAX send following object");
        console.log(obj);
    }
 
    return {
        form: function(totalxp){
            totalxp = parseInt(totalxp);
            
            $('.gmxp-section').on('keyup',function(e){
                var regex = /^\d+$/;
                var input = $(this).val();
                var num   = $(this).attr('data-section');
                
                if( regex.test(input) )
                    $('#xpError'+num).hide();
                else
                    $('#xpError'+num).show();
            });
            
            $('#submitXP').on('click',function(){
                validate(totalxp);
            });
            
            $('#defaultXP').on('click',function(){
                requestChanges(true);
            });
        }
    };
});
