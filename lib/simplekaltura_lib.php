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

/**
 * Helper function to create KalturaConfiguration
 * @return KalturaConfiguration
 */
function simplekaltura_create_config() {
	$kaltura_partner_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
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
	$partner_user_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');

	//Construction of Kaltura objects for session initiation
	$config 	= simplekaltura_create_config();
	$client 	= new KalturaClient($config);
	$partner	= $client->partner->getSecrets($config->partnerId,
		elgg_get_plugin_setting('kaltura_email_account', 'simplekaltura'),
		elgg_get_plugin_setting('kaltura_password_account', 'simplekaltura')
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
	elgg_load_library('simplekaltura');
	elgg_load_library('KalturaClient');

	// Get a kaltura client
	$client = simplekaltura_create_client(true);

	// Set up pager
	$pager = new KalturaFilterPager();
	$pager->pageIndex = "1";
	$pager->pageSize = "0";

	// Set up filter
	$filter = new KalturaMediaEntryFilter();
	$filter->tagsAdminTagsMultiLikeOr = elgg_get_plugin_setting('kaltura_admin_tags', 'simplekaltura');

	// Grab entries
	$result = $client->media->listAction($filter, $pager);

	// Get elgg entities
	$options = array(
		'type' => 'object',
		'subtype' => 'simplekaltura_video',
		'limit' => 0
	);

	$ia = elgg_set_ignore_access(true);

	// just need an array of the kaltura ids and GUIDs
	$batch = new ElggBatch('elgg_get_entities', $options);
	$video_ids = array();

	foreach($batch as $video) {
		if ($video->kaltura_entryid) {
			$video_ids[$video->kaltura_entryid] = $video->getGUID();
		}
	}

	// Match up each found entry to an elgg entity
	$success = true;
	foreach ($result->objects as $entry) {
		// If it exists..
		$video_guid = elgg_extract($entry->id, $video_ids);
		if ($video_guid) {
			$videoupdate = get_entity($video_guid);
			$success &= simplekaltura_update_video($videoupdate, $entry);
		}
	}

	elgg_set_ignore_access($ia);

	return $success;
}

/**
 * Creates a friendly time for Kaltura videos.
 *
 * Converts seconds to hours
 *
 * @param type $sec
 * @param type $padHours
 * @return type string The time
 */
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
		'comments_on' => 'On',
		'entity' => $entity,
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

/**
 * Builds and returns the URL for the SWF object for a video.
 *
 * @param ElggEntity $video
 * @return string The URL for the swf object on Kaltura's server
 */
function simplekaltura_get_swf_url(ElggEntity $video) {
	if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
		return false;
	}

	$widgetid = "_" . elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	$uiConfId = elgg_get_plugin_setting('kaltura_custom_player_id', 'simplekaltura');
	$entryid = $video->kaltura_entryid;

	return "http://www.kaltura.com/index.php/kwidget"
		. "/wid/$widgetid"
		. "/uiconf_id/$uiConfId"
		. "/entry_id/$entryid"
		// append a fake filename.swf to keep things from freaking out in JSON because of
		// same origin policies.
		. '/video.swf';
}