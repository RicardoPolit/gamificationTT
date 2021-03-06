<?php

    require_once('gmdl_catalogue.php');


function xmldb_local_gamedlemaster_upgrade($oldversion)
	{

		if ($oldversion < 2019033100)
			{
				upgrades2019033100();
			}
		else if ($oldversion < 2019040300)
			{
				upgrades2019040300();
			}
		else if($oldversion < 2019042100)
			{
				upgrades2019042100();
			}

		else if($oldversion < 2019042101)
			{
				upgrades2019042101();
			}
		else if($oldversion < 2019050800)
			{
				upgrades2019050800();
			}
		else if ($oldversion < 2019100900)
			{
            	upgrades2019100900();
			}
		else if($oldversion < 2019101500)
			{
				upgrades2019101500();
			}
        else if($oldversion < 2019101501)
            {
                guardarCatalogosDificultadCPU2019101501();
                // Gamedlemaster savepoint reached.
                upgrade_plugin_savepoint(true, 2019101501, 'local', 'gamedlemaster');
                upgrades2019102400();
            }
        else if($oldversion < 2019102400)
            {
                upgrades2019102400();
            }
        else if($oldversion < 2019102402)
            {
                upgrades2019102402();
            }
        else if($oldversion < 2019102403)
            {
                upgrades2019102403();
            }
        else if($oldversion < 2019102900)
            {
                upgrades2019102900();
            }
        else if($oldversion < 2019102901)
            {
                upgrades2019102901();
            }
        else if($oldversion < 2019102902)
            {
                upgrades2019102902();
            }
        else if ($oldversion < 2019103103)
            {
                upgrades2019103100($oldversion);
            }
        else if ($oldversion < 2019110200)
            {
                upgrades2019110200();
            }
        else if ($oldversion < 2019110300)
            {
                upgrades2019110300();
            }
        else if($oldversion < 2019110700)
            {
                upgrades2019110700();
            }
        else if($oldversion < 2019110800)
            {
                upgrades2019110800();
            }
        else if($oldversion < 2019111000)
            {
                upgrades2019111000();
            }
        else if ($oldversion < 2019111001)
            {
                upgrades2019111001();
            }
        else if($oldversion < 2019111002)
            {
                guardarCatalogosRarezaObjetos();
                guardarCatalogosTipoObjetos();
                guardarCatalogosObjetosImagenes();
                guardarCatalogosObjetosTiposBordes();
                guardarCatalogosObjetosColorBordes();
            }
		return true;
	}


function upgrades2019033100()
    {

        global $DB;
        $dbman = $DB->get_manager();
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
        upgrades2019040300();
    }

