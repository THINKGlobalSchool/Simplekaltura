<?php
/**
 * Simple Kaltura edit a video
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

elgg_push_breadcrumb($video->title, $video->getURL());
elgg_push_breadcrumb(elgg_echo('edit'));
$vars = simplekaltura_prepare_form_vars($video);
$content = elgg_view_form("simplekaltura/save", array(), $vars);

$title = elgg_echo('simplekaltura:label:editvideo', array($video->title));

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'context' => 'videos',
	'title' => $title,
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'edit')),
));

echo elgg_view_page($title, $body);