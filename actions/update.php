<?php
/**
 * Simple Kaltura Update
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get inputs
$guid 			= get_input('video_guid', null);
$title 			= get_input('video_title');
$description 	= get_input('video_description');
$tags			= get_input('video_tags');
$tags_array 	= string_to_tag_array($tags);
$access 		= get_input('video_access');

$video = get_entity($guid);

if (elgg_instanceof($video, 'object', 'simplekaltura_video') && $video->canEdit()) {
	// Need a title 
	if (!$title) {
		register_error(elgg_echo('simplekaltura:error:titlerequired'));
		forward(REFERER);
	}
	
	// Set elgg stuff
	$video->title = $title;
	$video->description = $description;
	$video->tags = $tags_array;
	$video->access_id = $access;
	
	// If error saving, register error and return
	if (!$video->save()) {
		register_error(elgg_echo('simplekaltura:error:save'));
		forward(REFERER);
	}
	
	// Set kaltura stuff
	$entry = new KalturaMediaEntry();
	$entry->tags = $tags;
	$entry->name = $video->title;
	simplekaltura_update_entry($video->kaltura_entryid, $entry, TRUE);
	
	// Forward on
	system_message(elgg_echo('simplekaltura:success:update'));
	forward('pg/videos/view/' . $video->getGUID());
	
} else {
	register_error(elgg_echo('simplekaltura:error:notfound'));
}
