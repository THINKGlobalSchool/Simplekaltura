<?php
/**
 * Simple Kaltura Thumbnail select input
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['entity']
 * @uses $vars['name']
 */

$video = elgg_extract('entity', $vars);

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	return;
}

$thumbnail_second = $video->thumbnail_second;

// Get field name
$name = elgg_extract('name', $vars);

if (!$name) {
	$name = 'thumbnail_second';
}

// Get video duration
$duration = $video->duration;

// Get current thumbnail
$current_thumb = elgg_view('output/img', array(
	'src' => simplekaltura_build_thumbnail_url($video->kaltura_entryid, 'large', $thumbnail_second),
));

$select_thumb = elgg_view('output/img', array(
	'src' => simplekaltura_build_thumbnail_url($video->kaltura_entryid, 'large', 1),
	'id' => 'simplekaltura-select-thumbnail',
));

$current_label = elgg_echo('simplekaltura:label:currentthumbnail');
$select_label = elgg_echo('simplekaltura:label:selectthumbnail');

$thumbnail_second_input = elgg_view('input/text', array(
	'text' => elgg_echo('simplekaltura:label:second'),
	'value' => $thumbnail_second,
	'name' => $name,
	'id' => 'thumbs-name',
));

$content = <<<HTML
	<div class='simplekaltura-edit-thumbnails'>
		<div class='simplekaltura-edit-thumbnail'>
			<div class='pbm'>
				<label>$current_label</label>
			</div>
			$current_thumb
		</div>
		<div class='simplekaltura-edit-thumbnail'>
			<div class='pbm'>
				<label>$select_label</label>
			</div>
			$select_thumb<br />
			<span class='elgg-subtext'></span>
			<div id='simplekaltura-thumbnail-slider' class='mas'><span class='duration hidden'>$duration</span></div>
		</div>
	</div>
	<div class='clearfix hidden'>$thumbnail_second_input</div>

HTML;

echo $content;