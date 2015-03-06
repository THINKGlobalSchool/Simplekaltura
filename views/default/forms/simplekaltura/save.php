<?php
/**
 * Simple Kaltura Edit/Create form
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

elgg_load_js('simplekaltura:thumbs');
elgg_load_css('elgg.jquery.ui');

$title = elgg_extract('title', $vars);
$description = elgg_extract('description', $vars);
$tags = elgg_extract('tags', $vars);
$access_id = elgg_extract('access_id', $vars, ACCESS_LOGGED_IN);
$guid = elgg_extract('guid', $vars);
$container_guid = elgg_extract('container_guid', $vars, elgg_get_page_owner_guid());
$entity = elgg_extract('entity', $vars);
$comments_on = elgg_extract('comments_on', $vars, 'On');
$thumbnail_second = elgg_extract('thumbnail_second', $vars);

$widget = '';

// inital submit options
$submit_options = array(
	'id' => 'simplekaltura-submit',
	'name' => 'save',
	'value' => elgg_echo('save'),
	'class' => 'elgg-button-submit'
);

// Only include the widget and js when adding
if (!$entity) {
	$widget = elgg_view('simplekaltura/form_elements/ksu_widget');

	// Disable the submit button initially, this will be enabled later once a video is selected
	$submit_options['disabled'] = 'DISABLED';
	$submit_options['class'] .= ' elgg-state-disabled';
} else {
	// Get entry to check status
	$entry = simplekaltura_get_entry($entity->kaltura_entryid);
	
	// Check for ready status
	if ($entry->status == KalturaEntryStatus::READY) {
		// Update local entity
		simplekaltura_update_video($entity);

		$thumbnail_input = elgg_view('input/simplekaltura_thumbs', array(
			'entity' => $entity,
		));

		$thumbnail_content = "<div>
      			$thumbnail_input
			</div><br />";
	} else {
		echo "<div class='elgg-message elgg-state-notice'>" . elgg_echo('simplekaltura:notconverted') . "</div>";
	}
}

$container_hidden = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $container_guid
));

$entity_hidden  = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $guid
));

// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'id' => 'video_title',
	'name' => 'video_title',
	'value' => $title
));

$description_label = elgg_echo("simplekaltura:label:description");
$description_input = elgg_view("input/longtext", array(
	'name' => 'video_description',
	'value' => $description
));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array(
	'id' => 'video_tags',
	'name' => 'video_tags',
	'value' => $tags
));

$access_label = elgg_echo('access');
$access_content = elgg_view('input/access', array(
	'name' => 'video_access',
	'id' => 'video_access',
	'value' => $access_id
));

$comments_label = elgg_echo('comments');
$comments_content = elgg_view('input/dropdown', array(
	'name' => 'comments_on',
	'value' => $comments_on,
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$submit_input = elgg_view('input/submit', $submit_options);

// Build Form Body
$form_body = <<<HTML
$new_js
<div class='simplekaltura'>
	<div id='video_title_container'>
		<label>$title_label</label><br />
        $title_input
	</div><br />
	$thumbnail_content
	<div>
		<label>$description_label</label><br />
        $description_input
	</div><br />
	<div>
		$widget
	</div><br />
	<div>
		<label>$tag_label</label><br />
        $tag_input
	</div><br />
	<div>
		<label>$comments_label</label><br />
		$comments_content
	</div><br />
	<div>
		<label>$access_label</label><br />
		$access_content
	</div><br />
	<div>
		$submit_input
		$container_hidden
		$entity_hidden
	</div>
</div>
HTML;

echo $form_body;