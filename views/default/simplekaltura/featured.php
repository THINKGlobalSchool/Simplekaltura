<?php
/**
 * Simple Kaltura Featured Sidebar
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */
if (!elgg_get_page_owner_guid()) {
	$options = array(
		'type' => 'object',
		'subtype' => 'simplekaltura_video',
		'metadata_name' => 'featured_video',
		'metadata_value' => 'yes',
		'full_view' => FALSE,
		'limit' => 5,
		'offset' => 0,
		'pagination' => FALSE,
	);

	elgg_push_context('gallery');
	$videos = elgg_list_entities_from_metadata($options);
	elgg_pop_context();

	if ($videos) {
		echo elgg_view_module('aside', elgg_echo('simplekaltura:label:featured'), $videos, array(
			'class' => 'simplekaltura-sidebar-gallery-module'
		));
	}
}