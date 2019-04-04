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

	/* @Override */
	public function get_content() {
	
	    global $PAGE;
	
	    if($this->content !== null){
	        return $this->content;
	    }
	    
	    $this->content = new StdClass;
	    $this->content->text   = $this->htmlMedal(null,0);
	    $this->content->footer = $this->htmlProgressBar("#1177d1");
	    
	    $PAGE->requires->js_call_amd('block_gmxp/levelUp', 'init');
	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }
  
    /* PRIVATE METHODS TO ORGANIZE VIEW */  
    private function htmlMedal($urlimage,$level,$color){
        if($urlimage==null)
            $urlimage = "https://www.expressmedals.com/v/vspfiles/photos/030-1.jpg";
            
        if($level<1)
            $level = 1;
            
        return "<div><img src=\"$urlimage\" class=\"gmxp-medal\"/></div>";
    }
    
    private function htmlProgressBar($color){
        return "<div class=\"gmxp-bar\"><div class=\"gmxp-progress\" style=\"width:50%;background-color:$color\"></div></div>";
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