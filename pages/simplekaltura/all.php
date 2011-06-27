<?php
/**
 * All videos
 */

$title = elgg_echo('simplekaltura:all');

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'simplekaltura_video',
	'full_view' => false
));

if (!$content) {
	$content = elgg_echo('simplekaltura:no_content');
}

elgg_register_add_button('videos');

$body = elgg_view_layout('content', array(
	'context' => 'videos',
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title
));

echo elgg_view_page($title, $body);
