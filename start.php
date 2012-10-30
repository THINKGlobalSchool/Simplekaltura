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
 *
 * - Because "videos" and "simplekaltura" are both used in code, we have to
 *   use both in the strings. This is a bit messy.
 * - This plugin doesn't lend itself well to sticky forms. Not sure what to do about that.
 *
 * - Figure out when to do the pull from Kaltura for stats. Bulk updates every 15 minutes are best,
 *   but IIRC it was previously doing updates on every entity view.
 */

elgg_register_event_handler('init', 'system', 'simplekaltura_init');

function simplekaltura_init() {
	$plugin_root = dirname(__FILE__);

	elgg_register_library('simplekaltura', "$plugin_root/lib/simplekaltura_lib.php");
	elgg_register_library('KalturaClient', "$plugin_root/vendors/kaltura_client/KalturaClient.php");
	elgg_load_library('simplekaltura');

	// helper libs
	$libs = array('listing-popup', 'uploader', 'widget', 'swfobject', 'html5', 'utility');

	foreach ($libs as $lib) {
		$url = elgg_get_simplecache_url('js', "simplekaltura/$lib");
		elgg_register_simplecache_view("js/simplekaltura/$lib");	
		elgg_register_js("simplekaltura:$lib", $url);
	}

	// Include html5 js library
	elgg_load_js('simplekaltura:html5');
	elgg_load_js('simplekaltura:utility');

	elgg_extend_view('css/elgg', 'simplekaltura/css');
	elgg_register_page_handler('videos', 'simplekaltura_page_handler');

	// Add to main menu
	$item = new ElggMenuItem('simplekaltura', elgg_echo('simplekaltura:spotvideo'), 'videos');
	elgg_register_menu_item('site', $item);
	
	// add the group pages tool option     
	add_group_tool_option('simplekaltura',elgg_echo('groups:enablesimplekaltura'), TRUE);
	
	// Profile block hook	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'simplekaltura_owner_block_menu');
	
	// Modify entity menu for addional video items
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'simplekaltura_setup_entity_menu');
	
	// actions
	$actions_root = "$plugin_root/actions/simplekaltura";
	elgg_register_action('simplekaltura/save', "$actions_root/save.php");
	elgg_register_action('simplekaltura/update', "$actions_root/update.php");
	elgg_register_action('simplekaltura/get_embed', "$actions_root/get_embed.php");
	elgg_register_action('simplekaltura/spotcontent_embed', "$actions_root/spotcontent_embed.php");
	elgg_register_action('videos/delete', "$actions_root/delete.php");

	// entity url and icon handlers
	elgg_register_entity_url_handler('object', 'simplekaltura_video', 'simplekaltura_url_handler');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'simplekaltura_icon_url_override');

	// Timeline icon handler
	elgg_register_plugin_hook_handler('tagdashboards:timeline:icon', 'simplekaltura_video', 'tagdashboards_timeline_video_icon_handler');

	// Register type
	elgg_register_entity_type('object', 'simplekaltura_video');

	// register CRON hook to poll video plays/duration/etc..
	elgg_register_plugin_hook_handler('cron', 'fifteenmin', 'simplekaltura_bulk_update');
	
	// Hook into facebook open graph image
	elgg_register_plugin_hook_handler('opengraph:image', 'facebook', 'simplekaltura_opengraph_image_handler');
	
	// Customize the simplekaltura embed entity menu
	if (elgg_is_active_plugin('tgsembed')) {
			elgg_register_plugin_hook_handler('register', 'menu:simpleicon-entity', 'simplekaltura_setup_simpleicon_entity_menu');
	}
	
	// Most Played Sidebar
	elgg_extend_view('simplekaltura/sidebar', 'simplekaltura/most_played');

	// Register some hooks for tagdashboards support
	if (elgg_is_active_plugin('tagdashboards')) {
		elgg_register_plugin_hook_handler('tagdashboards:subtype:heading', 'simplekaltura_video', 'simplekaltura_subtype_title_handler');
	}

	// Ajax whitelist
	//elgg_register_ajax_view('simplekaltura/embed');
	
	return TRUE;
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
 *  Popup video:      videos/popup/<guid>
 *
 * @param string $page
 */
