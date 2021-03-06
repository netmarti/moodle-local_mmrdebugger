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

$user = $DB->get_record('user', array('id'=>$id), '*', MUST_EXIST);

require_login();
require_capability('moodle/site:config', get_context_instance(CONTEXT_SYSTEM));

$url = new moodle_url('/local/mmrdebugger/user.php', array('id'=>$id));

$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');
$PAGE->set_title(fullname($user));
$PAGE->set_heading(fullname($user));
echo $OUTPUT->header();

echo '<iframe src="user.php?id=' .$id. '" width="100%" height="400" name="uactions" id="uactions"></iframe>';
echo '<form id="userf" action="user.php" target="uactions" onsubmit="">
    <input type="text" size="40" name="command" id="command">
    <input type="hidden" name="id" value="'.$id.'">
    <input type="hidden" name="type" value="command">
    ';

echo html_writer::start_tag('img',  array('src' => $OUTPUT->pix_url('camera', 'local_mmrdebugger'),
                                          'alt' => get_string('screenshot'),
                                          'id' => 'screenshotbutton'
                                          ));
echo html_writer::start_tag('img',  array('src' => $OUTPUT->pix_url('phone', 'local_mmrdebugger'),
                                          'alt' => get_string('streaming', 'local_mmrdebugger'),
                                          'id' => 'streampagebutton'
                                          ));
echo '</form>';

$jsmodule = array(
                'name' => 'local_mmrdebugger',
                'fullpath' => '/local/mmrdebugger/module.js',
                'requires' => array("io", "gallery-base64"));

$PAGE->requires->js_init_call('M.local_mmrdebugger.init', array(array('userid'=>$id)), false, $jsmodule);
echo $OUTPUT->footer();