function upgrades2019040300()
    {

        global $DB;
        $dbman = $DB->get_manager();
        // Define table gmdl_configuracion to be dropped.
        $table = new xmldb_table('gmdl_configuracion');
        // Conditionally launch drop table for gmdl_configuracion.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define key gmdl_id_componente (foreign) to be dropped form gmdl_logro.
        $table = new xmldb_table('gmdl_logro');
        $key = new xmldb_key('gmdl_id_componente', XMLDB_KEY_FOREIGN, array('gmdl_id_componente'), 'gmdl_componente', array('id'));
        // Launch drop key gmdl_id_componente.
        $dbman->drop_key($table, $key);

        // Rename field gmdl_id_componente on table gmdl_logro to modulo.
        $table = new xmldb_table('gmdl_logro');
        $field = new xmldb_field('gmdl_id_componente', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
        // Launch rename field gmdl_id_componente.
        $dbman->rename_field($table, $field, 'modulo');

        // Changing type of field modulo on table gmdl_logro to char.
        $table = new xmldb_table('gmdl_logro');
        $field = new xmldb_field('modulo', XMLDB_TYPE_CHAR, '15', null, XMLDB_NOTNULL, null, null, 'id');
        // Launch change of type for field modulo.
        $dbman->change_field_type($table, $field);


        // Define index nombre (unique) to be added to gmdl_logro.
        $table = new xmldb_table('gmdl_logro');
        $index = new xmldb_index('nombre', XMLDB_INDEX_UNIQUE, array('nombre'));
        // Conditionally launch add index nombre.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Define table gmdl_componente to be dropped.
        $table = new xmldb_table('gmdl_componente');

        // Conditionally launch drop table for gmdl_componente.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019040300, 'local', 'gamedlemaster');
        upgrades2019042100();
    }

function upgrades2019042100()
    {


        global $DB;
        $dbman = $DB->get_manager();

        // Define key gmdl_id_curso (foreign) to be dropped form gmdl_alumno.
        $table = new xmldb_table('gmdl_alumno');
        $key = new xmldb_key('gmdl_id_curso', XMLDB_KEY_FOREIGN, array('gmdl_id_curso'), 'gmdl_curso', array('mdl_id_curso'));
        // Launch drop key gmdl_id_curso.
        $dbman->drop_key($table, $key);


        // Define index gmdl_id_usuario-gmdl_id_curso (unique) to be dropped form gmdl_alumno.
        $table = new xmldb_table('gmdl_alumno');
        $index = new xmldb_index('gmdl_id_usuario-gmdl_id_curso', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'gmdl_id_curso'));
        // Conditionally launch drop index gmdl_id_usuario-gmdl_id_curso.
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }


        // Rename field mdl_id_curso on table gmdl_alumno to mdl_id_curso.
        $table = new xmldb_table('gmdl_alumno');
        $field = new xmldb_field('gmdl_id_curso', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdl_id_usuario');
        // Launch rename field mdl_id_curso.
        $dbman->rename_field($table, $field, 'mdl_id_curso');


        // Define key mdl_id_curso (foreign-unique) to be added to gmdl_alumno.
        $table = new xmldb_table('gmdl_alumno');
        $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN_UNIQUE, array('mdl_id_curso'), 'course', array('id'));
        // Launch add key mdl_id_curso.
        $dbman->add_key($table, $key);



        // Define index gmdl_id_usuario-mdl_id_curso (unique) to be added to gmdl_alumno.
        $table = new xmldb_table('gmdl_alumno');
        $index = new xmldb_index('gmdl_id_usuario-mdl_id_curso', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'mdl_id_curso'));
        // Conditionally launch add index gmdl_id_usuario-mdl_id_curso.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }



        // Define key gmdl_id_curso (foreign) to be dropped form gmdl_usuario_logro.
        $table = new xmldb_table('gmdl_usuario_logro');
        $key = new xmldb_key('gmdl_id_curso', XMLDB_KEY_FOREIGN, array('gmdl_id_curso'), 'gmdl_curso', array('mdl_id_curso'));
        // Launch drop key gmdl_id_curso.
        $dbman->drop_key($table, $key);



        // Rename field gmdl_id_curso on table gmdl_usuario_logro to mdl_id_curso.
        $table = new xmldb_table('gmdl_usuario_logro');
        $field = new xmldb_field('gmdl_id_curso', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdl_id_logro');
        // Launch rename field gmdl_id_curso.
        $dbman->rename_field($table, $field, 'mdl_id_curso');


        // Define key mdl_id_curso (foreign-unique) to be added to gmdl_usuario_logro.
        $table = new xmldb_table('gmdl_usuario_logro');
        $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN_UNIQUE, array('mdl_id_curso'), 'course', array('id'));
        // Launch add key mdl_id_curso.
        $dbman->add_key($table, $key);



        // Define field desbloqueado to be dropped from gmdl_usuario_logro.
        $table = new xmldb_table('gmdl_usuario_logro');
        $field = new xmldb_field('desbloqueado');
        // Conditionally launch drop field desbloqueado.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }


        // Define field cuando to be added to gmdl_usuario_logro.
        $table = new xmldb_table('gmdl_usuario_logro');
        $field = new xmldb_field('cuando', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'mdl_id_curso');
        // Conditionally launch add field cuando.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }



        // Define table gmdl_usuario_logro_curso to be renamed to NEWNAMEGOESHERE.
        $table = new xmldb_table('gmdl_usuario_logro');
        // Launch rename table for gmdl_usuario_logro_curso.
        $dbman->rename_table($table, 'gmdl_usuario_logro_curso');


        // Define field tipo to be added to gmdl_logro.
        $table = new xmldb_table('gmdl_logro');
        $field = new xmldb_field('tipo', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, 'A', 'experiencia_de_logro');
        // Conditionally launch add field tipo.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Changing precision of field mensaje on table gmdl_rango_nivel to (50).
        $table = new xmldb_table('gmdl_rango_nivel');
        $field = new xmldb_field('mensaje', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'Eres el mejor, bien hecho!', 'imagen');
        // Launch change of precision for field mensaje.
        $dbman->change_field_precision($table, $field);



        // Define field descripcion to be added to gmdl_rango_nivel.
        $table = new xmldb_table('gmdl_rango_nivel');
        $field = new xmldb_field('descripcion', XMLDB_TYPE_CHAR, '200', null, XMLDB_NOTNULL, null, 'Los niveles son infinitos, o crees poderme demostrar lo contrario?', 'mensaje');
        // Conditionally launch add field descripcion.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Define table gmdl_seccion_curso to be dropped.
        $table = new xmldb_table('gmdl_seccion_curso');
        // Conditionally launch drop table for gmdl_seccion_curso.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table gmdl_seccion_curso to be dropped.
        $table = new xmldb_table('gmdl_actividad_seccion');
        // Conditionally launch drop table for gmdl_seccion_curso.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }


        // Define table gmdl_seccion_curso to be dropped.
        $table = new xmldb_table('gmdl_curso');
        // Conditionally launch drop table for gmdl_seccion_curso.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }



        // Define table gmdl_usuario_logro_global to be created.
        $table = new xmldb_table('gmdl_usuario_logro_global');

        // Adding fields to table gmdl_usuario_logro_global.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_id_usuario', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_id_logro', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cuando', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_usuario_logro_global.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_id_usuario', XMLDB_KEY_FOREIGN, array('gmdl_id_usuario'), 'gmdl_usuario', array('mdl_id_usuario'));
        $table->add_key('gmdl_id_logro', XMLDB_KEY_FOREIGN, array('gmdl_id_logro'), 'gmdl_logro', array('id'));

        // Adding indexes to table gmdl_usuario_logro_global.
        $table->add_index('gmdl_id_usuario-gmdl_id_logro', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'gmdl_id_logro'));

        // Conditionally launch create table for gmdl_usuario_logro_global.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019042100, 'local', 'gamedlemaster');
        upgrades2019042101();
    }

