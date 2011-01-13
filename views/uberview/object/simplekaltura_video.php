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

$icon = "<img style='height: 100px; width: 140px;' onclick='javascript:simplekaltura_show_popup_$id()' src='$thumbnail_url' />";

$pop_url = elgg_get_site_url() . "mod/simplekaltura/popwidget.php?height=330&width=400";



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
<div id='popup_dialog_$id' class='simplekaltura_popup_dialog'></div>
<script type='text/javascript'>

$("#popup_dialog_$id").dialog({
					autoOpen: false,
					width: 500, 
					modal: true,
					open: function(event, ui) { 
						console.log('opening');
						$(".ui-dialog-titlebar-close").hide(); 	
					},
					buttons: {
						"X": function() { 
							$(this).dialog("close"); 
						} 
}});

function simplekaltura_show_popup_$id() {
	$("#popup_dialog_$id").dialog("open");
	$("#popup_dialog_$id").load('$pop_url&entryid=' + '$id');
}

</script>
___END;
echo "<div class='simplekaltura simplekaltura-ubergallery'>" . $icon . $info . "</div>";



