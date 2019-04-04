<?php
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

session_start();

class block_gmxp extends block_base{

    /* @Override */
	public function init(){
	    $this->title = get_string("gmxp","block_gmxp");
	}

	public function get_content() {
	
	    global $PAGE;
	
	    if($this->content !== null){
	        return $this->content;
	    }
	    
	    $this->content = new StdClass;
	    $this->content->text   = $this->htmlMedal(null,13,"#31a7f1");
	    $this->content->footer = $this->htmlProgressBar("#1177d1",3);
	    
	    $this->content->footer.= $this->htmlPopUp(null,13,"#31a7f1",26,"#1177d1");
	    
	    $PAGE->requires->js_call_amd('block_gmxp/experienceUp', 'init',array(array("inicio"=>4,"final"=>99)));
	    //$PAGE->requires->js_call_amd('block_gmxp/levelUp', 'init');
	    
	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }
    
  
    /* PRIVATE METHODS TO ORGANIZE VIEW */
    private function htmlMedal($urlimage,$level,$color){
        if($urlimage==null)
            $urlimage = "http://www.escom.ipn.mx/images/escudoESCOM.jpg";
            
        if($level<1)
            $level = 1;
            
        return "<div class=\"gmxp-container\">
                   <img class=\"gmxp-medal\" src=\"$urlimage\" />
                   <div class=\"gmxp-level\" style=\"color:$color\">$level</div>
               </div>";
    }
    
    private function htmlProgressBar($color,$progress){
        return "<div class=\"gmxp-bar\"><div class=\"gmxp-progress\" style=\"width:$progress%;background-color:$color\"></div></div>";
    }
    
    private function htmlPopup($urlimage,$level,$color,$progress,$color2){
        return "<div class=\"gmxp-popup\">
                   <div class=\"gmxp-content\">".
                        $this->htmlMedal($urlimage,$level,$color).
                        $this->htmlProgressBar($color2,$progress).
                    "</div>
               </div>";
    }
}


/*
 * Moodle Block Plugin Docs
 *    https://docs.moodle.org/dev/Blocks#Ready.2C_Set.2C_Go.21
 *
 * Moodle Blocks Guide
 *    https://docs.moodle.org/dev/Blocks
 *
 * Moodle Blocks Advanced Guide
 *    https://docs.moodle.org/dev/Blocks_Advanced
 *
 * GitHub Block Template
 *    https://github.com/danielneis/moodle-block_newblock
*/
