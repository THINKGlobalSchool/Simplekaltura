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
$player_height = get_input('height');  
$player_width  = get_input('width'); 
$autoplay = get_input('autoplay', '0');

// check for access on this and don't allow them to play vidoes in kaltura but not in our system.
if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	return true;
}

echo simplekaltura_get_swf_url($video);
return true;

echo elgg_view('simplekaltura/widget', array(
	'height' => $player_height,
	'width'	=> $player_width,
	'entity' => $video,
	'autoplay' => $autoplay
));