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
 * Publig messages hub
 * A basic messages processor that stores the messages in the application cache
 *
 * @package   local_mmrdebugger
 * @copyright 2012 Juan Leyva <jleyva@cvaconsulting.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$id      = required_param('id', PARAM_INT);
$command = optional_param('command', '', PARAM_RAW);

$user = $DB->get_record('user', array('id'=>$id), '*', MUST_EXIST);

require_login();
require_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM));

$url = new moodle_url('/local/mmrdebugger/user.php', array('id'=>$id));

$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');
$PAGE->set_title(fullname($user));
$PAGE->set_periodic_refresh_delay(5);
echo $OUTPUT->header();

$cache = cache::make('local_mmrdebugger', 'messages');

if ($command) {
 if (!$messages = $cache->get($id)) {
  $messages = array();
 }
 $idnumber = count($messages);
 $messages[$idnumber] = array("id"=>$idnumber, "type" => "command", "text" => $command, "response" => "");
 $cache->set($id, $messages);
}

// Security, active users allways has an element in cache
if ($messages = $cache->get($id)) {
	foreach (array_reverse($messages) as $message) {
		echo "<div style=\"border: 1px solid red; padding: 4px; margin: 4px\"><p><b>".$message['text']."</b><br />".$message['response']."</p></div>";
	}
}

echo $OUTPUT->footer();
