<?php



	   function xmldb_local_gamedlemaster_upgrade($oldversion,$block) {
			global $DB;
			$dbman = $DB->get_manager();

			/// Add a new column newcol to the mdl_myqtype_options
			if ($oldversion < 2019032700) {
				

			}

			return true;
		}



