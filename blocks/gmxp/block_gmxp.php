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
    const XP_UP = 'block_gmxp/experienceUp';
    const LVL_UP = 'block_gmxp/levelUp';

    private $image = "";

    /* @Override */
	public function init(){
	    $this->title = get_string("gmxp", self::PLUGIN);
	}

	public function get_content() {
		
	    global $PAGE;
        global $DB;

        $user = $_SESSION['GMXP'];
        $update = $DB->get_record(block_gmxp_dao::TBL_GAMIFIED_USER,
            array( 'id' => $user->id ));

        $levelxp = block_gmxp_dao::get_level_xp($update->nivel_actual);
        $this->loadImage();


        if($this->content !== null){
            return $this->content;
        }
        local_gamedlemaster_log::info($user, 'CURRENT USER DATA');
        local_gamedlemaster_log::info($update, 'UPDATED USER DATA');

        $total = $user->experiencia_actual;;
        if ($update->nivel_actual > $user->nivel_actual) {

            $obtained = 0;
            $total = $update->experiencia_actual - $update->experiencia_nivel;
            local_gamedlemaster_log::info('increased levels!!!');
            $PAGE->requires->js_call_amd(self::LVL_UP, 'init', array(array(
                'inicio' => 0,
                'final' => $update->experiencia_nivel,
                "nivel" => $levelxp,
                "total" => $total
            )));

        } else if ($update->experiencia_nivel > $user->experiencia_nivel) {

            $obtained = $user->experiencia_nivel;
            local_gamedlemaster_log::info('increased points!!!');
            $PAGE->requires->js_call_amd(self::XP_UP,  'init', array(array(
                "inicio" => $user->experiencia_nivel,
                "final" => $update->experiencia_nivel,
                "nivel" => $levelxp,
                "total" => $total,
                "start" => true
            )));

        } else {
            $obtained = $user->experiencia_nivel;
        }

        $_SESSION['GMXP'] = $update;
	    $this->content = new StdClass;
	    $this->content->text = self::htmlMedal($update->nivel_actual, $this->image);
        $this->content->footer = self::htmlProgressBar($obtained, $levelxp, $total);
        $this->content->footer.= self::htmlPopUp($update->nivel_actual, $this->image);
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
    
    static function htmlProgressBar($obtained, $required, $total, $detail = true,
      $update_obtained = null) {

        $progress = 100 * ($obtained / $required);
        $COLOR = get_config(self::PLUGIN, block_gmxp_core::COLORBAR);

        $content = "<div class=\"gmxp-bar\">
                    <div class=\"gmxp-progress\"
                        style=\"width:$progress%;background-color:$COLOR\">
                    </div>
                    </div>";

        if ($update_obtained !== null) {
            $obtained = $update_obtained;
        }

        if($detail) {
            $content .= "<div class='gmxp-txt-lvl'>
                Level XP: <b class=\"gmxp-ini\">{$obtained}/{$required}</b><br>
                Total XP: <b class=\"gmxp-tot\">{$total}</b>
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
