<?php
// this is a perfect action for the vroom plugin...

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

set_time_limit(0);

$dbprefix = elgg_get_config('dbprefix');
$name_metastring_id = add_metastring('simplekaltura_cannot_import');
$value_metastring_id = add_metastring('1');

$options = array(
	'type' => 'object',
	'subtype' => 'kaltura_video',
	'wheres' => array("NOT EXISTS ( SELECT 1 FROM {$dbprefix}metadata md WHERE md.entity_guid = e.guid AND md.name_id = $name_metastring_id AND md.value_id = $value_metastring_id)"),
	'limit' => false
);

// inc_offset = false because we take them out of the query results
$videos = new ElggBatch('elgg_get_entities', $options, '', 25, false);

$new = get_subtype_id('object', 'simplekaltura_video');
if (!$new) {
	$new = add_subtype('object', 'simplekaltura_video');
}
$old = get_subtype_id('object', 'kaltura_video');


foreach ($videos as $v) {
	if ($v->simplekaltura_cannot_import) {
		// we can't import this for some reason...
		continue;
	}
	
	// note the old plugin made mixes which are deprecated and don't play properly
	// we can get the associated source video id instead which works correctly
	$entry_id = false;
	
	// check if it's a mix (most of them are)
	try {
		$client = simplekaltura_create_client(true);
		$entry = $client->mixing->get($v->kaltura_video_id);
		$xml = new SimpleXMLElement($entry->dataContent);
		$assets = $xml->VideoAssets->vidAsset[0];
		
		if ($assets) {
			$attr = get_object_vars($xml->VideoAssets->vidAsset[0]->attributes());
			$entry_id = $attr['@attributes']['k_id'];
		}
		
	} catch (Exception $exc) {
		// do nothing yet...
	}
	
	
	// if we don't have an entry_id it could be a legit video...
	if (!$entry_id) {
		try {
			$entry = simplekaltura_get_entry($v->kaltura_video_id, true);
			if ($entry) {
				$entry_id = $v->kaltura_video_id;
			}
		} catch (Exception $exc) {
			// it's not a mix, and it's not a legit video
			// not sure what it is so lets leave it alone
			$v->simplekaltura_cannot_import = 1;
			continue;
		}
	}
	
	$v->kaltura_guid = $entry_id;
	$v->kaltura_entryid = $entry_id;
	$v->thumbnail_second = 2;
	$v->comments_on = ($v->kaltura_video_comments_on == 'Off') ? 0 : 1;
	$v->save();

	if ($new && $old) {
		$dbprefix = elgg_get_config('dbprefix');
		$sql = "UPDATE {$dbprefix}entities SET subtype = {$new} WHERE guid = {$v->guid}";
		update_data($sql);
	}
}


system_message(elgg_echo('simplekaltura:migration:complete'));
forward(REFERER);