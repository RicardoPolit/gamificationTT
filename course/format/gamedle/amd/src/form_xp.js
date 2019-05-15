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
        
        var proto = window.location.protocol;
        var host  = window.location.hostname;
        var path  = window.location.pathname.replace('view.php','format/gamedle/cli/courseXP.php');
        
        $.ajax({
    		method:"post",
    		url: proto+"//"+host+path,
    		data: obj,
    		success: function(resp){
    		    alert(resp);
    		},
    		error: function(err,or){
    			alert('SERVER ERROR: '+ proto+"//"+host+path);
    		}
    	});
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
