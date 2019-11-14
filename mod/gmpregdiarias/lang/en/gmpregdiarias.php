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
 * English strings for gmpregdiarias
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_gmpregdiarias
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['completionpregdiarenabled'] = 'El estudiante debe de contestar todas las preguntas diarias';
$string['completionpregdiargroup'] = 'Total de preguntas';
$string['completionpregdiargroup_help'] = 'Si se activa, se requiere que el estudiante conteste todas las preguntas diarias para que sea completada la actividad, advertencia: si se agregan nuevas preguntas al banco de preguntas seleccionado no se descompletará la actividad';
$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['attempt'] = 'Attempt #{$a}';
$string['completionscore'] = 'Student must achieve a minimum score of:';
$string['completionscoregroup'] = 'Require score';
$string['completionscoregroup_help'] = 'If enabled, you can require a minimum score is met before the activity is marked as complete.

Each question is worth 1000 points when answered correctly on the first try, so you may want to set the default to:

(Number of questions x 1000)';
$string['endofgame'] = 'Your score was: {$a}. Press space or click to restart.';
$string['emptyquiz'] = 'There are no multiple choice questions in the selected category.';
$string['eventgamestarted'] = 'gmpregdiarias game started';
$string['eventgamescoreadded'] = 'gmpregdiarias score recorded';
$string['eventgamescoresviewed'] = 'gmpregdiarias scores viewed';
$string['fullscreen'] = 'Fullscreen';
$string['howtoplay'] = 'How to Play';
$string['howtoplay_help'] = 'You can move the ship by using the arrow keys, or by dragging it with the mouse.

Press the spacebar or click the mouse button to shoot, or tap with two fingers anywhere on the game.

Clear as many questions as possible by shooting the correct answer.  Good Luck!';
$string['modulename_help'] = 'Esta actividad ayuda a los estudiantes a mejorar constantemente su conocimiento al desafiarlos a contestar la pregunta del día.

Esta pregunta es elegida aleatoriamente del banco de preguntas seleccionado al crear la actividad.

Esta actividad se puede completar automaticamente cuando el estudiante termina de contestar todas las preguntas del banco de preguntas seleccionado, es necesario activar que se complete automaticamente al agregar la actividad.

Al contestar la pregunta diaria correctamente se le otorga al estudiante experiencia y monedas.';
$string['modulenameplural'] = 'gmpregdiarias games';
$string['modulename'] = 'Gamedle - Preguntas diarias';
$string['notyetplayed'] = 'Not yet played';
$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['playedxtimeswithhighscore'] = 'Played {$a->times} times. The last game ended with a high score of {$a->score}';
$string['pluginadministration'] = 'gmpregdiarias administration';
$string['pluginname'] = 'Preguntas diarias';
$string['playerscores'] = 'Player scores';
$string['privacy:metadata:gmpregdiarias_scores'] = 'Information about the user\'s chosen answer(s) for a given choice activity';
$string['privacy:metadata:gmpregdiarias_scores:gmpregdiariasid'] = 'The ID of the gmpregdiarias activity the user is providing answer for';
$string['privacy:metadata:gmpregdiarias_scores:score'] = 'The score of the user during that playthrough.';
$string['privacy:metadata:gmpregdiarias_scores:timecreated'] = 'The timestamp indicating when the gmpregdiarias was played by the user';
$string['privacy:metadata:gmpregdiarias_scores:userid'] = 'The ID of the user playing this gmpregdiarias activity';
$string['questioncategory'] = 'Categoría de preguntas';
$string['questioncategory_help'] = 'Selecciona el banco de preguntas que quieres utilizar para las preguntas diarias
ADVERTENCIA al banco de preguntas seleccionado no se le deben de agregar o quitar preguntas puesto que no es posible validar el buen comportamiendo de la actividad si esto llegara a suceder ';
$string['gmpregdiariasfieldset'] = 'Custom example fieldset';
$string['gmpregdiariasname_help'] = 'What is the name of this gmpregdiarias?';
$string['gmpregdiariasname'] = 'Nombre de actividad';
$string['gmpregdiarias'] = 'gmpregdiarias';
$string['gmpregdiarias:addinstance'] = 'Add a gmpregdiarias instance';
$string['gmpregdiarias:view'] = 'View gmpregdiarias';
$string['gmpregdiarias:viewallscores'] = 'View player scores';
$string['removescores'] = 'Remove all user scores';
$string['score'] = 'Score: {$a->score} Lives: {$a->lives}';
$string['scoreheader'] = 'Score';
$string['scoreslink'] = 'View all attempts';
$string['scoreslinkhelp'] = 'View all player attempts and scores';
$string['spacetostart'] = 'Press space or click to start';
$string['sound'] = 'Sound';
