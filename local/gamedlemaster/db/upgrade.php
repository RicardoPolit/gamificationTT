<?php



	   function xmldb_local_gamedlemaster_upgrade($oldversion,$block) {
			global $DB;
			$dbman = $DB->get_manager();

			/// Add a new column newcol to the mdl_myqtype_options
			  if ($oldversion < 2019033100) {

				// Rename field exp_actual_de_nivel on table gmdl_usuario to experiencia_actual.
					$table = new xmldb_table('gmdl_usuario');
					$field = new xmldb_field('exp_actual_de_nivel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'nivel_actual');
					// Launch rename field experiencia_actual.
					$dbman->rename_field($table, $field, 'experiencia_actual');

				
				
				 // Changing type of field habilitado on table gmdl_curso to int.
					$table = new xmldb_table('gmdl_curso');
					$field = new xmldb_field('habilitado', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'mdl_id_curso');
					// Launch change of type for field habilitado.
					$dbman->change_field_type($table, $field);


				// Define key mdl_id_seccion_curso (unique) to be dropped form gmdl_seccion_curso.
					$table = new xmldb_table('gmdl_seccion_curso');
					$key = new xmldb_key('mdl_id_seccion_curso', XMLDB_KEY_UNIQUE, array('mdl_id_seccion_curso'));
					// Launch drop key mdl_id_seccion_curso.
					$dbman->drop_key($table, $key);
					
					
				// Define index mdl_id_seccion_curso (unique) to be added to gmdl_seccion_curso.
					$table = new xmldb_table('gmdl_seccion_curso');
					$index = new xmldb_index('mdl_id_seccion_curso', XMLDB_INDEX_UNIQUE, array('mdl_id_seccion_curso'));
					// Conditionally launch add index mdl_id_seccion_curso.
					if (!$dbman->index_exists($table, $index)) {
						$dbman->add_index($table, $index);
					}	
					
				// Define key mdl_id_actividad_seccion (unique) to be dropped form gmdl_actividad_seccion.
					$table = new xmldb_table('gmdl_actividad_seccion');
					$key = new xmldb_key('mdl_id_actividad_seccion', XMLDB_KEY_UNIQUE, array('mdl_id_actividad_seccion'));
					// Launch drop key mdl_id_actividad_seccion.
					$dbman->drop_key($table, $key);
				
				 // Define index mdl_id_actividad_seccion (unique) to be added to gmdl_actividad_seccion.
					$table = new xmldb_table('gmdl_actividad_seccion');
					$index = new xmldb_index('mdl_id_actividad_seccion', XMLDB_INDEX_UNIQUE, array('mdl_id_actividad_seccion'));
					// Conditionally launch add index mdl_id_actividad_seccion.
					if (!$dbman->index_exists($table, $index)) {
						$dbman->add_index($table, $index);
					}
					
				// Define index gmdl_id_usuario-mdl_id_categoria_curso (unique) to be added to gmdl_nivel_categoria_curso.
					$table = new xmldb_table('gmdl_nivel_categoria_curso');
					$index = new xmldb_index('gmdl_id_usuario-mdl_id_categoria_curso', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'mdl_id_categoria_curso'));
					// Conditionally launch add index gmdl_id_usuario-mdl_id_categoria_curso.
					if (!$dbman->index_exists($table, $index)) {
						$dbman->add_index($table, $index);
					}
				
				 // Changing type of field atributo on table gmdl_condicion to char.
					$table = new xmldb_table('gmdl_condicion');
					$field = new xmldb_field('atributo', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null, 'tabla');
					// Launch change of type for field atributo.
					$dbman->change_field_type($table, $field);
					
				
				// Changing type of field valor on table gmdl_condicion to char.
					$table = new xmldb_table('gmdl_condicion');
					$field = new xmldb_field('valor', XMLDB_TYPE_CHAR, '45', null, XMLDB_NOTNULL, null, null, 'expresion');
					// Launch change of type for field valor.
					$dbman->change_field_type($table, $field);
				
				// Define table gmdl_componente to be created.
					$table = new xmldb_table('gmdl_componente');
					// Adding fields to table gmdl_componente.
					$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
					$table->add_field('nombre', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
					$table->add_field('activo', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
					// Adding keys to table gmdl_componente.
					$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
					// Adding indexes to table gmdl_componente.
					$table->add_index('nombre', XMLDB_INDEX_UNIQUE, array('nombre'));
					// Conditionally launch create table for gmdl_componente.
					if (!$dbman->table_exists($table)) {
						$dbman->create_table($table);
					}
				
				// Define field gmdl_id_componente to be added to gmdl_logro.
					$table = new xmldb_table('gmdl_logro');
					$field = new xmldb_field('gmdl_id_componente', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
					// Conditionally launch add field gmdl_id_componente.
					if (!$dbman->field_exists($table, $field)) {
						$dbman->add_field($table, $field);
					}
				
				// Define key gmdl_id_componente (foreign) to be added to gmdl_logro.
					$table = new xmldb_table('gmdl_logro');
					$key = new xmldb_key('gmdl_id_componente', XMLDB_KEY_FOREIGN, array('gmdl_id_componente'), 'gmdl_componente', array('id'));
					// Launch add key gmdl_id_componente.
					$dbman->add_key($table, $key);
					
				// Define table gmdl_evento to be created.
					$table = new xmldb_table('gmdl_evento');
					// Adding fields to table gmdl_evento.
					$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
					$table->add_field('nombre_evento', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null);
					// Adding keys to table gmdl_evento.
					$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
					// Conditionally launch create table for gmdl_evento.
					if (!$dbman->table_exists($table)) {
						$dbman->create_table($table);
					}
				
				// Define field nombre_evento to be dropped from gmdl_evento_logro.
					$table = new xmldb_table('gmdl_evento_logro');
					$field = new xmldb_field('nombre_evento');
					// Conditionally launch drop field nombre_evento.
					if ($dbman->field_exists($table, $field)) {
						$dbman->drop_field($table, $field);
					}
				
				// Define field gmdl_id_evento to be added to gmdl_evento_logro.
					$table = new xmldb_table('gmdl_evento_logro');
					$field = new xmldb_field('gmdl_id_evento', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdl_id_logro');
					// Conditionally launch add field gmdl_id_evento.
					if (!$dbman->field_exists($table, $field)) {
						$dbman->add_field($table, $field);
					}
				
				
				// Define key gmdl_id_evento (foreign) to be added to gmdl_evento_logro.
					$table = new xmldb_table('gmdl_evento_logro');
					$key = new xmldb_key('gmdl_id_evento', XMLDB_KEY_FOREIGN, array('gmdl_id_evento'), 'gmdl_evento', array('id'));
					// Launch add key gmdl_id_evento.
					$dbman->add_key($table, $key);
				
				
				// Define index gmdl_id_evento-gmdl_id_logro (unique) to be added to gmdl_evento_logro.
					$table = new xmldb_table('gmdl_evento_logro');
					$index = new xmldb_index('gmdl_id_evento-gmdl_id_logro', XMLDB_INDEX_UNIQUE, array('gmdl_id_evento', 'gmdl_id_logro'));
					// Conditionally launch add index gmdl_id_evento-gmdl_id_logro.
					if (!$dbman->index_exists($table, $index)) {
						$dbman->add_index($table, $index);
					}
				
				// Define table gmdl_condiciones_logro to be renamed to gmdl_condicion_logro.
					$table = new xmldb_table('gmdl_condiciones_logro');
					// Launch rename table for gmdl_condiciones_logro.
					$dbman->rename_table($table, 'gmdl_condicion_logro');
					
					
				// Changing type of field descripcion on table gmdl_configuracion to char.
					$table = new xmldb_table('gmdl_configuracion');
					$field = new xmldb_field('descripcion', XMLDB_TYPE_CHAR, '250', null, XMLDB_NOTNULL, null, null, 'valor');
					// Launch change of type for field descripcion.
					$dbman->change_field_type($table, $field);
				
				
				// Gamedlemaster savepoint reached.
				upgrade_plugin_savepoint(true, 2019033100, 'local', 'gamedlemaster');
			}

			return true;
		}



