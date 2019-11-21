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

class block_gmxp extends block_base {

    const PLUGIN = 'block_gmxp';
    private $image = "";

    /* @Override */
	public function init(){
	    $this->title = get_string("gmxp", self::PLUGIN);
	}

	public function get_content() {
		
	    global $PAGE;
	    if($this->content !== null){
	        return $this->content;
	    }

	    $this->content = new StdClass;

        $this->loadImage();
	    $this->content->text   = self::htmlMedal(3, $this->image);

        //$this->representarDeExperiencia();
	    $this->content->footer = self::htmlProgressBar(3,100,200,100);
	    $this->content->footer.= self::htmlPopUp(3, $this->image);
	   
	    $experience = array("inicio"=>0,"final"=>53);
	    //$PAGE->requires->js_call_amd('block_gmxp/levelUp', 'init',array($experience));
	    $PAGE->requires->js_call_amd('block_gmxp/experienceUp', 'init',array($experience));

	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }


    /* PRIVATE METHODS TO ORGANIZE VIEW */
    private function loadImage(){

        global $CFG;
        $image = get_config(self::PLUGIN, block_gmxp_core::IMAGE);
        $this->image = $CFG->wwwroot . block_gmxp_core::PATH_IMAGE . $image;
    }

    static function htmlMedal($level, $urlimage){

        $COLOR = get_config(self::PLUGIN, block_gmxp_core::COLORLVL);

        if($level<1)
            $level = 1;

        return "<div class=\"gmxp-container\">".
                   "<img class=\"gmxp-medal\" src=\"$urlimage\"/>
                   <div class=\"gmxp-level\" style=\"color:$COLOR\">$level</div>
               </div>";
    }
    
    static function htmlProgressBar($progress,$exp_updated,$exp_need,$acumulada,
      $detail = true) {

        $COLOR = get_config(self::PLUGIN, block_gmxp_core::COLORBAR);
        $content = "<div class=\"gmxp-bar\">
                    <div class=\"gmxp-progress\"
                      style=\"width:$progress%;background-color:$COLOR\">
                    </div>
                </div>";

        $GMXP = $_SESSION['GMXP'];
        $levelxp = block_gmxp_dao::get_level_xp($GMXP['level']);

        if($detail) {
            $content .= "<div class='gmxp-txt-lvl'>
                Level XP: <b>{$GMXP['experiencia_nivel']}/{$levelxp}</b><br>
                Total XP: <b>{$GMXP['experiencia_actual']}</b>
            </div>";
        }

        return $content;
    }
    
    static function htmlPopup($level, $image){
        return "<div id=\"gmxp-popup\">
                   <div id=\"gmxp-content\">
                        <div class=\"gmxp-title\">".get_config('block_gmxp','defaultLevelsMessage')."</div>".
                        self::htmlMedal($level, $image).
                        "<div class=\"gmxp-level-name\"><b>".get_config('block_gmxp','defaultLevelsName')."</b></div>".
                        "<div class=\"gmxp-desc\">Descripcion de un nivel bien bonito carnal</div>".
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
