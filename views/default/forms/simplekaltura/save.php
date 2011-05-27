<?php
/**
 * Simple Kaltura Edit/Create form
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$title = elgg_extract('title', $vars);
$description = elgg_extract('description', $vars);
$tags = elgg_extract('tags', $vars);
$access_id = elgg_extract('access_id', $vars, ACCESS_LOGGED_IN);
$guid = elgg_extract('guid', $vars);
$container_guid = elgg_extract('container_guid', $vars);
$entity = elgg_extract('entity', $vars);

$widget = '';

// Only include the widget and js when adding
if (!$entity) {
	$widget = elgg_view('simplekaltura/form_elements/ksu_widget');

	$new_js = <<<JS
<script type='text/javascript'>
	$(document).ready(function () {
		$('#simplekaltura_submit').attr('disabled', 'disabled');
		$('#simplekaltura_submit').addClass('disabled');
		$('#simplekaltura-upload-dialog').dialog({
			autoOpen: false,
			width: 400,
			modal: true,
			draggable: false,
			resizeable: false,
			open: function(event, ui) {
				$(".ui-dialog-titlebar-close").hide();
			},
			closeOnEscape: false
		});
		$('#simplekaltura_submit').click(function () {
			upload();
			return false;
		});
	});
</script>
JS;
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

$submit_input = elgg_view('input/submit', array(
	'id' => 'simplekaltura_submit',
	'name' => 'save',
	'value' => elgg_echo('save')
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

// Build Form Body
$form_body = <<<EOT

$new_js

<div class='margin_top simplekaltura'>
	<div id='video_title_container'>
		<label>$title_label</label><br />
        $title_input
	</div><br />
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
	<div class="hidden" id="simplekaltura-upload-dialog">
		<p>Uploading... <span id="simplekaltura-upload-percent"></span></p>
		<div id='simplekaltura-upload-progress'></div>
	</div>
</div>

EOT;

echo $form_body;