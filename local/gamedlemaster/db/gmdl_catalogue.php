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


	function guardarCatalogosRarezaObjetos()
		{
			global $DB;
	        
			$data = new stdClass(); 
			$data->nombre = 'Común';
			$data->costo_adquisicion = '50';
			$data->posibilidad_obtencion = '60';
	        $DB->insert_record('gmdl_rareza_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Rara';
			$data->costo_adquisicion = '200';
			$data->posibilidad_obtencion = '30';
			$DB->insert_record('gmdl_rareza_objeto', $data, $returnid=true, $bulk=false);
			
			$data->nombre = 'Épica';
			$data->costo_adquisicion = '800';
			$data->posibilidad_obtencion = '8';
			$DB->insert_record('gmdl_rareza_objeto', $data, $returnid=true, $bulk=false);
			
			$data->nombre = 'Legendaria';
			$data->costo_adquisicion = '1200';
			$data->posibilidad_obtencion = '2';
	        $DB->insert_record('gmdl_rareza_objeto', $data, $returnid=true, $bulk=false);
		}

	function guardarCatalogosTipoObjetos()
		{
			global $DB;
	        
			$data = new stdClass(); 
			$data->nombre = 'Imagen de perfil';
	        $DB->insert_record('gmdl_tipo_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Tipo borde de imagen';
	        $DB->insert_record('gmdl_tipo_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Color borde de imagen';
	        $DB->insert_record('gmdl_tipo_objeto', $data, $returnid=true, $bulk=false);

		}

	function guardarCatalogosObjetosImagenes()
		{
			global $DB;
			$data = new stdClass(); 
			
			$data->nombre = 'Espada';
			$data->valor = 'pix/espada.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Castillo';
			$data->valor = 'pix/castillo.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			
			$data->nombre = 'Dragón';
			$data->valor = 'pix/dragon.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);
			
			
			$data->nombre = 'Caballero';
			$data->valor = 'pix/caballero.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Credo de los asesinos';
			$data->valor = 'pix/assassins_creed.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Imperio galáctico';
			$data->valor = 'pix/imperio_galactico.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);



			$data->nombre = 'Trifuerza';
			$data->valor = 'pix/trifuerza.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Vengadores';
			$data->valor = 'pix/vengadores.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Péon Ajedrez';
			$data->valor = 'pix/peon.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Cablallo Ajedrez';
			$data->valor = 'pix/caballo.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);



			$data->nombre = 'Alfil Ajedrez';
			$data->valor = 'pix/alfil.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Rey Ajedrez';
			$data->valor = 'pix/rey.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			


			$data->nombre = 'Albúm';
			$data->valor = 'pix/album.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Villa oculta de la hoja';
			$data->valor = 'pix/naruto.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Gato';
			$data->valor = 'pix/gato.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Guitarra roja';
			$data->valor = 'pix/guitarra_roja.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Guitarra azul';
			$data->valor = 'pix/guitarra_azul.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Auto deportivo';
			$data->valor = 'pix/auto.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Control';
			$data->valor = 'pix/control.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Control Switch';
			$data->valor = 'pix/switch.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Brazo robótico';
			$data->valor = 'pix/robot.png';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


		}


	function guardarCatalogosObjetosTiposBordes()
		{
			global $DB;
			$data = new stdClass(); 
			
			$data->nombre = 'Normal';
			$data->valor = 'gmtienda-objeto-tipo-borde-normal';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Entrecortado';
			$data->valor = 'gmtienda-objeto-tipo-borde-entrecortado';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);


			$data->nombre = 'Punteado';
			$data->valor = 'gmtienda-objeto-tipo-borde-punteado';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Doble';
			$data->valor = 'gmtienda-objeto-tipo-borde-doble';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Relieve';
			$data->valor = 'gmtienda-objeto-tipo-borde-relieve';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

		}

	function guardarCatalogosObjetosColorBordes()
		{
			global $DB;
			$data = new stdClass(); 
			
			$data->nombre = 'Rojo';
			$data->valor = 'gmtienda-objeto-color-borde-rojo';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Azul';
			$data->valor = 'gmtienda-objeto-color-borde-azul';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Amarillo';
			$data->valor = 'gmtienda-objeto-color-borde-amarillo';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 1;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Naranja';
			$data->valor = 'gmtienda-objeto-color-borde-naranja';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Verde';
			$data->valor = 'gmtienda-objeto-color-borde-verde';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 2;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Plateado';
			$data->valor = 'gmtienda-objeto-color-borde-plateado';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 3;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

			$data->nombre = 'Dorado';
			$data->valor = 'gmtienda-objeto-color-borde-dorado';
			$data->gmdl_tipo_objeto_id = 1;
			$data->gmdl_rareza_objeto_id = 4;
	        $DB->insert_record('gmdl_objeto', $data, $returnid=true, $bulk=false);

		}
?>