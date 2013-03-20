<?php
/**
 * Simple Kaltura view friends' videos
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($owner->name, "videos/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo("simplekaltura:friends", array($owner->name));

// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'simplekaltura_video', 10, false);

if (!$content) {
	$content = elgg_echo("simplekaltura:no_content");
}

elgg_register_title_button('videos');

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'context' => 'videos',
	'title' => $title,
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'friends')),
));

echo elgg_view_page($title, $body);