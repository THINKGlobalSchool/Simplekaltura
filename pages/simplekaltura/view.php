<?php
/**
 * View a video
 */

$video = get_entity(get_input('guid'));

$page_owner = elgg_get_page_owner_entity();

$crumbs_title = $page_owner->name;

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "videos/group/$page_owner->guid/owner");
} else {
	elgg_push_breadcrumb($crumbs_title, "videos/owner/$page_owner->username");
}

$title = $video->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($video, true);
//$content .= elgg_view_comments($video);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'view')),
));

echo elgg_view_page($title, $body);
