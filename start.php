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
 *
 * - Because "videos" and "simplekaltura" are both used in code, we have to
 *   use both in the strings. This is a bit messy.
 * - This plugin doesn't lend itself well to sticky forms. Not sure what to do about that.
 * - Comments should be added in the object/simplekaltura_video view or the page handler scripts.
 *   The plugin hook is deprecated.
 *
 */


register_elgg_event_handler('init', 'system', 'simplekaltura_init');

function simplekaltura_init() {
	$plugin_root = dirname(__FILE__);

	elgg_register_library('simplekaltura', "$plugin_root/lib/simplekaltura_lib.php");
	elgg_register_library('KalturaClient', "$plugin_root/vendors/kaltura_client_v3/KalturaClient.php");

	$url = elgg_get_simplecache_url('js', 'simplekaltura');
	elgg_register_js('simplekaltura', $url);

	// helper libs
	$libs = array('listing-popup', 'uploader', 'widget', 'swfobject', 'html5');

	foreach ($libs as $lib) {
		$url = elgg_get_simplecache_url('js', "simplekaltura/$lib");
		elgg_register_js("simplekaltura:$lib", $url);
	}

	elgg_extend_view('css/elgg', 'simplekaltura/css');
	elgg_register_page_handler('videos', 'simplekaltura_page_handler');

	// menus
	add_menu(elgg_echo("simplekaltura:spotvideo"), 'pg/videos');

	// actions
	$actions_root = "$plugin_root/actions/simplekaltura";
	elgg_register_action('simplekaltura/save', "$actions_root/save.php");
	elgg_register_action('simplekaltura/update', "$actions_root/update.php");
	elgg_register_action('simplekaltura/delete', "$actions_root/delete.php");

	// Setup url handler for simple kaltura videos
	register_entity_url_handler('simplekaltura_url_handler', 'object', 'simplekaltura_video');

	// Comment handler
	// in the views
	//elgg_register_plugin_hook_handler('entity:annotate', 'object', 'simplekaltura_annotate_comments');

	// Timeline icon handler
	elgg_register_plugin_hook_handler('ubertags:timeline:icon', 'simplekaltura_video', 'ubertags_timeline_video_icon_handler');

	// Register type
	elgg_register_entity_type('object', 'simplekaltura_video');

	//register CRON hook to poll video plays/duration/etc..
	elgg_register_plugin_hook_handler('cron', 'fifteenmin', 'simplekaltura_bulk_update');

	// Register some hooks for Ubertags support
	if (elgg_is_active_plugin('ubertags')) {
		elgg_register_plugin_hook_handler('ubertags:subtype:heading', 'simplekaltura_video', 'simplekaltura_subtype_title_handler');
	}

	return true;
}

/**
 * Serves pages for URLs like:
 *
 *  All videos:       videos/all
 *  User's videos:    videos/owner/<username>
 *  Friends' videos:  videos/friends/<username>
 *  View video:       videos/view/<guid>/<title>
 *  New video:        videos/add/<guid>
 *  Edit video:       videos/edit/<guid>
 *  Group videos:     videos/group/<guid>/owner
 *
 * @param string $page
 */
function simplekaltura_page_handler($page) {
	global $CONFIG;
	elgg_push_context('simplekaltura');

	elgg_push_breadcrumb(elgg_echo('videos'), 'videos');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	$pages_dir = dirname(__FILE__) . '/pages/simplekaltura';

	switch ($page_type) {
		case 'owner':
			include "$pages_dir/owner.php";
			break;

		case 'friends':
			include "$pages_dir/friends.php";
			break;

		case 'view':
			set_input('guid', elgg_extract(1, $page));
			include "$pages_dir/view.php";
			break;

		case 'add':
			include "$pages_dir/add.php";
			break;

		case 'edit':
			set_input('guid', elgg_extract(1, $page));
			include "$pages_dir/edit.php";
			break;

		case 'all':
		default:
			include "$pages_dir/all.php";
			break;
	}

	return true;
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

/**
 * Prepares form vars for simplekaltura_video objects
 *
 * @param ElggObject $entity Optional entity to base values on
 * @return array
 */
function simplekaltura_prepare_form_vars($entity = null) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $entity
	);

	if ($entity) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if (elgg_is_sticky_form('simplekaltura')) {
		$sticky_values = elgg_get_sticky_values('rubrics');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('simplekaltura');

	return $values;
}