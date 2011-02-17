<?php
/**
 * Simple Kaltura Video Entity Display
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

// Get entity info
$title = $vars['entity']->title;
$owner = $vars['entity']->getOwnerEntity();
$owner_video_link = "<a href=\"".elgg_get_site_url()."pg/videos/$owner->username\">{$owner->name}</a>";
$address = $vars['entity']->getURL();
$parsed_url = parse_url($address);

$kaltura_meta = elgg_echo('simplekaltura:label:vidlength', array(simplekaltura_sec2hms($vars['entity']->duration)));

// Ubertags listing
// Grab thumbnail
if (!($thumbnail_url = $vars['entity']->thumbnailUrl)) {
	$thumbnail_url = get_plugin_setting('kaltura_thumbnail_url', 'simplekaltura') . $vars['entity']->kaltura_entryid;
} 

$id = $vars['entity']->kaltura_entryid;

$pop_url = elgg_get_site_url() . "mod/simplekaltura/popwidget.php?height=330&width=100%25&autoplay=true&entryid=$id";

$icon = "<img onclick='javascript:video_listing_load_popup_by_id(\"popup-dialog-$id\", \"$pop_url\")' src='$thumbnail_url' />";

$info = <<<___END
<div class='entity_metadata'>
	$edit 
	$favorites 
	$likes
</div>
<p class='entity_title'>
	<a  href="$address">$title</a>
</p>
<p class='entity_subtext'>
	$owner_video_link
	$date
	$view_desc
	$comments_link
</p>
$tags
<p class='kaltura-meta'>
	$kaltura_meta
</p>
$description
<div id='popup-dialog-$id' class='simplekaltura-popup-dialog'></div>
___END;
echo "<div class='simplekaltura simplekaltura-ubergallery'>" . $icon . $info . "</div>";



