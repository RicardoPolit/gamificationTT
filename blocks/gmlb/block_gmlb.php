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

class block_gmlb extends block_base{

    /* @Override */
	public function init(){
	    $this->title = get_string("gmlb","block_gmlb");
	}

	public function get_content() {
		
	    if($this->content !== null){
	        return $this->content;
	    }
	    
	    $this->content = new StdClass;
	    $this->content->text   = ""; //$this->leaderList();
	    
	    
	    $this->content->footer = "<div style=\"text-align:center;margin: 10px 0;\"><b>...</b></div>
	    <div style=\"border: solid 2px black; margin: 10px 0; padding:5px;font-size:16px\">150 Usuario <span style=\"float:right;\">1,004</span></div>";
        
	    return $this->content;
	}

    function has_config() { return true; }
    function hide_header() { return false; }
    
    private function leaderList(){
        return "<table class=\"gmlb\">
            <tr class=\"head\">
                <th style=\"text-align:center;\">#</th>
                <th>User</th>
                <th class=\"right\">level</th>
            </tr>
            <tr class=\"item\" style=\"padding:50px;font-size:20px\">
                <td style=\"text-align:center; color:#ffb000;\"><i class=\"fa fa-trophy\"></i></td>
                <td><a href=\"https://www.google.com\">DanyKiller</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\" style=\"padding:50px;font-size:20px\">
                <td style=\"text-align:center; color:#AAA;\"><i class=\"fa fa-trophy\"></i></td>
                <td><a href=\"www.google.com\">Magdalena</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\" style=\"padding:50px;font-size:20px\">
                <td style=\"text-align:center;  color:brown;\"><i class=\"fa fa-trophy\"></i></td>
                <td><a href=\"www.google.com\">Carlos</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">4</td>
                <td><a href=\"www.google.com\">Moises</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">5</td>
                <td><a href=\"www.google.com\">Wenseslao</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <!--tr class=\"item\">
                <td style=\"text-align:center;\">6</td>
                <td><a href=\"www.google.com\">Patricia</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">7</td>
                <td><a href=\"www.google.com\">Jocelyn</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">8</td>
                <td><a href=\"www.google.com\">Ana</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">9</td>
                <td><a href=\"www.google.com\">María</a></td>
                <td class=\"right\">1,441</td>
            </tr>
            <tr class=\"item\">
                <td style=\"text-align:center;\">10</td>
                <td><a href=\"www.google.com\">Virginia</a></td>
                <td class=\"right\">1,441</td>
            </tr-->
        </table>";
    }
}

