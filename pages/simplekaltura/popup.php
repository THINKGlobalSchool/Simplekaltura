<?php
/**
 * Simple Kaltura Video Popup Widget
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$video_guid = get_input('guid');
$video = get_entity($video_guid);
$autoplay = get_input('autoplay', 0);
$wid = "_" . elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$uiconf_id = elgg_get_plugin_setting('kaltura_custom_player_id', 'simplekaltura'); 
$entry_id = $video->kaltura_entryid;

if ($autoplay) {
	$autoplay = 'true';
} else {
	$autoplay = 'false';
}

// check for access on this and don't allow them to play vidoes in kaltura but not in our system.
if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	return FALSE;
}

$wid_input = elgg_view('input/hidden', array(
	'name' => 'wid',
	'value' => $wid,
));

$uiconf_id_input = elgg_view('input/hidden', array(
	'name' => 'uiconf_id',
	'value' => $uiconf_id,
));

$entry_id_input = elgg_view('input/hidden', array(
	'name' => 'entry_id',
	'value' => $entry_id,
));

$autoplay_input = elgg_view('input/hidden', array(
	'name' => 'autoplay',
	'value' => $autoplay,
));

$content = <<<HTML
	<div class='elgg-kaltura-popup'>
		<div id="kaltura-dynamic-container" style="width:600px;height:450px;">
			$wid_input
			$uiconf_id_input
			$entry_id_input
			$autoplay_input
		</div>
	</div>
	<div class='elgg-kaltura-embed-container'></div>
HTML;

echo $content;

// Add a view to extend popup
echo elgg_view('simplekaltura/popup', array('entity' => $video));