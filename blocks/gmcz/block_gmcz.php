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

class block_gmcz extends block_base {

    const PLUGIN = 'block_gmcz';
    private $image = "";

    /* @Override */
	public function init(){
	    $this->title = get_string("gmcz", self::PLUGIN);
	}

	public function get_content() {
		
	    global $PAGE;
	    $obj = array("inicio"=>0,"final"=>53);
	    $PAGE->requires->js_call_amd('block_gmcz/sprite', 'init',array($obj,120));
	    return "HOLA MUNDO";
	}

    function has_config() { return true; }
    function hide_header() { return false; }

}
