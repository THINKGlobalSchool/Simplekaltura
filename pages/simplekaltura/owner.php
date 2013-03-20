<?php
/**
 * Simple Kaltura view videos owned by a single user/group
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$page_owner = elgg_get_page_owner_entity();

if (!$page_owner) {
	forward('videos');
}

elgg_push_breadcrumb($page_owner->name);

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'simplekaltura_video',
	'container_guid' => $page_owner->guid,
	'full_view' => false,
	'view_toggle_type' => false
));

if (!$content) {
	$content = elgg_echo('simplekaltura:no_content');
}

$title = elgg_echo('simplekaltura:owner', array($page_owner->name));

$filter_context = '';
if ($page_owner->getGUID() == elgg_get_logged_in_user_guid()) {
	$filter_context = 'mine';
	elgg_register_title_button('videos');
} else if (elgg_instanceof($page_owner, 'group')) {
	elgg_register_title_button('videos');
}

$vars = array(
	'filter_context' => $filter_context,
	'content' => $content,
	'context' => 'videos',
	'title' => $title,
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'owner')),
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}


$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);