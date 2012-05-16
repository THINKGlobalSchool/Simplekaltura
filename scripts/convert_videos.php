<?php
/** Convert Existing Kaltura Entries **/
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
global $CONFIG;
admin_gatekeeper();

set_time_limit(0);

$go = get_input('go', FALSE);

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

// Get a kaltura client
$client = simplekaltura_create_client(TRUE);

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


// just need an array of the kaltura ids and GUIDs
$batch = new ElggBatch('elgg_get_entities', $options);
$video_ids = array();

foreach($batch as $video) {
	if ($video->kaltura_entryid) {
		$video_ids[$video->kaltura_entryid] = $video->getGUID();
	}
}

$new_flavors = array(
	'301951', // Mobile (H264) Basic
	'301961', // Mobile (H264) Standard
	'301971'  // iPad
);

echo "<h1>CONVERT KALTURA ENTRIES</h1><pre>";

// Match up each found entry to an elgg entity
$count = 0;
foreach ($result->objects as $entry) {
	// If it exists..
	$video_guid = elgg_extract($entry->id, $video_ids);
	if ($video_guid) {
		$count++;
		// Get elgg entity
		$videoupdate = get_entity($video_guid);
		
		// Echo info
		echo "<br />" . $videoupdate->guid . ' - ' . $videoupdate->title . "({$entry->id})<br />";
				
		// Echo entries
		$flavors = string_to_tag_array($entry->flavorParamsIds);
		echo "<br/>	Flavors: <br />";
		
		foreach ($flavors as $flavor) {
			echo "		$flavor<br />";
		}
		
		foreach ($new_flavors as $flavor) {
			if (!in_array($flavor, $flavors)) {	
				if ($go) {
					$client->flavorAsset->convert($entry->id, $flavor);
					$action = "<-- CONVERTED!";
				} else {
					$action = "<-- TO ADD";
				}
				
				echo "		$flavor $action<br />";
			}
		}
	}
}

echo "Total: {$count}<br />";


echo "</pre>";