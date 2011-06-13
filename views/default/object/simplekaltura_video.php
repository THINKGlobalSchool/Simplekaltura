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

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

$full = elgg_extract('full_view', $vars, false);
$video = elgg_extract('entity', $vars, false);

if (!$video) {
	return true;
}

$owner = $video->getOwnerEntity();
$container = $video->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$title = $video->title;
$description = $video->description;
$excerpt = elgg_get_excerpt($description);

$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$owner_link = elgg_view('output/url', array(
	'href' => "videos/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));
$tags = elgg_view('output/tags', array('tags' => $video->tags));
$date = elgg_view_friendly_time($video->time_created);

$video_icon = '';

$plays = (is_int($vars['entity']->plays)) ? $vars['entity']->plays : elgg_echo('simplekaltura:label:unavailable');

$kaltura_metadata = elgg_echo('simplekaltura:label:vidlength',
		array(simplekaltura_sec2hms($vars['entity']->duration)));

$kaltura_metadata .= elgg_echo('simplekaltura:label:vidplays', array($plays));
$id = $video->kaltura_entryid;

// The "on" status changes for comments, so best to check for !Off
$comments_link = '';

if ($video->comments_on != 'Off') {
	$comments_count = $video->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $video->getURL() . '#comments',
			'text' => $text,
		));
	}
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'videos',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "<p>$author_text $date $comments_link<br />$kaltura_metadata</p>";
$subtitle .= $categories;

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
//if ($full && !elgg_in_context('gallery')) {
	$body = elgg_view('output/longtext', array(
		'value' => $video->description
	));

	$body .= '<hr />';
	$body .= elgg_view('simplekaltura/widget', $vars);

	$header = elgg_view_title($video->title);

	$params = array(
		'entity' => $video,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/summary', $params);

	$video_info = elgg_view_image_block($owner_icon, $list_body);

	echo <<<HTML
$header
$video_info
$body
HTML;

}
/*
@todo add support for gallery view mode

elseif (elgg_in_context('gallery')) {
	echo '<div class="video-gallery-item">';
	echo "<h3>" . $video->title . "</h3>";
	echo elgg_view_entity_icon($file, 'medium');
	echo "<p class='subtitle'>$owner_link $date</p>";
	echo '</div>';
}
*/
else {
	// Grab thumbnail
	$icon = elgg_view_entity_icon($video, 'tiny');

	$pop_url = elgg_get_site_url() . "mod/simplekaltura/popwidget.php?height=330&width=100%25&autoplay=true&entryid=$id";
//	$icon = "<img onclick='javascript:video_listing_load_popup_by_id(\"popup-dialog-$id\", \"$pop_url\")' src='$thumbnail_url' />";

	$params = array(
		'entity' => $video,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('page/components/summary', $params);

	echo elgg_view_image_block($icon, $list_body);
}



return true;







// Get entity info
$title = $vars['entity']->title;
$owner = $vars['entity']->getOwnerEntity();
$owner_video_link = "<a href=\"" . elgg_get_site_url() . "pg/videos/$owner->username\">{$owner->name}</a>";
$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
$owner_text = elgg_echo('simplekaltura:label:posted_by', array($owner_video_link)) . ' ' . $friendlytime;
$address = $vars['entity']->getURL();
$parsed_url = parse_url($address);
$object_acl = elgg_view('output/access', array('entity' => $vars['entity']));

// Do an update
// @todo why?
simplekaltura_update_video($vars['entity']);

// Comments
$comments_count = elgg_count_comments($vars['entity']);

if ($comments_count != 0) { // only display if there are commments
	$comments_link = " | <a href=\"{$vars['entity']->getURL()}#annotations\">" . elgg_echo("comments") . " (". $comments_count .")</a>";
}else{
	$comments_link = '';
}

// Edit/Delete
$edit = $object_acl;
if ($vars['entity']->canEdit()) {
	$edit_url = elgg_get_site_url()."pg/videos/edit/{$vars['entity']->getGUID()}/";
	$edit_link = "<span class='entity_edit'><a href=\"$edit_url\">" . elgg_echo('edit') . '</a></span>';

	$delete_url = "action/simplekaltura/delete?guid=" . $vars['entity']->guid;
	$delete_link .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array(
				'href' => $delete_url,
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("simplekaltura:label:deleteconfirm"),
				)) . "</span>";

	$edit .= "$edit_link $delete_link";
}

// View to override
$edit .= elgg_view("simplekaltura/options", array('entity' => $vars['entity']));

// Add favorites
// @todo not supported in 1.8 and will probably be a menu anyway
//$favorites .= elgg_view("favorites/form",array('entity' => $vars['entity']));

// Tags
$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
if (!empty($tags)) {
	$tags = '<p class="tags">' . $tags . '</p>';
}



if ($vars['full']) { // Full view
	// Owner Icon
	$owner_icon = elgg_view('profile/icon', array('entity' => $owner, 'size' => 'tiny'));

	$vars['entryid'] = $vars['entity']->kaltura_entryid;

	// Kaltura Widget
	$widget = elgg_view('simplekaltura/widget', $vars);

	// Display content
	echo <<<___END
	<div class="simplekaltura_video clearfix">
		<div id="content_header" class="clearfix">
			<div class="content_header_title"><h2>$title</h2></div>
		</div>
		<div class="clearfix">
		<div class="entity_listing_icon">
			$owner_icon
		</div>
		<div class="entity_listing_info">
			<div class="entity_metadata">
				$edit
				$favorites
				$likes
			</div>
			<p class="entity_subtext">
				$owner_text
				$date
				$comments_link

			</p>
			$tags
			<p class='kaltura-meta'>
				$kaltura_meta
			</p>
		</div>
		</div>
		<div class='simplekaltura-video-description'>{$vars['entity']->description}</div>
		<div class='simplekaltura-video-container'>
			$widget
		</div>
		<div class='simplekaltura-video-footer'>$download_link</div>
	</div>
___END;
} else {	// Listing
	// Grab thumbnail
	if (!($thumbnail_url = $vars['entity']->thumbnailUrl)) {
		$thumbnail_url = get_plugin_setting('kaltura_thumbnail_url', 'simplekaltura') . $vars['entity']->kaltura_entryid;
	}

	// View description pop-down
	if ($vars['entity']->description != '') {
		$view_desc = "| <a class='link' onclick=\"elgg_slide_toggle(this,'.entity_listing','.note');\">" . elgg_echo('description') . "</a>";
		$description = "<div class='note hidden'>". $vars['entity']->description . "</div>";
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
		$owner_text
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
	echo "<div class='simplekaltura'>" . elgg_view_listing($icon, $info) . "</div>";
}
