<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module gmcompcpu
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the gmcompcpu specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_gmcompcpu
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function gmcompcpu_supports($feature) {
    switch($feature) {
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_COMPLETION_HAS_RULES:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_USES_QUESTIONS:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the gmcompcpu into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $gmcompcpu An object from the form in mod_form.php
 * @param mod_gmcompcpu_mod_form $mform The add instance form
 * @return int The id of the newly inserted gmcompcpu record
 */
function gmcompcpu_add_instance(stdClass $gmcompcpu, mod_gmcompcpu_mod_form $mform = null) {
    global $DB;

    $gmcompcpu->timecreated = time();

    // TODO: Highscores.

    return $DB->insert_record('gmcompcpu', $gmcompcpu);
}

/**
 * Updates an instance of the gmcompcpu in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $gmcompcpu An object from the form in mod_form.php
 * @param mod_gmcompcpu_mod_form $mform
 * @return boolean Success/Fail
 */
function gmcompcpu_update_instance(stdClass $gmcompcpu, mod_gmcompcpu_mod_form $mform = null) {
    global $DB;

    $gmcompcpu->timemodified = time();
    $gmcompcpu->id = $gmcompcpu->instance;

    return $DB->update_record('gmcompcpu', $gmcompcpu);
}

/**
 * Removes an instance of the gmcompcpu from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function gmcompcpu_delete_instance($id) {
    global $DB;

    $DB->delete_records('gmcompcpu', array('id' => $id));
    $intentos = $DB->get_records('gmdl_intento',array('gmdlcompcpu_id' => $id));
    $DB->delete_records('gmdl_intento', array('gmdlcompcpu_id' => $id));

    foreach ( $intentos as $intento ){
        $DB->delete_records('gmdl_respuesta_cpu', array('gmdl_intento_id' => $intento->id));
    }
    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record.
 * @param stdClass $user The user record.
 * @param cm_info|stdClass $mod The course module info object or record.
 * @param stdClass $gmcompcpu The gmcompcpu instance record.
 * @return stdclass|null
 */
function gmcompcpu_user_outline($course, $user, $mod, $gmcompcpu) {

    $result = new stdClass();
    $result->info = get_string("notyetplayed", "gmpregdiarias");
    return  $result;

}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $gmcompcpu the module instance record
 * @return void, is supposed to echo directly
 */
function gmcompcpu_user_complete($course, $user, $mod, $gmcompcpu) {
    global $DB;

   /* if ($games = $DB->get_records('gmcompcpu_scores',
            array('gmcompcpuid' => $gmcompcpu->id, 'userid' => $user->id),
            'timecreated ASC')) {
        $attempt = 1;
        foreach ($games as $game) {

            echo get_string('attempt', 'gmcompcpu', $attempt++) . ': ';
            echo get_string('achievedhighscoreof', 'gmcompcpu', $game->score);
            echo ' - '.userdate($game->timecreated).'<br />';
        }
    } else {*/
        print_string("notyetplayed", "gmcompcpu");
   /* }*/

}

/**
 * Obtains the automatic completion state for this gmcompcpu based on any conditions
 * in gmcompcpu settings.
 *
 * @param object $course Course
 * @param object $cm Course-module
 * @param int $userid User ID
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool True if completed, false if not. (If no conditions, then return
 *   value depends on comparison type)
 */
function gmcompcpu_get_completion_state($course, $cm, $userid, $type) {
    global $DB;

    // Get gmcompcpu details.
    if (!($gmcompcpu = $DB->get_record('gmcompcpu', array('id' => $cm->instance)))) {
        throw new Exception("Can't find gmcompcpu {$cm->instance}");
    }

    // Default return value.
    $result = $type;
    if ($gmcompcpu->completioncpudiff) {
        $gmuserid = $DB->get_record('gmdl_usuario',array('mdl_id_usuario' => $userid));
        $where = ' gmdlcompcpu_id = :gmcompcpuid AND gmdl_usuario_id = :gmuserid AND gmdl_dificultad_cpu_id >= :cpudificultad AND puntuacion_usuario >= puntuacion_cpu';
        $params = array(
            'gmcompcpuid' => $gmcompcpu->id,
            'gmuserid' => $gmuserid->id,
            'cpudificultad' => $gmcompcpu->completioncpudiff,
        );
        $value = $DB->count_records_select('gmdl_intento', $where, $params) > 0;
        if ($type == COMPLETION_AND) {
            $result = $result && $value;
        } else {
            $result = $result || $value;
        }
    }

    return $result;
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in gmcompcpu activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @param stdClass $course The course record.
 * @param bool $viewfullnames boolean to determine whether to show full names or not
 * @param int $timestart the time the rendering started
 * @return boolean True if the activity was printed, false otherwise
 */
function gmcompcpu_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  // True if anything was printed, otherwise false.
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link gmcompcpu_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function gmcompcpu_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by gmcompcpu_get_recent_mod_activity
 *
 * @see gmcompcpu_get_recent_mod_activity()
 *
 * @param object $activity The activity object of the gmcompcpu
 * @param int $courseid The id of the course the gmcompcpu resides in
 * @param bool $detail not used, but required for compatibilty with other modules
 * @param int $modnames not used, but required for compatibilty with other modules
 * @param bool $viewfullnames boolean to determine whether to show full names or not
 * @return void
 */
function gmcompcpu_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function gmcompcpu_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * e.g. array('moodle/site:accessallgroups');
 * @return array of capabilities used in the module
 */
function gmcompcpu_get_extra_capabilities() {
    return array();
}

// Gradebook API.
// TODO: Highscore entries in gradebook.

/**
 * Is a given scale used by the instance of gmcompcpu?
 *
 * This function returns if a scale is being used by one gmcompcpu
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $gmcompcpuid ID of an instance of this module
 * @param int $scaleid ID of the scale
 * @return bool true if the scale is used by the given gmcompcpu instance
 */
function gmcompcpu_scale_used($gmcompcpuid, $scaleid) {
    global $DB;

    if ($scaleid and $DB->record_exists('gmcompcpu', array('id' => $gmcompcpuid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of gmcompcpu.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid The id of the scale
 * @return boolean true if the scale is used by any gmcompcpu instance
 */
function gmcompcpu_scale_used_anywhere($scaleid) {

    return false;
}

/**
 * Creates or updates grade item for the give gmcompcpu instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $gmcompcpu instance object with extra cmidnumber and modname property
 * @param mixed $grades optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return void
 */
function gmcompcpu_grade_item_update(stdClass $gmcompcpu, $grades=null) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $item = array();
    $item['itemname'] = clean_param($gmcompcpu->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $gmcompcpu->grade;
    $item['grademin']  = 0;

    grade_update('mod/gmcompcpu', $gmcompcpu->course, 'mod', 'gmcompcpu', $gmcompcpu->id, 0, null, $item);
}

/**
 * Update gmcompcpu grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $gmcompcpu instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function gmcompcpu_update_grades(stdClass $gmcompcpu, $userid = 0) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $grades = array(); // Populate array of grade objects indexed by userid.

    grade_update('mod/gmcompcpu', $gmcompcpu->course, 'mod', 'gmcompcpu', $gmcompcpu->id, 0, $grades);
}

// File API.

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function gmcompcpu_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for gmcompcpu file areas
 *
 * @package mod_gmcompcpu
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function gmcompcpu_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the gmcompcpu file areas
 *
 * @package mod_gmcompcpu
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the gmcompcpu's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function gmcompcpu_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

// Navigation API.

/**
 * Extends the global navigation tree by adding gmcompcpu nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the gmcompcpu module instance
 * @param stdclass $course The course in which navigation is currently being extended
 * @param stdclass $module The module in which navigation is currently being extended
 * @param cm_info $cm The course module info
 * @return void
 */
function gmcompcpu_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the gmcompcpu settings
 *
 * This function is called when the context for the page is a gmcompcpu module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $gmcompcpunode {@link navigation_node}
 */
function gmcompcpu_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $gmcompcpunode=null) {
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the gmcompcpu.
 * @param stdClass $mform form passed by reference
 */
function gmcompcpu_reset_course_form_definition(&$mform) {

    $mform->addElement('header', 'gmcompcpuheader', get_string('modulenameplural', 'gmcompcpu'));
    $mform->addElement('advcheckbox', 'reset_gmcompcpu_scores', get_string('removescores', 'gmcompcpu'));

}

/**
 * Course reset form defaults.
 * @param stdClass $course
 * @return array
 */
function gmcompcpu_reset_course_form_defaults($course) {
    return array('reset_gmcompcpu_scores' => 1);

}

/**
 * Actual implementation of the rest coures functionality, delete all the
 * gmcompcpu responses for course $data->courseid.
 *
 * @param stdClass $data the data submitted from the reset course.
 * @return array status array
 */
function gmcompcpu_reset_userdata($data) {
    global $DB;
        $componentstr = get_string('modulenameplural', 'gmcompcpu');
        $status = array();

    if (!empty($data->reset_gmcompcpu_scores)) {
        $scoresql = "SELECT qg.id
                     FROM {gmcompcpu} qg
                     WHERE qg.course=?";

        $DB->delete_records_select('gmcompcpu_scores', "gmcompcpuid IN ($scoresql)", array($data->courseid));
        $status[] = array('component' => $componentstr, 'item' => get_string('removescores', 'gmcompcpu'), 'error' => false);
    }

    return $status;
}

/**
 * Removes all grades from gradebook
 *
 * @param int $courseid
 * @param string $type (Optional)
 */
function gmcompcpu_reset_gradebook($courseid, $type='') {
    // TODO: LOOK AT AFTER GRADES ARE IMPLEMENTED!
    global $DB;

    $sql = "SELECT g.*, cm.idnumber as cmidnumber, g.course as courseid
              FROM {gmcompcpu} g, {course_modules} cm, {modules} m
             WHERE m.name='gmcompcpu' AND m.id=cm.module AND cm.instance=g.id AND g.course=?";

    if ($gmcompcpus = $DB->get_records_sql($sql, array($courseid))) {
        foreach ($gmcompcpus as $gmcompcpu) {
            gmcompcpu_grade_item_update($gmcompcpu, 'reset');
        }
    }
}

/**
 * This function receives a calendar event and returns the action associated with it, or null if there is none.
 *
 * This is used by block_myoverview in order to display the event appropriately. If null is returned then the event
 * is not displayed on the block.
 *
 * @param calendar_event $event
 * @param \core_calendar\action_factory $factory
 * @param int $userid User id to use for all capability checks, etc. Set to 0 for current user (default).
 * @return \core_calendar\local\event\entities\action_interface|null
 */
function mod_gmcompcpu_core_calendar_provide_event_action(calendar_event $event,
                                                    \core_calendar\action_factory $factory,
                                                    int $userid = 0) {
    global $USER;
    if (!$userid) {
        $userid = $USER->id;
    }
    $cm = get_fast_modinfo($event->courseid, $userid)->instances['gmcompcpu'][$event->instance];
    if (!$cm->uservisible) {
        // The module is not visible to the user for any reason.
        return null;
    }
    $completion = new \completion_info($cm->get_course());
    $completiondata = $completion->get_data($cm, false, $userid);
    if ($completiondata->completionstate != COMPLETION_INCOMPLETE) {
        return null;
    }
    return $factory->create_instance(
        get_string('view'),
        new \moodle_url('/mod/gmcompcpu/view.php', ['id' => $cm->id]),
        1,
        true
    );
}
