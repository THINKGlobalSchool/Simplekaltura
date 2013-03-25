<?php
/**
 * Simple Kaltura video popup widget
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity_guid']
 */

$video_guid = elgg_extract('entity_guid', $vars);
$video = get_entity($video_guid);

// Check valid entity
if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	return FALSE;
}

$autoplay = elgg_extract('autoplay', $vars, 0);

$wid = "_" . elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$uiconf_id = elgg_get_plugin_setting('kaltura_custom_player_id', 'simplekaltura'); 
$entry_id = $video->kaltura_entryid;

if ($autoplay) {
	$autoplay = 'true';
} else {
	$autoplay = 'false';
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

// Width and height need to set as style attribute on dynamic player container
$width = elgg_get_plugin_setting('kaltura_popup_width', 'simplekaltura');   
$height = elgg_get_plugin_setting('kaltura_popup_height', 'simplekaltura');

$content = <<<HTML
	<div class='elgg-kaltura-popup'>
		<div id="kaltura-dynamic-container" style="width:{$width}px;height:{$height}px;">
			$wid_input
			$uiconf_id_input
			$entry_id_input
			$autoplay_input
		</div>
	</div>
	<div class='elgg-kaltura-embed-container'></div>
HTML;

echo $content;