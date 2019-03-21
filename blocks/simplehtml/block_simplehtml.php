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

class block_simplehtml extends block_base{

    public $gamedle_image;
    public $gamedle_text;
    public $gamedle_footer;

    /* @Override */
	public function init(){
	    $this->title = get_string("simplehtml","block_simplehtml");
	}

	/* @Override */
	public function get_content() {
	    if($this->content !== null){
	        return $this->content;
	    }
	    
	    $this->content = new StdClass;
	    $this->content->text = $this->gamedle_text;
	    $this->content->footer = $this->gamedle_footer;
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
