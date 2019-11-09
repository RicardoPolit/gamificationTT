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
defined('MOODLE_INTERNAL') || die();
//require_once($CFG->dirroot. '/course/format/topics/format.php');

require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/completionlib.php');

    // Horrible backwards compatible parameter aliasing..
    if ($topic = optional_param('topic', 0, PARAM_INT)) {
        $url = $PAGE->url;
        $url->param('section', $topic);
        debugging('Outdated topic param passed to course/view.php', DEBUG_DEVELOPER);
        redirect($url);
    }
    // End backwards-compatible aliasing..

    // Obtaining the context, format and adding course format options
    $context = context_course::instance($course->id);
    $format = course_get_format($course);
    $course = $format->get_course();

    if (($marker >=0) && has_capability('moodle/course:setcurrentsection', $context) && confirm_sesskey()) {
        $course->marker = $marker;
        course_set_marker($course->id, $marker);
    }

    // Make sure section 0 is created.
    course_create_sections_if_missing($course, 0);
    $renderer = $PAGE->get_renderer('format_gamedle');

    if (!empty($displaysection)) {
        $renderer->print_single_section_page($course, null, null, null, null, $displaysection);
    } else {
        $renderer->print_multiple_section_page($course, null, null, null, null);
    }

    // Include course format js module
    $PAGE->requires->js('/course/format/topics/format.js');
    
    /**
     * STARTS GAMEDLE MODIFICATIONS
     */
     
    if ($PAGE->user_is_editing() && $format->get_format()==="gamedle") {
        $_SESSION['Gamedle']['format'] = $course->id;
        $totalxp = (int)get_config('block_gmxp', block_gmxp_core::COURSEXP);
        $service = "{$CFG->wwwroot}/course/format/gamedle/cli/courseXP.php";

        $PAGE->requires->js_call_amd('format_gamedle/form_xp','form',
            array($course->id, $totalxp, $service));

    } else
        $_SESSION['Gamedle']['format'] = false;
    
