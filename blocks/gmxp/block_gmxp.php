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
	global $PAGE;

	public function init(){
	    $this->title = get_string("gmxp","block_gmxp");
	}

	public function get_content() {	
	
	    if($this->content !== null){
	        return $this->content;
	    }

	    $this->content = new StdClass;
	    $this->content->text   = $this->htmlMedal(null,13);

            $this->representarDeExperiencia();

	    $this->content->footer = $this->htmlProgressBar(3,100,200,100);
	    $this->content->footer.= $this->htmlPopUp(null,13,26);
	   
	    $experience = array("inicio"=>0,"final"=>53);
	    $PAGE->requires->js_call_amd('block_gmxp/levelUp', 'init',array($experience));
	    $PAGE->requires->js_call_amd('block_gmxp/experienceUp', 'init',array($experience));

	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }
    
  
    /* PRIVATE METHODS TO ORGANIZE VIEW */
    private function htmlMedal($urlimage,$level){
        if($urlimage==null)
            $urlimage = "http://www.escom.ipn.mx/images/escudoESCOM.jpg";
            
        if($level<1)
            $level = 1;
            
        return "<div class=\"gmxp-container\">
                   <img class=\"gmxp-medal\" src=\"$urlimage\" />
                   <div class=\"gmxp-level\" style=\"color:".get_config('block_gmxp','defaultColorPickerLevels')."\">$level</div>
               </div>";
    }
    
    private function htmlProgressBar($progress,$exp_act,$exp_neces,$acumulada){
        return "<div class=\"gmxp-bar\">
                    <div class=\"gmxp-progress\"
                      style=\"width:$progress%;background-color:".get_config('block_gmxp','defaultColorPickerLevels')."\">
                    </div>
                </div>
                <div>
                    <p  class='gmxp-txt-lvl'> Exp. del Nivel: <b>$exp_act/$exp_neces </b></p>
                    <p  class='gmxp-txt-lvl'> Exp. total: <b > $acumulada</b></p>
                </div>";
    }
    
    private function htmlPopup($urlimage,$level,$progress){
        return "<div id=\"gmxp-popup\">
                   <div id=\"gmxp-content\">
                        <div class=\"gmxp-title\">".get_config('block_gmxp','defaultLevelsMessage')."</div>".
                        $this->htmlMedal($urlimage,$level).
                        "<div class=\"gmxp-level-name\"><b>".get_config('block_gmxp','defaultLevelsName')."</b></div>".
                        "<div class=\"gmxp-desc\">Descripcion de un nivel bien bonito carnal</div>".
                    "</div>
               </div>";
    }
    private function representarDeExperiencia($PAGE)
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
