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

/**
 * Serve the files from the MYPLUGIN file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */

// Used for myprofile callback
require_once("{$CFG->dirroot}/blocks/moodleblock.class.php");
require_once("{$CFG->dirroot}/blocks/gmxp/block_gmxp.php");

function block_gmxp_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {

    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if( $filearea !== "level_images_simplehtml" ){
        return false;
    }

    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    // require_login($course, true, $cm);

    /* TODO: Check Capabilities !!!

        // Check the relevant capabilities - these may vary depending on the filearea being accessed.
        if (!has_capability('block/gmxp:view', $context)) {
            return false;
        }
    */
    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    //$itemid = array_shift($args); // The first item in the $args array.
    $itemid = 0;

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file(1, 'block_gmxp', $filearea, $itemid, "/", $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}

/*function block_gmxp_myprofile_navigation(core_user\output\myprofile\tree $tree,
  $user, $iscurrentuser, $course){

    $PLUGIN = "block_gmxp";
    $level = 3;

    $node_title = get_string('gmxp', $PLUGIN).' '.$level;
    $node_content = block_gmxp::htmlProgressBar($level,false);

    $node = new core_user\output\myprofile\node( //block_gmxp_core::CATEGORY
        'contact', block_gmxp_core::PERFIL_EXPERIENCE,
        $node_title, null, null, $node_content);

    $tree->add_node($node);
}*/

function block_gmxp_extend_navigation($node, $course, $module, $cm) {
    local_gamedlemaster_log::info($node);
    local_gamedlemaster_log::info($course);
    local_gamedlemaster_log::info($module);
    local_gamedlemaster_log::info($cm);
}

?>
