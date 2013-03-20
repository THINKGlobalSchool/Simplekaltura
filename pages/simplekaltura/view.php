<?php
/**
 * Simple Kaltura view video
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$video = get_entity(get_input('guid'));

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
}

$page_owner = elgg_get_page_owner_entity();

$crumbs_title = $page_owner->name;

if (elgg_instanceof($page_owner, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "videos/group/$page_owner->guid/owner");
} else {
	elgg_push_breadcrumb($crumbs_title, "videos/owner/$page_owner->username");
}

$title = $video->title;

elgg_push_breadcrumb($title);

$content = elgg_view_entity($video, array('full_view' => TRUE));
//$content .= elgg_view_comments($video);

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'view')),
));

echo elgg_view_page($title, $body);
