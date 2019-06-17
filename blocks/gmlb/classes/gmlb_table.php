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

require_once "$CFG->libdir/tablelib.php";

class gmlb_table extends flexible_table{

    function col_firstname($row){
        return "-".$row->firstname."-";
    }
    
    function __construct(){
        parent::__construct(1);
        global $CFG;
        
        // Define the list of columns to show.
        $columns = array('rank','level','fullname','xp');
        $this->define_columns($columns);
        
        // Define the list of headers to show
        $headers = array('Rank','Level','User','Progress');
        $this->define_headers($headers);
        
        $this->define_baseurl($CFG->wwwroot."/blocks/gmlb/leaderboard.php");
    }
    
    function out($pagesize, $useinitialsbar, $downloadhelpbutton='') {
        $this->setup();

/*
        // Compute where to start from.
        if (empty($this->fence)) {
            $requestedpage = optional_param($this->request[TABLE_VAR_PAGE], null, PARAM_INT);
            if ($requestedpage === null) {
                $mypos = $this->leaderboard->get_position($this->userid);
                if ($mypos !== null) {
                    $this->currpage = floor($mypos / $pagesize);
                }
            }
            $this->pagesize($pagesize, $this->leaderboard->get_count());
            $limit = new limit($pagesize, (int) $this->get_page_start());

        } else {
            $this->pagesize($this->fence->get_count(), $this->fence->get_count());
            $limit = $this->fence;
        }

        $ranking = $this->leaderboard->get_ranking($limit);
        foreach ($ranking as $rank) {
            $classes = ($this->userid == $rank->get_state()->get_id()) ? 'highlight-row' : '';
        }
        */
        
        /*$this->set_sql(
            "{user}.id, nivel_actual, firstname, lastname, experiencia_actual",
            "{gmdl_usuario}, {user}",
            'mdl_id_usuario = {user}.id');*/
        
        global $DB;
        $users = $DB->get_records('user', null, '', '*');
        
        foreach( $users as $user ){
            $this->add_data_keyed(array(
                'rank' => 1,
                'level' => 13,
                'fullname' => $this->col_fullname($user),
                'xp' => 13
            ));
        }
        
        $this->finish_output();
    }
}
