<?php
/**
 * Timeline view for simplekaltura videos
 *
 * @package Ubertags
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
elgg_load_library('simplekaltura');

if (!($thumbnail_url = $vars['entity']->thumbnailUrl)) {
	$thumbnail_url = get_plugin_setting('kaltura_thumbnail_url', 'simplekaltura') . $vars['entity']->kaltura_entryid;
}

$comments_count = elgg_count_comments($vars['entity']);
$likes_count = likes_count($vars['entity']);

$pop_url = simplekaltura_get_swf_url($vars['entity']);

echo  "<a href='$pop_url' class='tagdashboards-lightbox'><img width='153px' src='$thumbnail_url' /></a><br />
	<div class='elgg-subtext timeline-entity-subtext'>
		Likes: $likes_count $views_string Comments: $comments_count
	</div>". elgg_get_excerpt($vars['entity']->description);