function upgrades2019042101()
    {

        global $DB;
        $dbman = $DB->get_manager();
        // Define key mdl_id_curso (foreign-unique) to be dropped form gmdl_alumno.
            $table = new xmldb_table('gmdl_alumno');
            $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN_UNIQUE, array('mdl_id_curso'), 'course', array('id'));
        // Launch drop key mdl_id_curso.
            $dbman->drop_key($table, $key);


        // Define key mdl_id_curso (foreign) to be added to gmdl_alumno.
        $table = new xmldb_table('gmdl_alumno');
        $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN, array('mdl_id_curso'), 'course', array('id'));
        // Launch add key mdl_id_curso.
        $dbman->add_key($table, $key);


        // Define key mdl_id_curso (foreign-unique) to be dropped form gmdl_usuario_logro_curso.
        $table = new xmldb_table('gmdl_usuario_logro_curso');
        $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN_UNIQUE, array('mdl_id_curso'), 'course', array('id'));
        // Launch drop key mdl_id_curso.
        $dbman->drop_key($table, $key);


        // Define key mdl_id_curso (foreign) to be added to gmdl_usuario_logro_curso.
        $table = new xmldb_table('gmdl_usuario_logro_curso');
        $key = new xmldb_key('mdl_id_curso', XMLDB_KEY_FOREIGN, array('mdl_id_curso'), 'course', array('id'));
        // Launch add key mdl_id_curso.
        $dbman->add_key($table, $key);


        // Define index gmdl_id_usuario-gmdl_id_logro-mdl_id_curso (unique) to be added to gmdl_usuario_logro_curso.
        $table = new xmldb_table('gmdl_usuario_logro_curso');
        $index = new xmldb_index('gmdl_id_usuario-gmdl_id_logro-mdl_id_curso', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'gmdl_id_logro', 'mdl_id_curso'));

        // Conditionally launch add index gmdl_id_usuario-gmdl_id_logro-mdl_id_curso.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Changing precision of field nombre_evento on table gmdl_evento to (100).
        $table = new xmldb_table('gmdl_evento');
        $field = new xmldb_field('nombre_evento', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null, 'id');
        // Launch change of precision for field nombre_evento.
        $dbman->change_field_precision($table, $field);


        // Define index nombre_evento (unique) to be added to gmdl_evento.
        $table = new xmldb_table('gmdl_evento');
        $index = new xmldb_index('nombre_evento', XMLDB_INDEX_UNIQUE, array('nombre_evento'));
        // Conditionally launch add index nombre_evento.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }


        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019042101, 'local', 'gamedlemaster');
        upgrades2019050800();
    }



