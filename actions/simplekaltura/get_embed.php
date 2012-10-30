<?php
/**
 * Simple Kaltura Embed Code Action
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
} else if ($video->access_id != ACCESS_PUBLIC) {
	register_error(elgg_echo('simplekaltura:error:nonpublic'));
} else {
	echo elgg_view('simplekaltura/embed', array('video_guid' => $video->guid));
}
forward(REFERER);