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

	$body .= '<div class="elgg-kaltura-player center">';
	$body .= elgg_view('simplekaltura/widget', $vars);
	$body .= "<br />$download_link</div>";

	if ($video->comments_on != 'Off') {
		$body .= '<a name="comments"></a>';
		$body .= elgg_view_comments($video);
	}

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

} else {
	// for the popup display
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');

	// Grab thumbnail but make it popup the video player instead of link to the Elgg view
	$pop_url = simplekaltura_get_swf_url($video);

	$icon = elgg_view_entity_icon($video, 'medium', array(
		'href' => $pop_url,
		'link_class' => 'simplekaltura-lightbox',
		'title' => 'simplekaltura_lightbox',
	));

	if (elgg_in_context('gallery')) {	
		$icon = elgg_view('output/img', array(
			'src' => $video->getIconURL('medium'),
			'alt' => $title,
		));
		
		$url = $video->getURL();
		$trunc_title = elgg_get_excerpt($video->title, 75);
		
		$content = <<<HTML
			<div class='simplekaltura-video-gallery-item'>
				<h3><a href="$url">$trunc_title</a></h3>
				<div class='simplekaltura-video-gallery-icon'>
					<a href="$pop_url" class="elgg-lightbox" title="$video->title">$icon</a>
				</div>
				<div class='elgg-subtext'>$author_text $date</div>
			</div>
HTML;

		echo $content;
	} else {
		$params = array(
			'entity' => $video,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
			'content' => $excerpt
		);
		$list_body = elgg_view('page/components/summary', $params);

		echo elgg_view_image_block($icon, $list_body);
	}
}