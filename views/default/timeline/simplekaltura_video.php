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

if (!($thumbnail_url = $vars['entity']->thumbnailUrl)) {
	$thumbnail_url = get_plugin_setting('kaltura_thumbnail_url', 'simplekaltura') . $vars['entity']->kaltura_entryid;
}

$comments_count = elgg_count_comments($vars['entity']);
$likes_count = elgg_count_likes($vars['entity']);

$id = $vars['entity']->kaltura_entryid;
$pop_url = elgg_get_site_url() . "mod/simplekaltura/popwidget.php?height=330&width=400&entryid=$id";

echo  "<div style='display: none;' id='popup-dialog-$id' class='simplekaltura-popup-dialog'></div>
	<img onclick='javascript:timeline_load_popup_by_id(\"popup-dialog-$id\", \"$pop_url\")' width='153px' src='$thumbnail_url' /><br /><div class='entity_subtext timeline-entity-subtext'>
		Likes: $likes_count $views_string Comments: $comments_count
	</div>". elgg_get_excerpt($vars['entity']->description);
?>