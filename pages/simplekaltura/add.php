<?php
/**
 * Add video
 */

$page_owner = elgg_get_page_owner_entity();

$title = elgg_echo('videos:add');
elgg_push_breadcrumb($title);

$vars = simplekaltura_prepare_form_vars();
$content = elgg_view_form('simplekaltura/save', array(), $vars);

$body = elgg_view_layout('content', array(
	'filter' => '',
	'context' => 'videos',
	'content' => $content,
	'title' => $title,
	'handler' => 'videos'
));

echo elgg_view_page($title, $body);