function upgrades2019050800()
    {
        global $DB;
        $dbman = $DB->get_manager();

        // Define table gmdl_alumno to be dropped.
        $table = new xmldb_table('gmdl_alumno');
        // Conditionally launch drop table for gmdl_alumno.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }


        // Define table gmdl_seccion_curso to be created.
        $table = new xmldb_table('gmdl_seccion_curso');
        // Adding fields to table gmdl_seccion_curso.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('mdl_id_seccion_curso', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('experiencia_de_seccion', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        // Adding keys to table gmdl_seccion_curso.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('mdl_id_seccion_curso', XMLDB_KEY_UNIQUE, array('mdl_id_seccion_curso'));
        // Conditionally launch create table for gmdl_seccion_curso.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table gmdl_recompensas_seccion to be created.
        $table = new xmldb_table('gmdl_recompensas_seccion');

        // Adding fields to table gmdl_recompensas_seccion.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_id_usuario', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_id_seccion_curso', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('experiencia_dada', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table gmdl_recompensas_seccion.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_id_usuario', XMLDB_KEY_FOREIGN, array('gmdl_id_usuario'), 'gmdl_usuario', array('mdl_id_usuario'));
        $table->add_key('gmdl_id_seccion_curso', XMLDB_KEY_FOREIGN, array('gmdl_id_seccion_curso'), 'gmdl_seccion_curso', array('mdl_id_seccion_curso'));

        // Adding indexes to table gmdl_recompensas_seccion.
        $table->add_index('gmd_id_usuario-gmdl_id_seccion_curso', XMLDB_INDEX_UNIQUE, array('gmdl_id_usuario', 'gmdl_id_seccion_curso'));

        // Conditionally launch create table for gmdl_recompensas_seccion.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }



        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019050800, 'local', 'gamedlemaster');
        upgrades2019100900();

    }


