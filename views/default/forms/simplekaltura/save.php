<?php
/**
 * Simple Kaltura Edit/Create form
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');

elgg_load_js('simplekaltura:thumbs');
elgg_load_css('simplekaltura-jquery-ui');

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

// Only include the widget and js when adding
if (!$entity) {
	$widget = elgg_view('simplekaltura/form_elements/ksu_widget');

	$new_js = <<<JAVASCRIPT
<script type='text/javascript'>
	$(document).ready(function () {
		// Init upload dialog
		$('#simplekaltura-upload-dialog').dialog({
			autoOpen: false,
			width: 400,
			height: 85,
			modal: true,
			draggable: false,
			resizeable: false,
			open: function(event, ui) {
				$(".ui-dialog-titlebar-close").hide();
			},
			closeOnEscape: false
		});

		// Click handler for save button
		$('#simplekaltura-submit').click(function(event) {
			upload();
			event.preventDefault();
		});
	});
</script>
JAVASCRIPT;
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

$submit_input = elgg_view('input/submit', array(
	'id' => 'simplekaltura-submit',
	'name' => 'save',
	'value' => elgg_echo('save'),
	'disabled' => 'DISABLED',
	'class' => 'elgg-button-submit elgg-state-disabled'
));

// Hidden inputs for kaltura entries
$k_guid_input = elgg_view('input/hidden', array(
	'name' => 'k_guid',
	'id' => 'k_guid'
));

$k_entryid_input = elgg_view('input/hidden', array(
	'name' => 'k_entryid',
	'id' => 'k_entryid'
));

$k_bytesloaded_input = elgg_view('input/hidden', array(
	'name' => 'k_bytesloaded',
	'id' => 'k_bytesloaded'
));

$k_filetype_input = elgg_view('input/hidden', array(
	'name' => 'k_filetype',
	'id' => 'k_filetype'
));

$dialog_title = elgg_echo('simplekaltura:label:uploadingdialogtitle');

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
		$k_guid_input
		$k_entryid_input
		$k_bytesloaded_input
		$k_filetype_input
	</div>
	<div class="hidden" id="simplekaltura-upload-dialog" class="elgg-lightbox" title="$dialog_title">
		<div id='simplekaltura-upload-progress'></div>
	</div>
</div>
HTML;

echo $form_body;