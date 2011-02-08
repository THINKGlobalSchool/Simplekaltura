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
			
			$title = elgg_echo('simplekaltura:title:editvideo');

			elgg_push_breadcrumb($video->title, $video->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view_title($title) . elgg_view('simplekaltura/forms/edit', $vars);
	
		} else {
			$content = elgg_echo('simplekaltura:error:notfound');
		}
	} else {
		$title = elgg_echo('simplekaltura:title:uploadnew');
		if (!$guid) {
			$container = get_loggedin_user();
		} else {
			$container = get_entity($guid);
		}
		elgg_set_page_owner_guid($container->guid);
		
		elgg_push_breadcrumb(elgg_echo('simplekaltura:label:new'));
		$content =  elgg_view_title($title) . elgg_view('simplekaltura/forms/edit', $vars);
	}


	return array('content' => $content, 'title' => $title, 'layout' => 'one_column_with_sidebar');
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
 * @param bool $admin - wether or not to use an admin session
 * @return KalturaClient
 */
function simplekaltura_create_client($admin = false) {
	// Get settings
	$partner_user_id = get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	
	//Construction of Kaltura objects for session initiation
	$config 	= simplekaltura_create_config();
	$client 	= new KalturaClient($config);
	$partner	= $client->partner->getSecrets($config->partnerId, 
											get_plugin_setting('kaltura_email_account', 'simplekaltura'), 
											get_plugin_setting('kaltura_password_account', 'simplekaltura')
											);
	
	if ($admin) {
		$st = KalturaSessionType::ADMIN;
		$secret = $partner->adminSecret;
	} else {
		$st = KalturaSessionType::USER;
		$secret = $partner->secret;
	}									
	
	$ks			= $client->session->start($secret, $partner_user_id, $st);
	
	// Set KS
	$client->setKs($ks);
	return $client;
}

/** 
 * Helper function to grab a kaltura entry 
 * @param string 	$entry_id - Entry to grab
 * @param bool 		$admin - Admin session 
 * @return KalturaMediaEntry 
 */
function simplekaltura_get_entry($entry_id, $admin = false) {
	$client = simplekaltura_create_client($admin);
	$entry = $client->media->get($entry_id);
	return $entry;
}

/** 
 * Helper function to update a kaltura entry
 * @param string 				$entry_id 	- Entry to update
 * @param KalturaMediaEntry		$entry 		- Entry object
 * @param bool					$admin		- Wether or not to use an admin session
 * @return bool
 */
function simplekaltura_update_entry($entry_id, KalturaMediaEntry $entry, $admin = false) {
	$client = simplekaltura_create_client($admin);
	$entry = $client->media->update($entry_id, $entry);
	return TRUE;
}

/** 
 * Helper function to delete a simplekaltura video 
 * and the corresponding kaltura entry
 * @param ElggObject $video - Video to delete
 * @return true 
 */
function simplekaltura_delete_video($video) {
	$success = true;
		
	// Delete Kaltura Object
	try {
		$client = simplekaltura_create_client(true);
		$client->media->delete($video->kaltura_entryid);
	} catch (Exception $e) {
		$success = false;
	}
	
	// Delete Elgg Object
	$success &= $video->delete();
	
	return $success;	
}

/**
 * Helper function to update a simplekaltura video object
 * @param ElggObject 		$video - The elgg object to update
 * @param KalturaMediaEntry	$entry - The kaltura entry, or null if none
 * @return bool 
 */
function simplekaltura_update_video($video, $entry = null) {
	// Don't necessarily need an entry here, if updating an existing video
	if (!$entry) {
		// Assume we're grabbing the entry of the current video
		$entry = simplekaltura_get_entry($video->kaltura_entryid, true);
	}
		
	// Set up an array of metadata mappings to populate
	$metadata_names = array(
		'duration',
		'thumbnailUrl', 
		'plays',
		'views',
		'downloadUrl'
	);
	
	// Set metadata
	foreach ($metadata_names as $name) {
		create_metadata(
			$video->getGUID(), 
			$name, 
			$entry->$name, 
			'', 
			$video->getOwnerGUID(),
			$video->access_id,
			FALSE
		);
	}
	
	// Store the whole shebang just in case (?? not sure if I need this..)
	create_metadata(
		$video->getGUID(), 
		'raw_entry', 
		serialize($entry), 
		'', 
		$video->getOwnerGUID(),
		$video->access_id, 
		FALSE
	);
	return true;
}

/** 
 * Cron function to update all simplekaltura_video objects
 */
function simplekaltura_bulk_update() {
	
	// Get a kaltura client
	$client = simplekaltura_create_client(true);
	
	// Set up pager
	$pager = new KalturaFilterPager();
	$pager->pageIndex = "1";
	$pager->pageSize = "0";
	
	// Set up filter
	$filter = new KalturaMediaEntryFilter();
	$filter->tagsAdminTagsMultiLikeOr = get_plugin_setting('kaltura_admin_tags', 'simplekaltura');
	
	// Grab entries
	$result = $client->media->listAction($filter, $pager);
	
	// Get elgg entities
	$params = array(
		'type' => 'object',
		'subtype' => 'simplekaltura_video',
		'limit' => 0
	);
	
	
	// Ignore access here to grab and work with elgg objects
	$access = elgg_get_access_object();
	$ia = $access->getIgnoreAccess();
	$access->setIgnoreAccess(true);
	
	$videos = elgg_get_entities($params);
	
	// Create array of video entry_ids from elgg entities
	$video_ids = array();
	foreach($videos as $video) {
		if ($video->kaltura_entryid) {
			$video_ids[$video->kaltura_entryid] = $video->getGUID();
		}
	}
	
	// Match up each found entry to an elgg entity
	$success = true;
	foreach ($result->objects as $entry) {
		// If it exists.. 
		if ($video_guid = $video_ids[$entry->id]) {
			$videoupdate = get_entity($video_guid);
			$success &= simplekaltura_update_video($videoupdate, $entry);
		}
	}
	
	$access->setIgnoreAccess($ia);
	
	return $success;
}

function simplekaltura_sec2hms ($sec, $padHours = false) {
	if ($sec) {
	    $hms = "";
    
	    // do the hours first
	    $hours = intval(intval($sec) / 3600); 

	    // add hours to $hms (with a leading 0 if asked for)
	    $hms .= ($padHours) 
	          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
	          : $hours. ":";
    
	    // minutes
	    $minutes = intval(($sec / 60) % 60); 

	    // add minutes to $hms (with a leading 0 if needed)
	    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

	    // seconds
	    $seconds = intval($sec % 60); 

	    // add seconds to $hms (with a leading 0 if needed)
	    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

	    // done!
	    return $hms;
	} else {
		return elgg_echo('simplekaltura:label:unavailable');
	}
}



?>