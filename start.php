<?php
/**
 * Simple Kaltura start.php
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * 
 * /////////// @TODO ///////////////
 * - Elggy my/friends/everyone
 * - Entity views/listings
 */

function simplekaltura_init() {

	// Include helpers
	require_once 'lib/simplekaltura_lib.php';

	// Extend CSS
	elgg_extend_view('css/screen','simplekaltura/css');


	// Page handler
	register_page_handler('videos','simplekaltura_page_handler');

	// Add to tools menu
	add_menu(elgg_echo("videos"), elgg_get_site_url() . 'pg/videos');

	// Add submenus
	elgg_register_event_handler('pagesetup','system','simplekaltura_submenus');

	// Register actions
	elgg_register_action('simplekaltura/save', elgg_get_plugin_path() . 'simplekaltura/actions/save.php');
	elgg_register_action('simplekaltura/delete', elgg_get_plugin_path() . 'simplekaltura/actions/delete.php');

	// Setup url handler for simple kaltura videos
	register_entity_url_handler('simplekaltura_url_handler','object', 'simplekaltura_video');

	// Comment handler
	elgg_register_plugin_hook_handler('entity:annotate', 'object', 'simplekaltura_annotate_comments');

	// Register type
	register_entity_type('object', 'simplekaltura_video');		

	return true;

}



/* Simplekaltura page handler */
function simplekaltura_page_handler($page) {
	global $CONFIG;
	set_context('simplekaltura');
	gatekeeper();

	elgg_push_breadcrumb(elgg_echo('videos'), "pg/videos"); // @TODO something better
	if (isset($page[0]) && !empty($page[0])) {
		switch ($page[0]) {
			case 'friends': 
				//$content_info = ubertags_get_page_content_friends(get_loggedin_userid());
			break;
			case 'search':
				//$content_info = ubertags_get_page_content_search();
			break;
			case 'view': 
				$content_info = simplekaltura_get_page_content_view($page[1]);
			break;
			case 'new':
			case 'edit':
				$content_info = simplekaltura_get_page_content_edit($page[1]);
			break;
			default:
				$content_info = simplekaltura_get_page_content_edit($page[1]);
			/*	// Should be a username if we're here
				if (isset($page[0])) {
					$owner_name = $page[0];
					set_input('username', $owner_name);
				} else {
					set_page_owner(get_loggedin_userid());
				}
				// grab the page owner
				$owner = elgg_get_page_owner();
				$content_info = ubertags_get_page_content_list($owner->getGUID());*/
			break;
		}
	} else {
		$content_info = simplekaltura_get_page_content_edit($page[1]); //@TODO this wouldnt be default.. ever. just for dev.
	}

	$sidebar = isset($content_info['sidebar']) ? $content_info['sidebar'] : '';

	$params = array(
		'content' => elgg_view('navigation/breadcrumbs') . $content_info['content'],
		'sidebar' => $content_info['sidebar'],
	);
	$body = elgg_view_layout($content_info['layout'], $params);

	echo elgg_view_page($content_info['title'], $body, $content_info['layout'] == 'administration' ? 'admin' : 'default');
	
}

/**
 * Set up submenus
 */
function simplekaltura_submenus() {
	// all/yours/friends 
	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:menu:yourvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/' . get_loggedin_user()->username), 'simplekaltura');

	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:menu:friendsvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/friends' ), 'simplekaltura');

	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:menu:allvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/' ), 'simplekaltura');

}

/**
 * Populates the ->getUrl() method for a simple kaltura video
 *
 * @param ElggEntity entity
 * @return string request url
 */
function simplekaltura_url_handler($entity) {
	return elgg_get_site_url() . "pg/videos/view/{$entity->guid}/";
}

/**
 * Hook into the framework and provide comments on  simple kaltura videos
 *
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown
 */
function simplekaltura_annotate_comments($hook, $entity_type, $returnvalue, $params) {
	$entity = $params['entity'];
	$full = $params['full'];

	if (
		($entity instanceof ElggEntity) &&	// Is the right type 
		($entity->getSubtype() == 'simplekaltura_video') &&  // Is the right subtype
		($full) // This is the full view
	)
	{
		// Display comments
		return elgg_view_comments($entity);
	}

}

register_elgg_event_handler('init', 'system', 'simplekaltura_init');
?>