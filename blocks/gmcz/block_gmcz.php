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

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = html_writer::empty_tag( 'div',
            array('id'=>'canvasDiv')
        );
        $this->content->footer = "";
        $avatar = self::get_avatar('knight');

	    $PAGE->requires->js_call_amd('block_gmcz/sprite', 'init',array($avatar));
	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }

    static function get_avatar($avatar) {

        global $CFG;
        $path = "{$CFG->wwwroot}/blocks/gmcz/pix/{$avatar}";

        return array(
            array('name' => 'hair', 'src' => "$path/hair.png"),
            array('name' => 'head', 'src' => "$path/head.png"),
            array('name' => 'torso', 'src' => "$path/torso.png"),
            array('name' => 'rightArm', 'src' => "$path/rightArm.png"),
            array('name' => 'leftArm', 'src' => "$path/leftArm.png"),
            array('name' => 'legs', 'src' => "$path/legs.png"),
        );
    }
}
