<?php
/**
 * Simple Kaltura start.php
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

elgg_register_event_handler('init', 'system', 'simplekaltura_init');

function simplekaltura_init() {
	$plugin_root = dirname(__FILE__);

	elgg_register_library('simplekaltura', "$plugin_root/lib/simplekaltura_lib.php");
	elgg_register_library('KalturaClient', "$plugin_root/vendors/kaltura_client/KalturaClient.php");
	elgg_load_library('simplekaltura');

	// helper libs
	$libs = array('swfobject', 'html5', 'utility', 'thumbs');

	foreach ($libs as $lib) {
		$url = elgg_get_simplecache_url('js', "simplekaltura/$lib");
		elgg_register_js("simplekaltura:$lib", $url);
	}

	// Register SKUploader
	elgg_register_external_view('js/simplekaltura/SKUploader.js', TRUE);

	// Include html5 js library
	elgg_load_js('simplekaltura:html5');
	elgg_load_js('simplekaltura:utility');

	elgg_extend_view('css/elgg', 'simplekaltura/css');

	// If plugin is properly configured
	if (simplekaltura_is_configured()) {
		// Add to main menu
		$item = new ElggMenuItem('simplekaltura', elgg_get_plugin_setting('kaltura_entity_title', 'simplekaltura'), 'videos');
		elgg_register_menu_item('site', $item);

		// Register page handler
		elgg_register_page_handler('videos', 'simplekaltura_page_handler');
	} else {
		elgg_add_admin_notice('simpkaltura_not_configured', elgg_echo('simplekaltura:error:pluginnotconfigured'));
	}
	
	// add the group pages tool option     
	add_group_tool_option('simplekaltura',elgg_echo('groups:enablesimplekaltura'), TRUE);
	
	// Profile block hook	
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'simplekaltura_owner_block_menu');
	
	// Modify entity menu for addional video items
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'simplekaltura_setup_entity_menu');

	// notifications
	elgg_register_notification_event('object', 'simplekaltura_video', array('create'));
	elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:simplekaltura_video', 'simplekaltura_prepare_notification');

	// actions
	$actions_root = "$plugin_root/actions/simplekaltura";
	elgg_register_action('simplekaltura/save', "$actions_root/save.php");
	elgg_register_action('simplekaltura/update', "$actions_root/update.php");
	elgg_register_action('simplekaltura/get_embed', "$actions_root/get_embed.php");
	elgg_register_action('simplekaltura/featured', "$actions_root/featured.php", 'admin');
	elgg_register_action('videos/delete', "$actions_root/delete.php");
	elgg_register_action('simplekaltura/migrate', "$actions_root/migrate.php", 'admin');

	// entity url and icon handlers
	elgg_register_plugin_hook_handler('entity:url', 'object', 'simplekaltura_url_handler');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'simplekaltura_icon_url_override');

	// Register type
	elgg_register_entity_type('object', 'simplekaltura_video');

	// register CRON hook to poll video plays/duration/etc..
	elgg_register_plugin_hook_handler('cron', 'fifteenmin', 'simplekaltura_bulk_update');
	
	// Most Played Sidebar
	elgg_extend_view('simplekaltura/sidebar', 'simplekaltura/featured');
	elgg_extend_view('simplekaltura/sidebar', 'simplekaltura/most_played');

	// Whitelist ajax views
	elgg_register_ajax_view('simplekaltura/popup');
	
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
			gatekeeper();
			group_gatekeeper();
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
		case 'all':
		default:
			include "$pages_dir/all.php";
			break;
	}

	return true;
}

/**
 * Returns the URL from a simplekaltura entity
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    The current URL
 * @param array  $params Hook parameters
 * @return string
 */
function simplekaltura_url_handler($hook, $type, $url, $params) {
	$entity = $params['entity'];

	// Check that the entity is a photo object
	if (!elgg_instanceof($entity, 'object', 'simplekaltura_video')) {
		return;
	}

	return "videos/view/{$entity->guid}/";
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
		return simplekaltura_build_thumbnail_url($video->kaltura_entryid, $size, $video->thumbnail_second);
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
		$item = new ElggMenuItem('simplekaltura', elgg_get_plugin_setting('kaltura_entity_title', 'simplekaltura'), $url);
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
		$admin_download = elgg_get_plugin_setting('admin_only_download', 'simplekaltura');
		$download_url = $entity->downloadUrl;
		if ($download_url) {
			if ($admin_download != 'yes' || elgg_is_admin_logged_in()) {
				$options = array(
					'name' => 'download_video',
					'text' => elgg_echo('simplekaltura:label:download'),
					'title' => 'download_video',
					'href' => $download_url,
					'priority' => 200,
				);

				$value[] = ElggMenuItem::factory($options);
			}
		}

		// feature link
		if (elgg_is_admin_logged_in()) {
			if ($entity->featured_video == "yes") {
				$url = "action/simplekaltura/featured?guid={$entity->guid}&action_type=unfeature";
				$wording = elgg_echo("simplekaltura:label:makeunfeatured");
			} else {
				$url = "action/simplekaltura/featured?guid={$entity->guid}&action_type=feature";
				$wording = elgg_echo("simplekaltura:label:makefeatured");
			}
			$options = array(
				'name' => 'feature',
				'text' => $wording,
				'href' => $url,
				'priority' => 300,
				'is_action' => true,
				'section' => 'info'
			);
			$value[] = ElggMenuItem::factory($options);
		}
	}

	return $value;
}

/**
 * Prepare a notification message about a new video
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function simplekaltura_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	// Title for the notification
	$notification->subject = elgg_echo('simplekaltura:notification:subject');

    // Message body for the notification
	$notification->body = elgg_echo('simplekaltura:notification:body', array(
		$owner->name,
		$entity->title,
		$entity->description,
		$entity->getURL()
	), $language);

    // The summary text is used e.g. by the site_notifications plugin
    $notification->summary = elgg_echo('simplekaltura:notification:summary', array($entity->title), $language);

    return $notification;
}