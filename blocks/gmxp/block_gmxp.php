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
	    $this->content->text   = $this->htmlMedal(3);

        //$this->representarDeExperiencia();
	    $this->content->footer = $this->htmlProgressBar(3,100,200,100);
	    $this->content->footer.= $this->htmlPopUp(3);
	   
	    $experience = array("inicio"=>0,"final"=>53);
	    //$PAGE->requires->js_call_amd('block_gmxp/levelUp', 'init',array($experience));
	    $PAGE->requires->js_call_amd('block_gmxp/experienceUp', 'init',array($experience));	    
	    
	    if(isset($_SESSION['Gamedle']))
        $this->debugWebConsole("USER",$_SESSION['Gamedle']);
        
        //if(isset($_SESSION['Gamedle']['XP']['Extra']))
        //echo("<script>console.log('Cou: ". file_get_contents( "proof.txt" ). "');</script>");
	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }


    /* PRIVATE METHODS TO ORGANIZE VIEW */
    private function getLevelImage(){
        $image = get_config('block_gmxp', 'imageDefecto');
        return moodle_url::make_pluginfile_url($this->context->id, 'block_gmxp', "level_images_simplehtml", 110, "/level1", $image);
    }

    private function htmlMedal($level){
        $urlimage = $this->getLevelImage();
            
        if($level<1)
            $level = 1;

        return "<div class=\"gmxp-container\">".
                   "<img class=\"gmxp-medal\" src=\"$urlimage\" />
                   <div class=\"gmxp-level\" style=\"color:".get_config('block_gmxp','defaultColorPickerLevels')."\">$level</div>
               </div>";
    }
    
    private function htmlProgressBar($progress,$exp_updated,$exp_need,$acumulada){
        return "<div class=\"gmxp-bar\">
                    <div class=\"gmxp-progress\"
                      style=\"width:$progress%;background-color:".get_config('block_gmxp','defaultColorPickerProgressBar')."\">
                    </div>
                </div>
                <div class='gmxp-txt-lvl'>
                    Level XP: <b>$exp_updated/$exp_need </b><br>
                    Total XP: <b> $acumulada</b>
                </div>";
    }
    
    private function htmlPopup($level){
        return "<div id=\"gmxp-popup\">
                   <div id=\"gmxp-content\">
                        <div class=\"gmxp-title\">".get_config('block_gmxp','defaultLevelsMessage')."</div>".
                        $this->htmlMedal($level).
                        "<div class=\"gmxp-level-name\"><b>".get_config('block_gmxp','defaultLevelsName')."</b></div>".
                        "<div class=\"gmxp-desc\">Descripcion de un nivel bien bonito carnal</div>".
                    "</div>
               </div>";
    }
    
    private function debugWebConsole($tag,$object){
        echo("<script>console.log('".$tag.": ".json_encode($object)."');</script>");
    }
    
    /*private function representarDeExperiencia()
        {
            global $USER;
            $exp_act = $_SESSION['Gamedle']['user_xp'] ;
            $exp_neces = $this->calcularExpNecesaria();
            $porcentaje  = $exp_act*100/$exp_neces;
            $this->content->footer = $this->htmlProgressBar("#1177d1",$porcentaje,$exp_act,$exp_neces,$this->calcularExpAcumulada($exp_act));
            if($_SESSION['Gamedle']['xp_por_dar'] > 0) //El usuario recebirá la experiencia
                {
                    if($_SESSION['Gamedle']['xp_por_dar'] >= $exp_neces) //La experiencia que recibirá es más de la que necesita el nivel
                        {
                            $_SESSION['Gamedle']['xp_por_dar'] = $_SESSION['Gamedle']['xp_por_dar'] - $exp_neces;
                            $this->content->footer.= $this->htmlPopUp(null,13,"#31a7f1",26,"#1177d1");
                        }
                    else
                        {
                            $_SESSION['Gamedle']['xp_por_dar'] = 0;
                            $porcentajef  = ($exp_act+$_SESSION['Gamedle']['xp_por_dar'])*100/$exp_neces;
                            $PAGE->requires->js_call_amd('block_gmxp/experienceUp', 'init',array(array("inicio"=>$porcentaje,"final"=>$porcentajef)));

                        }
                }
        }*/
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