function upgrades2019100900()
    {
        global $DB;
        $dbman = $DB->get_manager();

        // Define table gmdl_com_cpu to be created.
        $table = new xmldb_table('gmdl_com_cpu');

        // Adding fields to table gmdl_com_cpu.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('intro', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('introformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mdl_question_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_com_cpu.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('mdl_question_id', XMLDB_KEY_FOREIGN, array('mdl_question_id'), 'question', array('id'));
        $table->add_key('mdl_course_id', XMLDB_KEY_FOREIGN, array('course'), 'course', array('id'));

        // Conditionally launch create table for gmdl_com_cpu.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table gmdl_dificultad_cpu to be created.
        $table = new xmldb_table('gmdl_dificultad_cpu');

        // Adding fields to table gmdl_dificultad_cpu.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('nombre', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_dificultad_cpu.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for gmdl_dificultad_cpu.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

         // Define table gmdl_intento to be created.
        $table = new xmldb_table('gmdl_intento');

        // Adding fields to table gmdl_intento.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_dificultad_cpu_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_com_cpu_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('puntuacion_cpu', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('puntuacion_usuario', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('fecha_inicio', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fecha_fin', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table gmdl_intento.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_dificultad_cpu_id', XMLDB_KEY_FOREIGN, array('gmdl_dificultad_cpu_id'), 'gmdl_dificultad_cpu', array('id'));
        $table->add_key('gmdl_com_cpu_id', XMLDB_KEY_FOREIGN, array('gmdl_com_cpu_id'), 'gmdl_com_cpu', array('id'));
        $table->add_key('gmdl_usuario_id', XMLDB_KEY_FOREIGN, array('gmdl_usuario_id'), 'gmdl_usuario', array('id'));

        // Conditionally launch create table for gmdl_intento.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table gmdl_respuesta_cpu to be created.
        $table = new xmldb_table('gmdl_respuesta_cpu');

        // Adding fields to table gmdl_respuesta_cpu.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('mdl_question_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mdl_question_answers_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_intento_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_respuesta_cpu.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_intento_id', XMLDB_KEY_FOREIGN, array('gmdl_intento_id'), 'gmdl_intento', array('id'));
        $table->add_key('mdl_question_answers_id', XMLDB_KEY_FOREIGN, array('mdl_question_answers_id'), 'question_answers', array('id'));
        $table->add_key('mdl_question_id', XMLDB_KEY_FOREIGN, array('mdl_question_id'), 'question', array('id'));

        // Conditionally launch create table for gmdl_respuesta_cpu.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019100900, 'local', 'gamedlemaster');
        upgrades2019101500();

    }



function upgrades2019101500()
    {

        global $DB;
        $dbman = $DB->get_manager();

        // Define key gmdlcomcpu_id (foreign) to be dropped form gmdl_intento.
        $table = new xmldb_table('gmdl_intento');
        $key = new xmldb_key('gmdl_com_cpu_id', XMLDB_KEY_FOREIGN, array('gmdl_com_cpu_id'), 'gmdl_com_cpu', array('id'));

        // Launch drop key gmdlcomcpu_id.
        $dbman->drop_key($table, $key);



        // Define table gmdl_com_cpu to be renamed to gmdlcompcpu.
        $table = new xmldb_table('gmdl_com_cpu');

        // Launch rename table for gmdlcompcpu.
        $dbman->rename_table($table, 'gmdlcompcpu');


        // Rename field gmdlcomcpu_id on table gmdl_intento to gmdlcompcpu_id.
        $table = new xmldb_table('gmdl_intento');
        $field = new xmldb_field('gmdl_com_cpu_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdl_dificultad_cpu_id');

        // Launch rename field gmdlcomcpu_id.
        $dbman->rename_field($table, $field, 'gmdlcompcpu_id');


        // Define key gmdlcomcpu_id (foreign) to be added to gmdl_intento.
        $table = new xmldb_table('gmdl_intento');
        $key = new xmldb_key('gmdlcompcpu_id', XMLDB_KEY_FOREIGN, array('gmdlcompcpu_id'), 'gmdlcomcpu', array('id'));

        // Launch add key gmdlcomcpu_id.
        $dbman->add_key($table, $key);



        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019101500, 'local', 'gamedlemaster');
        upgrades2019102400();

    }



function upgrades2019102400()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Rename field gmdl_usuario_id on table gmdl_intento to mdl_usuario_id.
        $table = new xmldb_table('gmdl_intento');
        $field = new xmldb_field('gmdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdlcomcpu_id');

        // Launch rename field gmdl_usuario_id.
        $dbman->rename_field($table, $field, 'mdl_usuario_id');

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019102400, 'local', 'gamedlemaster');
        upgrades2019102402();
    }

// El renombramiento es algo que se tiene que hablar con los directores, para saber cómo se usan estas llaves

