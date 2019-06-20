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

    /**
     * 
     */
    
    function __construct(){
        parent::__construct('leaderboard');
        global $CFG;
        
        // Define the list of columns to show.
        $columns = array('rank','level','fullname','xp');
        $this->define_columns($columns);
        
        // Define the list of headers to show
        $headers = array('Rank','Level','User','Progress');
        $this->define_headers($headers);
        
        $this->define_baseurl($CFG->wwwroot."/blocks/gmlb/leaderboard.php");
    }

    /**
     * This method displays the rank when formating
     * column rank managed with autoincrement
     */

    function col_rank($row){
        static $count = 1;
        return $count++;
    }
    
    /**
     * This method displays the current level when formating
     * column level
     */
    
    function col_level($row){
        return $row->nivel_actual;
    }
    
    /**
     * This method displays the progress bar when formatting
     * column xp.
     */
    
    function col_xp($row){
    
        // Getting the values in order to calculate the XP of the level
        $typeOfIncrement = get_config('block_gmxp','typeOfIncrement');
        $increment  = get_config('block_gmxp','numValorIncrement');
        $xpLevelOne = get_config('block_gmxp','firstExpRequiried');
        $xpPoints = $row->experiencia_actual;
        
        if($typeOfIncrement==0) // Percentual
            $requiredXP = $xpLevelOne*( $increment^($row->nivel_actual-1) );
        
        else // $typeOfIncrement==1 // Lineal
            $requiredXP = $xpLevelOne + ( $increment*($row->nivel_actual+1) );
            
        $progress = $xpPoints / $requiredXP;
        
        return "<div class=\"gmxp-bar\">
                    <div class=\"gmxp-progress\"
                      style=\"width:$progress%;background-color:".get_config('block_gmxp','defaultColorPickerProgressBar')."\">
                    </div>
                </div>
                <div class='gmxp-txt-lvl'>
                    <b>Level XP: $xpPoints/$xpLevelOne </b><br>
                </div>";
    }
    
    /**
     * Method to display when cannot find "method col_column"
     */
    
    function other_cols($column, $row) {
        return "Method col_$column(\$row) not exists<br>\n".json_encode($row);
    }
    
    
    /**
     * This function gets the data of gamified users 
     * and format it as rows in the table
     */
    
    function out($pagesize, $useinitialsbar, $downloadhelpbutton='') {
        
        // Required due to override this method of parent class
        $this->setup();
        
        // Retrieving data of the moodle database
        global $DB;
        $rank = 1;
        $users = $DB->get_records_sql(
            'SELECT {user}.id, firstnamephonetic, lastnamephonetic, middlename, '.
                   'alternatename, firstname, lastname, nivel_actual, experiencia_actual '.
            'FROM {user}, {gmdl_usuario} '.
            'WHERE {user}.id = {gmdl_usuario}.mdl_id_usuario');
            
        // All the name attributes are required to call the function col_fullname
        // this function internally calls the function fullname in moodlelib.php
        
        // Iterate over rows to format them
        foreach( $users as $user ){
            $this->add_data_keyed($this->format_row($user));
        }
 
        // Required due to override this method of parent class
        $this->finish_output();
    }

}
