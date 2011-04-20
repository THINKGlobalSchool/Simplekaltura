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
 * - What to do for deletes?
 */

function simplekaltura_init() {

	// Include helpers
	require_once 'lib/simplekaltura_lib.php';

	// Extend CSS
	elgg_extend_view('css/screen','simplekaltura/css');


	// Page handler
	register_page_handler('videos','simplekaltura_page_handler');

	// Add to tools menu
	add_menu(elgg_echo("simplekaltura:spotvideo"), elgg_get_site_url() . 'pg/videos');

	// Add submenus
	elgg_register_event_handler('pagesetup','system','simplekaltura_submenus');

	// Register actions
	elgg_register_action('simplekaltura/save', elgg_get_plugin_path() . 'simplekaltura/actions/save.php');
	elgg_register_action('simplekaltura/update', elgg_get_plugin_path() . 'simplekaltura/actions/update.php');
	elgg_register_action('simplekaltura/delete', elgg_get_plugin_path() . 'simplekaltura/actions/delete.php');

	// Setup url handler for simple kaltura videos
	register_entity_url_handler('simplekaltura_url_handler','object', 'simplekaltura_video');

	// Comment handler
	elgg_register_plugin_hook_handler('entity:annotate', 'object', 'simplekaltura_annotate_comments');

	// Timeline icon handler
	elgg_register_plugin_hook_handler('ubertags:timeline:icon', 'simplekaltura_video', 'ubertags_timeline_video_icon_handler');
	
	// Change Tag Dashboards Heading
	elgg_register_plugin_hook_handler('tagdashboards:subtype:heading', 'simplekaltura_video', 'simplekaltura_heading_handler');
	
	// Register type
	register_entity_type('object', 'simplekaltura_video');		
	
	//register CRON hook to poll video plays/duration/etc..
	elgg_register_plugin_hook_handler('cron', 'fifteenmin', 'simplekaltura_bulk_update');
	
	// Register some hooks for Ubertags support
	if (is_plugin_enabled('ubertags')) {
		elgg_register_plugin_hook_handler('ubertags:subtype:heading', 'simplekaltura_video', 'simplekaltura_subtype_title_handler');
	}
	
	// Register Popup JS
	elgg_register_js(elgg_get_site_url() . 'mod/simplekaltura/lib/listing-popup.js', 'video-listing-popup');
	
	// Add group option
	add_group_tool_option('simplekaltura',elgg_echo('simplekaltura:enablegroup'), TRUE);
	
	// Profile hook	
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'simplekaltura_profile_menu');
	
	// Extend Groups profile page
	elgg_extend_view('groups/tool_latest','simplekaltura/group_module');
	
	
	return true;
}

/* Simplekaltura page handler */
function simplekaltura_page_handler($page) {
	global $CONFIG;
	set_context('simplekaltura');
	
	elgg_push_breadcrumb(elgg_echo('videos'), "pg/videos"); // @TODO something better
	
	// Following the core blogs plugin page handler
	if (!isset($page[0])) {
		$page[0] = 'all';
	}
	
	$page_type = $page[0];
	
	switch ($page_type) {
		case 'owner':
			if (isset($page[1])) {
				$owner_name = $page[1];
				set_input('username', $owner_name);
			} else {
				set_page_owner(get_loggedin_userid());
			}
			// grab the page owner
			$owner = elgg_get_page_owner();
			$params = simplekaltura_get_page_content_list($owner->guid);
			break;
		case 'friends': 
			$user = get_user_by_username($page[1]);
			$params = simplekaltura_get_page_content_friends($user->guid);
			break;
		case 'view': 
			$params = simplekaltura_get_page_content_view($page[1]);
			break;
		case 'new':
			$params = simplekaltura_get_page_content_edit($page_type, $page[1]);
			break;
		case 'edit':
			$params = simplekaltura_get_page_content_edit($page_type, $page[1]);
			break;
		case 'group':
			//$params = blog_get_page_content_list($page[1]);
			break;
		case 'all':
		default:
			$params = simplekaltura_get_page_content_list();
			break;
	}

	$params['sidebar'] .= isset($params['sidebar']) ? $params['sidebar'] : '';
	$params['content'] = elgg_view('navigation/breadcrumbs') . $params['content'];

	$body = elgg_view_layout($params['layout'], $params);

	echo elgg_view_page($params['title'], $body);
	
}

/**
 * Set up submenus
 */
function simplekaltura_submenus() {
	
	$user = get_loggedin_user();
	
	// all/yours/friends 
	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:title:yourvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/owner/' . get_loggedin_user()->username), 'simplekaltura');

	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:title:friendsvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/friends/' . $user->username), 'simplekaltura');

	elgg_add_submenu_item(array('text' => elgg_echo('simplekaltura:title:allvideos'), 
								'href' => elgg_get_site_url() . 'pg/videos/' ), 'simplekaltura');

}

/**
 * Plugin hook to add simplekaltura videos to the profile block
 * 	
 * @param unknown_type $hook
 * @param unknown_type $entity_type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown
 */
function simplekaltura_profile_menu($hook, $entity_type, $return_value, $params) {
	global $CONFIG;

	if ($params['owner'] instanceof ElggUser || $params['owner']->simplekaltura_enable == 'yes') {
		$return_value[] = array(
			'text' => elgg_echo('simplekaltura:spotvideo'),
			'href' => "{$CONFIG->url}pg/videos/owner/{$params['owner']->username}",
		);
	}
	return $return_value;
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

/* Handler to register a timeline icon for simplekaltura videos */
function ubertags_timeline_video_icon_handler($hook, $type, $returnvalue, $params) {
	if ($type == 'simplekaltura_video') {
		return elgg_get_site_url() . "mod/simplekaltura/images/simplekaltura_video.gif";
	}
	return false;
}

/* Handler to change the heading for simplekaltura videos */
function simplekaltura_heading_handler($hook, $type, $returnvalue, $params) {
	return elgg_echo('simplekaltura:spotvideo');
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

/* Handler to change name of Albums to Photos */
function simplekaltura_subtype_title_handler($hook, $type, $returnvalue, $params) {
	if ($type == 'simplekaltura_video') {
		return 'Spot Videos';
	}
}

register_elgg_event_handler('init', 'system', 'simplekaltura_init');
?>