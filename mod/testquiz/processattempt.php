<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');

$id  = optional_param('id', "", PARAM_INT);  // Is the param action.

$timenow = time();
$transaction = $DB->start_delegated_transaction();

$quba = question_engine::load_questions_usage_by_activity($id);
$quba->process_all_actions($timenow);
question_engine::save_questions_usage_by_activity($quba);

/*$attempt = $quba->get_attempt_iterator();

if ($attempt->timefinish) {
    $attempt->sumgrades = $quba->get_total_mark();
}
$attempt->timemodified = $timenow;
$DB->update_record('quiz_attempts', $attempt);*/

$transaction->allow_commit();

$qubaidscondition = new qubaid_list(array($id));

$dm = new question_engine_data_mapper();
$latesstepdata = $dm->load_questions_usages_latest_steps(
    $qubaidscondition, $quba->get_slots());

echo json_encode($latesstepdata);