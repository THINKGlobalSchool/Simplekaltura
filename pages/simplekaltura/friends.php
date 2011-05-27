<?php
/**
 * Friends' videos
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


$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'context' => 'videos',
	'title' => $title
));

echo elgg_view_page($title, $body);