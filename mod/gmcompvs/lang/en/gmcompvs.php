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
 * English strings for gmcompvs
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_gmcompvs
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['attempt'] = 'Attempt #{$a}';
$string['gmcompvsapuestas'] = 'Apuestas';
$string['gmcompvsapuestas_help'] = 'Para el buen funcionamiento de las monedas una vez activado mantenerlo de esa manera';
$string['completionnumwon'] = 'Los estudiantes deben de haber ganado un minimo de competencias de:';
$string['completionnumwongroup'] = 'Competencias ganadas requeridas';
$string['completionnumwongroup_help'] = 'Si si activa, se debe de dar una cantidad minima de 
competencias ganas para que se ponga como completada la actividad.
(Por defecto es 1)';

$string['completionscore'] = 'Student must achieve a minimum score of:';
$string['completionscoregroup'] = 'Require score';
$string['completionscoregroup_help'] = 'If enabled, you can require a minimum score is met before the activity is marked as complete.

Each question is worth 1000 points when answered correctly on the first try, so you may want to set the default to:

(Number of questions x 1000)';
$string['endofgame'] = 'Your score was: {$a}. Press space or click to restart.';
$string['emptyquiz'] = 'There are no multiple choice questions in the selected category.';
$string['eventgamestarted'] = 'gmcompvs game started';
$string['eventgamescoreadded'] = 'gmcompvs score recorded';
$string['eventgamescoresviewed'] = 'gmcompvs scores viewed';
$string['fullscreen'] = 'Fullscreen';
$string['howtoplay'] = 'How to Play';
$string['howtoplay_help'] = 'You can move the ship by using the arrow keys, or by dragging it with the mouse.

Press the spacebar or click the mouse button to shoot, or tap with two fingers anywhere on the game.

Clear as many questions as possible by shooting the correct answer.  Good Luck!';
$string['modulename_help'] = 'Este módulo es una competencia entre estudiantes en modalidad 1 contra 1 con la posibilidad de apostar monedas para personalizar el perfil gamificado.

Se debe de seleccionar un banco de preguntas al agregar la competencia y ese banco se utilizará para competir, los estudiantes pueden elegir a quien desafiar, también tiene la capacidad
de completarse automaticamente al seleccionar un minimo requerido de competencias ganadas.

Al ganar una competencia se le otorga al estudiante experiencia y monedas para que pueda subir de nivel en su perfil gamificado y así ganar más premios';
$string['modulenameplural'] = 'gmcompvs games';
$string['modulename'] = 'Gamedle - Competencia uno contra uno';
$string['notyetplayed'] = 'Not yet played';
$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['playedxtimeswithhighscore'] = 'Played {$a->times} times. The last game ended with a high score of {$a->score}';
$string['pluginadministration'] = 'gmcompvs administration';
$string['pluginname'] = 'Competencia uno contra uno';
$string['playerscores'] = 'Player scores';
$string['privacy:metadata:gmcompvs_scores'] = 'Information about the user\'s chosen answer(s) for a given choice activity';
$string['privacy:metadata:gmcompvs_scores:gmcompvsid'] = 'The ID of the gmcompvs activity the user is providing answer for';
$string['privacy:metadata:gmcompvs_scores:score'] = 'The score of the user during that playthrough.';
$string['privacy:metadata:gmcompvs_scores:timecreated'] = 'The timestamp indicating when the gmcompvs was played by the user';
$string['privacy:metadata:gmcompvs_scores:userid'] = 'The ID of the user playing this gmcompvs activity';
$string['questioncategory'] = 'Categoría de preguntas';
$string['questioncategory_help'] = 'Select the category from the question bank to use in the game.

Note that you should only select questions that are not critical to assessment later on. The quiz game is similar to creating a quiz with infinite attempts and instant feedback on whether you got something right or wrong.

**Note**: gmcompvs is designed to promote learning rather than for assessment. Students will have infinite attempts with instant feedback. For this reason, only add questions you want students to learn the answer to, rather than questions you want to assess if they have learned';
$string['gmcompvsfieldset'] = 'Custom example fieldset';
$string['gmcompvsname_help'] = 'Cual es el nombre de esta actividad?';
$string['gmcompvsname'] = 'Nombre de actividad';
$string['gmcompvs'] = 'gmcompvs';
$string['gmcompvs:addinstance'] = 'Add a gmcompvs instance';
$string['gmcompvs:view'] = 'View gmcompvs';
$string['gmcompvs:viewallscores'] = 'View player scores';
$string['removescores'] = 'Remove all user scores';
$string['score'] = 'Score: {$a->score} Lives: {$a->lives}';
$string['scoreheader'] = 'Score';
$string['scoreslink'] = 'View all attempts';
$string['scoreslinkhelp'] = 'View all player attempts and scores';
$string['spacetostart'] = 'Press space or click to start';
$string['sound'] = 'Sound';
