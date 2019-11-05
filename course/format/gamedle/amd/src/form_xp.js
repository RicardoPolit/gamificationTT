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
 
    function validate(totalxp, service){
        var sectionsXP = Array();
        var exit  = false;
        var regex = /^\d+$/;
        var a = 0;
        
        $('.gmxp-section').each(function(){
        
            var id = $(this).attr('data-section');
            var value = $(this).val();
            
            if( !regex.test(value) ){ exit = true; return; }
            
            sectionsXP[id] = value;
            a += parseInt(value);
        });
        
        if( !exit && a == totalxp ){
            $("#gmxp-sum").hide();
            $("#submitError").css('color','#000');
            requestChanges(false,sectionsXP, service);
            
        } else {
            if(!exit){
                $("#gmxp-sum-val").html(a);
                $("#gmxp-sum").show();
            }
            $("#submitError").css('color','#CF0000');
        }
    }
    
    function requestChanges(auto, data, service){
    
        var obj = { defaultXP:auto };
        if(!auto)
            obj["data"] = data;

            console.log(service);
        
        $.ajax({
    		method:"post",
    		url: service,
    		data: obj,
    		success: function(resp){
    		    obj = JSON.parse(resp);
    		    
    		    if( obj.status == "BAD" )
    		        alert( obj.msg );

		        else
		            location.reload();
    		},
    		error: function(err,or){
    			alert('SERVER ERROR: ' + service);
    		}
    	});
    }
 
    return {
        form: function(totalxp, service){
            totalxp = parseInt(totalxp);
            alert('thisis happendin');
            
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
                validate(totalxp, service);
            });
            
            $('#defaultXP').on('click',function(){
                requestChanges(true, undefined, service);
            });
        }
    };
});
