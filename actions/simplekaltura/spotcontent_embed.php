<?php
/**
 * Simple Kaltura Spot Content Embed Code Action
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('video_guid');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	register_error(elgg_echo('simplekaltura:error:notfound'));
} else {
	$view_video_label = elgg_echo('simplekaltura:label:viewvideo');
	$encoded_content = rawurlencode(elgg_view('simplekaltura/widget', array(
		'custom_uiconfid' => '10201381', // Embed specific player
		'entity' => $video, 
		'width' => '725',
		'height' => '540',
	)) ."<a href='{$video->getURL()}'>{$view_video_label}</a>");
	echo "[generic embed={$encoded_content}]";
}
forward(REFERER);