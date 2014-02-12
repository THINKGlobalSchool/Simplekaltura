<?php

set_time_limit(0);

$options = array(
	'type' => 'object',
	'subtype' => 'kaltura_video',
	'limit' => false
);

$videos = new ElggBatch('elgg_get_entities', $options);


foreach ($videos as $v) {
	$v->kaltura_guid = $v->kaltura_video_id;
	$v->kaltura_entryid = $v->kaltura_video_id;
	$v->thumbnail_second = 2;
	$v->comments_on = ($v->kaltura_video_comments_on == 'Off') ? 0 : 1;
	$v->save();
//	
//	$video->kaltura_bytesloaded = $k_bytesloaded;
//	$video->kaltura_filetype = $k_filetype;
}


$new = get_subtype_id('object', 'simplekaltura_video');
if (!$new) {
	$new = add_subtype('object', 'simplekaltura_video');
}
$old = get_subtype_id('object', 'kaltura_video');

if ($new && $old) {
	$dbprefix = elgg_get_config('dbprefix');
	$sql = "UPDATE {$dbprefix}entities SET subtype = {$new} WHERE type = 'object' AND subtype = {$old}";
	update_data($sql);
}

system_message('Kaltura videos have been migrated');
forward(REFERER);