function simplekaltura_page_handler($page) {
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
		case 'group':
			group_gatekeeper();
			include "$pages_dir/owner.php";
			break;
		case 'popup':
			set_input('guid', elgg_extract(1, $page));
			include "$pages_dir/popup.php";
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
	return elgg_get_site_url() . "videos/view/{$entity->guid}/";
}

/* Handler to register a timeline icon for simplekaltura videos */
function tagdashboards_timeline_video_icon_handler($hook, $type, $returnvalue, $params) {
	if ($type == 'simplekaltura_video') {
		return elgg_get_site_url() . "mod/simplekaltura/images/simplekaltura_video.gif";
	}
	return false;
}

/* Handler to change name of Albums to Photos */
function simplekaltura_subtype_title_handler($hook, $type, $returnvalue, $params) {
	if ($type == 'simplekaltura_video') {
		return 'Spot Videos';
	}
}


/**
 * Override the default entity icon for videos
 *
 * @return string Relative URL
 */
function simplekaltura_icon_url_override($hook, $type, $returnvalue, $params) {
	$video = $params['entity'];
	$size = $params['size'];
	
	if (elgg_instanceof($video, 'object', 'simplekaltura_video')) {
		$thumbnail_url = $vars['entity']->thumbnailUrl;
		if (!$thumbnail_url) {
			$thumbnail_url = elgg_get_plugin_setting('kaltura_thumbnail_url', 'simplekaltura') . $video->kaltura_entryid;
		}

		return $thumbnail_url;
	}
}

/**
 * Plugin hook to add simplekaltura videos to the profile block
 * 	
 * @param sting  $hook   hook
 * @param string $type   type
 * @param mixed  $value  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function simplekaltura_owner_block_menu($hook, $type, $value, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "videos/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('simplekaltura', elgg_echo('simplekaltura:spotvideo'), $url);
		$value[] = $item;
	} else {
		if ($params['entity']->simplekaltura_enable == 'yes') {
			$url = "videos/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('simplekaltura', elgg_echo('simplekaltura:label:groupvideos'), $url);
			$value[] = $item;
		}
	}
	return $value;
}

/**
 * Add download link to video entity menu
 *
 * @param sting  $hook   hook
 * @param string $type   type
 * @param mixed  $value  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function simplekaltura_setup_entity_menu($hook, $type, $value, $params) {
	$entity = $params['entity'];

	if (elgg_instanceof($entity, 'object', 'simplekaltura_video')) {
		// Add download link if we have a download url
		$download_url = $entity->downloadUrl;
		if ($download_url) {
			$options = array(
				'name' => 'download_video',
				'text' => elgg_echo('simplekaltura:label:download'),
				'title' => 'download_video',
				'href' => $download_url,
				'section' => 'actions',
				'priority' => 300,
			);

			$value[] = ElggMenuItem::factory($options);
		}
	}

	return $value;
}

/**
 * Provide video thumbnail
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function simplekaltura_opengraph_image_handler($hook, $type, $return, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'simplekaltura_video')) {
		return $entity->getIconURL();
	}
	return $return;
}

/**
 * Register items for the simpleicon entity menu
 *
 * @param sting  $hook   view
 * @param string $type   input/tags
 * @param mixed  $return  Value
 * @param mixed  $params Params
 *
 * @return array
 */
function simplekaltura_setup_simpleicon_entity_menu($hook, $type, $return, $params) {
	if (get_input('embed_spot_content')) {
		$entity = $params['entity'];
		
		if (elgg_instanceof($entity, 'object', 'simplekaltura_video')) {
			// Item to add object to portfolio
			$options = array(
				'name' => 'embed_video',
				'text' => elgg_echo('simplekaltura:label:embedvideo'),
				'title' => 'embed_video',
				'href' => "#{$entity->guid}",
				'class' => 'simplekaltura-spotcontent-embed elgg-button elgg-button-action',
				'section' => 'info',
			);
			
			$return[] = ElggMenuItem::factory($options);
			return $return;
		}
	}
	return $return;
}
