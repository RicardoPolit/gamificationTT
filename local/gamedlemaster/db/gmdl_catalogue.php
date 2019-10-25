<?php


	
	function guardarCatalogosDificultadCPU2019101501()
		{
			global $DB;
	        
			$data = new stdClass(); 
			$data->nombre = 'Fácil';
	        $DB->insert_record('gmdl_dificultad_cpu', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Normal';
	        $DB->insert_record('gmdl_dificultad_cpu', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Difícil';
	        $DB->insert_record('gmdl_dificultad_cpu', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Imposible';
	        $DB->insert_record('gmdl_dificultad_cpu', $data, $returnid=true, $bulk=false);

		}



?>