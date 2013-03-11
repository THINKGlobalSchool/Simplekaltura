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
 * @uses $vars['thumbnail_second']
 * @uses $vars['video_guid']
 */

$video = get_entity(elgg_extract('video_guid', $vars));

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	return;
}

// Get thumbnail second
$thumbnail_second = elgg_extract('thumbnail_second', $vars);

// Default to video's thumbnail second value if unset
if (!$thumbnail_second) {
	$thumbnail_second = $video->thumbnail_second;
}

// Get field name
$name = elgg_extract('name', $vars);

if (!$name) {
	$name = 'thumbnail_second';
}

// Get video duration
$duration = $video->duration;

// Generate three random thumbnails
$random_sec1 = rand(1, $duration);
$random_thumb1 = elgg_view('output/img', array(
	'src' => simplekaltura_build_thumbnail_url($video->kaltura_entryid, 'medium', $random_sec1),
));

$random_sec2 = rand(1, $duration);
$random_thumb2 = elgg_view('output/img', array(
	'src' => simplekaltura_build_thumbnail_url($video->kaltura_entryid, 'medium', $random_sec2),
));

$random_sec3 = rand(1, $duration);
$random_thumb3 = elgg_view('output/img', array(
	'src' => simplekaltura_build_thumbnail_url($video->kaltura_entryid, 'medium', $random_sec3),
));

$thumbnail_second_label = elgg_echo('simplekaltura:label:thumbnailsecond');
$thumbnail_second_input = elgg_view('input/text', array(
	'text' => elgg_echo('simplekaltura:label:second'),
	'value' => $thumbnail_second,
	'name' => $name,
	'id' => 'thumbs-name',
));

// Regenerate thumbs button
$regenerate_button = elgg_view('input/button', array(
	'value' => elgg_echo('simplekaltura:label:regenerate'),
	'class' => 'simplekaltura-regenerate-thumbs elgg-button-action',
)); 

// Hidden video guid
$video_hidden = elgg_view('input/hidden', array(
	'name' => 'video_guid',
	'value' => $video->guid
));

$content = <<<HTML
	<div class='simplekaltura-random-thumbnails'>
		<div class='simplekaltura-random-thumbnail'>
			$random_thumb1<br />
			<input type="radio" name='thumbnail_second_radio' value="$random_sec1" />
		</div>
		<div class='simplekaltura-random-thumbnail'>
			$random_thumb2<br />
			<input type="radio" name='thumbnail_second_radio' value="$random_sec2" />
		</div>
		<div class='simplekaltura-random-thumbnail'>
			$random_thumb3<br />
			<input type="radio" name='thumbnail_second_radio' value="$random_sec3" />
		</div>
	</div>
	$regenerate_button
	$video_hidden
	<div class='clearfix'></div>
	<div>
		<label>$thumbnail_second_label</label><br />
		$thumbnail_second_input
	</div>
HTML;

echo $content;