function upgrades2019102402()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Rename field gmdl_usuario_id on table gmdl_intento to mdl_usuario_id.
        $table = new xmldb_table('gmdl_intento');
        $field = new xmldb_field('mdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'gmdlcomcpu_id');

        // Launch rename field gmdl_usuario_id.
        $dbman->rename_field($table, $field, 'gmdl_usuario_id');

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019102402, 'local', 'gamedlemaster');
        upgrades2019102403();
    }

function upgrades2019102403(){ // Dan: Required extra field on user
    global $DB;
    $dbman = $DB->get_manager();

    $table = new xmldb_table('gmdl_usuario');
    $field = new xmldb_field('experiencia_nivel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
    $dbman->add_field($table, $field);
    upgrade_plugin_savepoint(true, 2019102403, 'local', 'gamedlemaster');
    upgrades2019102900();
}


function upgrades2019102900()
    {

        global $DB;
        $dbman = $DB->get_manager();
        // Define table gmcompcpu to be renamed to gmcompcpu.
        $table = new xmldb_table('gmdlcompcpu');

        // Launch rename table for gmcompcpu.
        $dbman->rename_table($table, 'gmcompcpu');

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019102900, 'local', 'gamedlemaster');
        upgrades2019102901();
    }


function upgrades2019102901()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Changing the default of field introformat on table gmcompcpu to 0.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'intro');

        // Launch change of default for field introformat.
        $dbman->change_field_default($table, $field);


        // Changing the default of field timemodified on table gmcompcpu to 0.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'name');

        // Launch change of default for field timemodified.
        $dbman->change_field_default($table, $field);

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019102901, 'local', 'gamedlemaster');
        upgrades2019102902();
    }


function upgrades2019102902()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Define key mdl_question_id (foreign) to be dropped form gmcompcpu.
        $table = new xmldb_table('gmcompcpu');
        $key = new xmldb_key('mdl_question_id', XMLDB_KEY_FOREIGN, array('mdl_question_id'), 'question', array('id'));

        // Launch drop key mdl_question_id.
        $dbman->drop_key($table, $key);
        // Rename field mdl_question_id on table gmcompcpu to mdl_question_category_id.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('mdl_question_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'introformat');

        // Launch rename field mdl_question_id.
        $dbman->rename_field($table, $field, 'mdl_question_category_id');
        // Changing type of field mdl_question_category_id on table gmcompcpu to text.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('mdl_question_category_id', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'introformat');

        // Launch change of type for field mdl_question_category_id.
        $dbman->change_field_type($table, $field);


        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019102902, 'local', 'gamedlemaster');
        upgrades2019103100();
    }


function upgrades2019103100()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Define table gmcompvs to be created.
        $table = new xmldb_table('gmcompvs');

        // Adding fields to table gmcompvs.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('introformar', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('mdl_question_categories_id', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmcompvs.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        
        // Conditionally launch create table for gmcompvs.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table gmdl_partida to be created.
        $table = new xmldb_table('gmdl_partida');

        // Adding fields to table gmdl_partida.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_comp_vs_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_partida.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_comp_vs_id', XMLDB_KEY_FOREIGN, array('gmdl_comp_vs_id'), 'gmcompvs', array('id'));

        // Conditionally launch create table for gmdl_partida.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table gmdl_participacion to be created.
        $table = new xmldb_table('gmdl_participacion');

        // Adding fields to table gmdl_participacion.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_partida_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fecha_inicio', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fecha_fin', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('puntuacion', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table gmdl_participacion.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_partida_id', XMLDB_KEY_FOREIGN, array('gmdl_partida_id'), 'gmdl_partida', array('id'));
        $table->add_key('gmdl_usuario_id', XMLDB_KEY_FOREIGN, array('gmdl_usuario_id'), 'gmdl_usuario', array('id'));

        // Conditionally launch create table for gmdl_participacion.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Rename field mdl_question_categories_id on table gmcompcpu to mdl_question_categories_id.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('mdl_question_category_id', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'introformat');

        // Launch rename field mdl_question_categories_id.
        $dbman->rename_field($table, $field, 'mdl_question_categories_id');
    

        upgrades2019110200();
    }

