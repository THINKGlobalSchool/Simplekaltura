<?php
/**
 * Vidoes for a single user
 */

$page_owner = elgg_get_page_owner_entity();

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
	'title' => $title
);

// don't show filter if out of filter context
if ($page_owner instanceof ElggGroup) {
	$vars['filter'] = false;
}


$body = elgg_view_layout('content', $vars);

echo elgg_view_page($title, $body);