<?php
/**
 * Simple Kaltura Embed Code
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('video_guid', $vars);

$video = get_entity($guid);
$entryId = $video->kaltura_entryid;

$site_link_label = elgg_echo('simplekaltura:label:viewonsite', array(elgg_get_site_entity()->name));

// Embed player dimensions/config
$width = elgg_get_plugin_setting('kaltura_embed_player_width', 'simplekaltura');
$height = elgg_get_plugin_setting('kaltura_embed_player_height', 'simplekaltura');
$embed_player = elgg_get_plugin_setting('kaltura_custom_embed_player_id', 'simplekaltura');

$embed_code = elgg_view('simplekaltura/widget', array(
	'custom_uiconfid' => $embed_player,
	'width' => $width,
	'height' => $height,
	'entity' => $video, 
));

$embed_code .= "<a href='{$video->getURL()}'>{$site_link_label}</a>";


$embed_text = "<label>" . elgg_echo('simplekaltura:label:copypaste') . "</label>";
$embed_text .= elgg_view('input/plaintext', array(
	'value' => $embed_code,
	'readonly' => 'readonly',
	'class' => 'elgg-kaltura-embed-code',
));

echo "<br />" . elgg_view_module('featured', elgg_echo('simplekaltura:label:embedcode'), $embed_text);