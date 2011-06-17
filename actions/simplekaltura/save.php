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

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

$title = get_input('video_title');
$description = get_input('video_description');
$tags = get_input('video_tags');
$tags_str  = string_to_tag_array($tags);
$access = get_input('video_access');
$comments_on = get_input('comments_on', 'On');

$guid = get_input('guid');
$video = get_entity($guid);
$new = false;

// edit
if ($guid) {
	if (!elgg_instanceof($video, 'object', 'simplekaltura_video') || !$video->canEdit()) {
		register_error('simplekaltura:error:notfound');
		forward(REFERER);
	}
} else {
	// Kaltura related
	$k_guid = get_input('k_guid');
	$k_entryid = get_input('k_entryid');
	$k_bytesloaded = get_input('k_bytesloaded');
	$k_filetype = get_input('k_filetype');

	if (!$k_guid || !$k_entryid || !$k_bytesloaded || !$k_filetype) {
		register_error(elgg_echo('simplekaltura:error:save'));
		forward(REFERER);
	}

	// Set elgg stuff
	$video = new ElggObject();
	$video->subtype = 'simplekaltura_video';
	$video->kaltura_guid = $k_guid;
	$video->kaltura_entryid = $k_entryid;
	$video->kaltura_bytesloaded = $k_bytesloaded;
	$video->kaltura_filetype = $k_filetype;
}

$video->title = $title;
$video->description = $description;
$video->tags = $tags_str;
$video->access_id = $access;
$video->comments_on = $comments_on;

// If error saving, register error and return
if (!$video->save()) {
	register_error(elgg_echo('simplekaltura:error:save'));
	forward(REFERER);
}

// Set admin tags and other kaltura bits
$entry = new KalturaMediaEntry();

// only for new videos
if (!$guid) {
	$entry->adminTags = elgg_get_plugin_setting('kaltura_admin_tags', 'simplekaltura');
}
$entry->tags = $tags;
$entry->name = $video->title;
simplekaltura_update_entry($video->kaltura_entryid, $entry, TRUE);

// Add to river
if (!$guid) {
	add_to_river('river/object/simplekaltura/create', 'create', elgg_get_logged_in_user_guid(), $video->getGUID());
}

// Forward on
system_message(elgg_echo('simplekaltura:success:save'));
forward($video->getURL());