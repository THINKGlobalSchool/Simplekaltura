<?php
/**
 * Simple Kaltura save action
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

gatekeeper();

// Get inputs
$title = get_input('video_title');
$description = get_input('video_description');
$tags = string_to_tag_array(get_input('video_tags'));
$access = get_input('video_access');
$search = get_input('video_search');

//@TODO Check values..

$video = new ElggObject();
$video->subtype = 'simplekaltura_video';
$video->title = $title;
$video->description = $description;
$video->tags = $tags;
$video->access_id = $access;

// If error saving, register error and return
if (!$video->save()) {
	register_error(elgg_echo('simplekaltura:error:save'));
	forward(REFERER);
}

// Add to river
add_to_river('river/object/simplekaltura/create', 'create', get_loggedin_userid(), $video->getGUID());

// Forward on
system_message(elgg_echo('simplekaltura:success:save'));
forward('pg/videos');


?>