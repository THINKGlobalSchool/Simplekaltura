<?php
/**
 * Simple Kaltura Embed Code
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = elgg_extract('video_guid', $vars);

$video = get_entity($guid);
$entryId = $video->kaltura_entryid;

$spot_link_label = elgg_echo('simplekaltura:label:viewonspot');

$embed_code = elgg_view('simplekaltura/widget', array(
	'custom_uiconfid' => '10201381', // Embed specific player
	'width' => '725',
	'height' => '540',
	'entity' => $video, 
));

$embed_code .= "<a href='{$video->getURL()}'>{$spot_link_label}</a>";


$embed_text = "<label>" . elgg_echo('simplekaltura:label:copypaste') . "</label>";
$embed_text .= elgg_view('input/plaintext', array(
	'value' => $embed_code,
	'readonly' => 'readonly',
	'class' => 'elgg-kaltura-embed-code',
));

echo "<br />" . elgg_view_module('featured', elgg_echo('simplekaltura:label:embedcode'), $embed_text);