function upgrades2019110200()
    {
        global $DB;
        $dbman = $DB->get_manager();
         // Changing nullability of field fecha_inicio on table gmdl_participacion to null. 
         $table = new xmldb_table('gmdl_participacion');
         $field = new xmldb_field('fecha_inicio', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'gmdl_partida_id');
 
         // Launch change of nullability for field fecha_inicio.
         $dbman->change_field_notnull($table, $field);
 
         // Gamedlemaster savepoint reached.
         upgrade_plugin_savepoint(true, 2019110200, 'local', 'gamedlemaster');
         upgrades2019110300();
    }

function upgrades2019110300()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Define table gmpregdiarias to be created.
        $table = new xmldb_table('gmpregdiarias');

        // Adding fields to table gmpregdiarias.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('intro', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('introformar', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('mdl_question_categories_id', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmpregdiarias.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for gmpregdiarias.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

       
        // Define table gmdl_intento_diario to be created.
        $table = new xmldb_table('gmdl_intento_diario');

        // Adding fields to table gmdl_intento_diario.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('mdl_question_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_preg_diarias_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('calificacion', XMLDB_TYPE_NUMBER, '12, 7', null, XMLDB_NOTNULL, null, '0.0');
        $table->add_field('fecha', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table gmdl_intento_diario.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_usuario_id', XMLDB_KEY_FOREIGN, array('gmdl_usuario_id'), 'gmdl_usuario', array('id'));
        $table->add_key('mdl_question_id', XMLDB_KEY_FOREIGN, array('mdl_question_id'), 'mdl_question', array('id'));
        $table->add_key('gmdl_preg_diarias_id', XMLDB_KEY_FOREIGN, array('gmdl_preg_diarias_id'), 'gmpregdiarias', array('id'));

        // Conditionally launch create table for gmdl_intento_diario.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019110300, 'local', 'gamedlemaster');
        upgrades2019110700();
    }

function upgrades2019110700()
    {
        
        global $DB;
        $dbman = $DB->get_manager();
        // Define field completioncpudiff to be added to gmcompcpu.
        $table = new xmldb_table('gmcompcpu');
        $field = new xmldb_field('completioncpudiff', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'mdl_question_categories_id');

        // Conditionally launch add field completioncpudiff.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field completionnumwon to be added to gmcompvs.
        $table = new xmldb_table('gmcompvs');
        $field = new xmldb_field('completionnumwon', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'mdl_question_categories_id');

        // Conditionally launch add field completionnumwon.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019110700, 'local', 'gamedlemaster');
        upgrades2019110800();
    }


