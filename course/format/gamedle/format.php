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

    $context = context_course::instance($course->id);
    // Retrieve course format option fields and add them to the $course object.
    $course = course_get_format($course)->get_course();

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
    
    $object = array(
        "msg1" => "P1",
        "msg2" => "Pasando objeto como parametro",
    );
    
    $PAGE->requires->js_call_amd('format_gamedle/experienceCourse', 'funcionA', array($object));
    $PAGE->requires->js_call_amd('format_gamedle/experienceCourse', 'funcionB', array("P2","Pasando parametros como array"));
    
    
    
    
