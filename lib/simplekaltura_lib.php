<?php
/**
 * Simple Kaltura  helper library
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

require_once(elgg_get_plugin_path() . "simplekaltura/vendors/kaltura_client_v3/KalturaClient.php"); 

/* Get edit/create content */
function simplekaltura_get_page_content_edit($page_type, $guid) {
	$vars = array();
	if ($page_type == 'edit') {
		$video = get_entity((int)$guid);

		if (elgg_instanceof($video, 'object', 'simplekaltura_video') && $video->canEdit()) {
			$vars['entity'] = $video;

			elgg_push_breadcrumb($video->title, $video->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view('simplekaltura/forms/edit', $vars);
	
		} else {
			$content = elgg_echo('simplekaltura:error:notfound');
		}
	} else {
		if (!$guid) {
			$container = get_loggedin_user();
		} else {
			$container = get_entity($guid);
		}
		elgg_set_page_owner_guid($container->guid);
		
		elgg_push_breadcrumb(elgg_echo('simplekaltura:label:new'));
		$content = elgg_view('simplekaltura/forms/edit', $vars);
	}


	return array('content' => $content, 'title' => elgg_echo('simplekaltura:title:uploadnew'), 'layout' => 'one_column_with_sidebar');
}

/* View a video  */
function simplekaltura_get_page_content_view($guid) {
	$video = get_entity($guid);
	$owner = get_entity($video->container_guid);
	set_page_owner($owner->getGUID());
	elgg_push_breadcrumb($owner->name, elgg_get_site_url() . 'pg/videos/owner/' . $owner->username);
	elgg_push_breadcrumb($video->title, $video->getURL());
	$return['title'] = $video->title;
	$return['content'] = elgg_view_entity($video, true);
	$return['layout'] = 'one_column_with_sidebar';
	
	return $return;
}

/**
 * Get page components to list a user's or all simple kaltura videos
 *
 * @param int $owner_guid The GUID of the page owner or NULL for all videos
 * @return array
 */
function simplekaltura_get_page_content_list($container_guid = NULL) {

	$return = array();
	$return['layout'] = 'one_column_with_sidebar'; // @TODO Temporary.. until we're up to latest code level

	$options = array(
		'type' => 'object',
		'subtype' => 'simplekaltura_video',
		'full_view' => FALSE,
		'limit' => 5
	);

	$loggedin_userid = get_loggedin_userid();
	if ($container_guid) {
		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		if (!$container) {

		}
		$return['title'] = elgg_echo('simplekaltura:title:uservideos', array($container->name));
		elgg_set_page_owner_guid($container_guid);

		$crumbs_title = elgg_echo('simplekaltura:ownedvideos', array($container->name));
		elgg_push_breadcrumb($crumbs_title);

		if ($container_guid == $loggedin_userid) {
			$return['filter_context'] = 'mine';
		} 

		/* Groups..
		if (elgg_instanceof($container, 'group')) {
			$return['filter'] = '';
			if ($container->isMember(get_loggedin_user())) {
				$url = "pg/videos/new/$container->guid";
				$params = array(
					'href' => $url,
					'text' => elgg_echo("blog:new"),
					'class' => 'elgg-action-button',
				);
				$buttons = elgg_view('output/url', $params);
				$return['buttons'] = $buttons;
			}
		}
		*/
	} else {
		$return['filter_context'] = 'everyone';
		$return['title'] = elgg_echo('simplekaltura:title:allvideos');
	}
	
	$header = elgg_view('page_elements/content_header', array(
		'context' => $return['filter_context'],
		'type' => 'simplekaltura_video',
		'all_link' => elgg_get_site_url() . "pg/videos",
		'mine_link' => elgg_get_site_url() . "pg/videos/owner/" . get_loggedin_user()->username,
		'friend_link' => elgg_get_site_url() . "pg/videos/friends/" . get_loggedin_user()->username,
		'new_link' => elgg_get_site_url() . "pg/videos/new/" . $container_guid,
	));
	
	if ($container_guid && ($container_guid != $loggedin_userid)) {
		// do not show content header when viewing other users' posts
		$header = elgg_view('page_elements/content_header_member', array('type' => 'Videos'));
	}
	

	$list = elgg_list_entities($options);
	if (!$list) {
		$return['content'] = elgg_view('simplekaltura/noresults');
	} else {
		$return['content'] = $list;
	}
	
	$return['content'] = $header . $return['content'];

	return $return;
}

/**
 * Get page components to list of the user's friends' video posts
 *
 * @param int $user_guid
 * @return array
 */
function simplekaltura_get_page_content_friends($user_guid) {

	elgg_set_page_owner_guid($user_guid);
	$user = get_user($user_guid);

	$return = array();

	$return['filter_context'] = 'friends';
	$return['title'] = elgg_echo('simplekaltura:title:friendsvideos');
	$return['layout'] = 'one_column_with_sidebar';

	$crumbs_title = elgg_echo('simplekaltura:ownedvideos', array($user->name));
	elgg_push_breadcrumb($crumbs_title, "pg/videos/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('simplekaltura:label:friends'));

	if (!$friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		$return['content'] .= elgg_echo('friends:none:you');
		return $return;
	} else {
		$options = array(
			'type' => 'object',
			'subtype' => 'simplekaltura_video',
			'full_view' => FALSE,
			'limit' => 5
		);

		foreach ($friends as $friend) {
			$options['container_guids'][] = $friend->getGUID();
		}

		$list = elgg_list_entities($options);
		if (!$list) {
			$return['content'] = elgg_view('simplekaltura/noresults');
		} else {
			$return['content'] = $list;
		}
	}

	$header = elgg_view('page_elements/content_header', array(
		'context' => $return['filter_context'],
		'type' => 'simplekaltura_video',
		'all_link' => elgg_get_site_url() . "pg/videos",
		'mine_link' => elgg_get_site_url() . "pg/videos/owner/" . get_loggedin_user()->username,
		'friend_link' => elgg_get_site_url() . "pg/videos/friends/" . get_loggedin_user()->username,
		'new_link' => elgg_get_site_url() . "pg/videos/new/" . $container_guid,
	));
	
	$return['content'] = $header . $return['content'];
	
	return $return;
}


/** 
 * Helper function to create KalturaConfiguration
 * @return KalturaConfiguration
 */ 
function simplekaltura_create_config() {
	$kaltura_partner_id = get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	$config		= new KalturaConfiguration($kaltura_partner_id);
	return $config;
}

/**
 * Helper function to generate the KS (kaltura session) 
 * @return KalturaClient
 */
function simplekaltura_create_client() {
	// Get settings
	$partner_user_id = get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	
	//Construction of Kaltura objects for session initiation
	$config 	= simplekaltura_create_config();
	$client 	= new KalturaClient($config);
	$partner	= $client->partner->getSecrets($config->partnerId, 
											get_plugin_setting('kaltura_email_account', 'simplekaltura'), 
											get_plugin_setting('kaltura_password_account', 'simplekaltura')
											);
	$ks			= $client->session->start($partner->secret, $partner_user_id, KalturaSessionType::USER);
	
	// Set KS
	$client->setKs($ks);
	return $client;
}

/**
 * Helper function to update a simplekaltura video object
 * @param ElggObject $video - The elgg object to update
 * @return bool 
 */
function simplekaltura_update_video($video) {
	// May be an exception if something is wrong
	try {
		// Get a kaltura API client
		$client = simplekaltura_create_client();
		// Get the entry object to gather more info
		$entry = $client->baseEntry->get($video->kaltura_entryid);
		
		// Set up an array of metadata mappings to populate
		$metadata_names = array(
			'msDuration',
			'thumbnailUrl', 
			'plays',
			'views'
		);
		
		// Set metadata
		foreach ($metadata_names as $name) {
			create_metadata(
				$video->getGUID(), 
				$name, 
				$entry->$name, 
				'', 
				$video->getOwnerGUID(),
				$video->access_id
			);
		}
		
		// Store the whole shebang just in case (?? not sure if I need this..)
		create_metadata(
			$video->getGUID(), 
			'raw_entry', 
			serialize($entry), 
			'', 
			$video->getOwnerGUID(),
			$video->access_id
		);
		return true;
	} catch (Exception $e) {
		// @TODO Log it? Or something?
		return false;
	}
}

?>