function upgrades2019110800()
    {
        global $DB;
        $dbman = $DB->get_manager();

        // Define field experiencia_nivel to be added to gmdl_usuario.
        $table = new xmldb_table('gmdl_usuario');
        $field = new xmldb_field('experiencia_nivel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'experiencia_actual');

        // Conditionally launch add field experiencia_nivel.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field monedas_oro to be added to gmdl_usuario.
        $table = new xmldb_table('gmdl_usuario');
        $field = new xmldb_field('monedas_oro', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1', 'experiencia_nivel');

        // Conditionally launch add field monedas_oro.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field monedas_plata to be added to gmdl_usuario.
        $table = new xmldb_table('gmdl_usuario');
        $field = new xmldb_field('monedas_plata', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '100', 'monedas_oro');

        // Conditionally launch add field monedas_plata.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Define field apuestas_activas to be added to gmcompvs.
        $table = new xmldb_table('gmcompvs');
        $field = new xmldb_field('apuestas_activas', XMLDB_TYPE_BINARY, null, null, null, null, null, 'completionnumwon');

        // Conditionally launch add field apuestas_activas.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


         // Define table gmdl_apuesta to be created.
         $table = new xmldb_table('gmdl_apuesta');

         // Adding fields to table gmdl_apuesta.
         $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
         $table->add_field('gmdl_participacion_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
         $table->add_field('activa', XMLDB_TYPE_BINARY, null, null, null, null, null);
         $table->add_field('monedas_plata', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
 
         // Adding keys to table gmdl_apuesta.
         $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
         $table->add_key('gmdl_participacion_id', XMLDB_KEY_FOREIGN_UNIQUE, array('gmdl_participacion_id'), 'gmdl_participacion', array('id'));
 
         // Conditionally launch create table for gmdl_apuesta.
         if (!$dbman->table_exists($table)) {
             $dbman->create_table($table);
         }
 

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019110800, 'local', 'gamedlemaster');
        upgrades2019111000();
    }


function upgrades2019111000()
    {
        global $DB;
        $dbman = $DB->get_manager();

        // Rename field introformar on table gmpregdiarias to introformat.
        $table = new xmldb_table('gmpregdiarias');
        $field = new xmldb_field('introformar', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'intro');

        // Launch rename field introformar.
        $dbman->rename_field($table, $field, 'introformat');
 


        // Rename field introformar on table gmpregdiarias to introformat.
        $table = new xmldb_table('gmcompvs');
        $field = new xmldb_field('introformar', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0', 'intro');

        // Launch rename field introformar.
        $dbman->rename_field($table, $field, 'introformat');

         // Gamedlemaster savepoint reached.
         upgrade_plugin_savepoint(true, 2019111000, 'local', 'gamedlemaster');
    }
function upgrades2019111001()
    {
        global $DB;
        $dbman = $DB->get_manager();
        // Define table gmdl_tipo_objeto to be created.
        $table = new xmldb_table('gmdl_tipo_objeto');

        // Adding fields to table gmdl_tipo_objeto.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('nombre', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'Tipo objeto');

        // Adding keys to table gmdl_tipo_objeto.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for gmdl_tipo_objeto.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table gmdl_rareza_objeto to be created.
        $table = new xmldb_table('gmdl_rareza_objeto');

        // Adding fields to table gmdl_rareza_objeto.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('nombre', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'Raro');
        $table->add_field('costo_adquisicion', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1000');
        $table->add_field('posibilidad_de_desbloqueo', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '30');

        // Adding keys to table gmdl_rareza_objeto.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for gmdl_rareza_objeto.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table gmdl_objeto to be created.
        $table = new xmldb_table('gmdl_objeto');

        // Adding fields to table gmdl_objeto.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('nombre', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, 'Un objeto');
        $table->add_field('valor', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_tipo_objeto_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('gmdl_rareza_objeto_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1');

        // Adding keys to table gmdl_objeto.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_tipo_objeto_id', XMLDB_KEY_FOREIGN, array('gmdl_tipo_objeto_id'), 'gmdl_tipo_objeto', array('id'));
        $table->add_key('gmdl_rareza_objeto_id', XMLDB_KEY_FOREIGN, array('gmdl_rareza_objeto_id'), 'gmdl_rareza_objeto', array('id'));

        // Conditionally launch create table for gmdl_objeto.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table gmdl_objeto_desbloqueado to be created.
        $table = new xmldb_table('gmdl_objeto_desbloqueado');

        // Adding fields to table gmdl_objeto_desbloqueado.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('gmdl_objeto_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('gmdl_usuario_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('elegido', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table gmdl_objeto_desbloqueado.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('gmdl_usuario_id', XMLDB_KEY_FOREIGN, array('gmdl_usuario_id'), 'gmdl_usuario', array('id'));
        $table->add_key('gmdl_objeto_id', XMLDB_KEY_FOREIGN, array('gmdl_objeto_id'), 'gmdl_objeto', array('id'));

        // Conditionally launch create table for gmdl_objeto_desbloqueado.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gamedlemaster savepoint reached.
        upgrade_plugin_savepoint(true, 2019111001, 'local', 'gamedlemaster');
    }