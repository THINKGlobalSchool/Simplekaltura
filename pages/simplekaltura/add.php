<?php
/**
 * Simple Kaltura add video
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
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
	'handler' => 'videos',
	'sidebar' => elgg_view('simplekaltura/sidebar', array('page' => 'add')),
));

echo elgg_view_page($title, $body);