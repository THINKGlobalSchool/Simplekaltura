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
	
// Check if we've got an entity, if so, we're editing.
if (isset($vars['entity'])) {
	
	if (!$vars['entity']) {
		forward(REFERER);
	}
	
	$action 			= "simplekaltura/edit";
	$title 		 		= $vars['entity']->title;
	$description 		= $vars['entity']->description;
	$tags 				= $vars['entity']->tags;
	$access_id			= $vars['entity']->access_id;
	
	// Hidden guid input
	$entity_hidden  = elgg_view('input/hidden', array(
		'internalname' => 'video_guid',
		 'value' => $vars['entity']->getGUID()
	));

} else {
	// No entity, creating new one
	$action 			= "simplekaltura/save";
	$title 				= "";
	$description 		= "";
	$access_id 			= ACCESS_LOGGED_IN;

	$entity_hidden = "";
	
	$widget = elgg_view('simplekaltura/forms/ksu_widget');	
}

$submit_input = elgg_view('input/submit', array(
	'internalid' => 'simplekaltura_submit', 
	'internalname' => 'save', 
	'value' => elgg_echo('save')
));	

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());

$container_hidden = elgg_view('input/hidden', array(
	'internalname' => 'container_guid', 
	'value' => $container_guid
));


// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'internalid' => 'video_title', 
	'internalname' => 'video_title', 
	'value' => $title
));

$description_label = elgg_echo("simplekaltura:label:description");
$description_input = elgg_view("input/longtext", array(
	'internalname' => 'video_description', 
	'value' => $description
));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array(
	'internalid' => 'video_tags', 
	'internalname' => 'video_tags', 
	'value' => $tags
));
													
$access_label = elgg_echo('access');
$access_content = elgg_view('input/access', array(
	'internalname' => 'video_access', 
	'internalid' => 'video_access', 
	'value' => $access_id
));

// Hidden inputs for kaltura entries
$k_guid_input = elgg_view('input/hidden', array(
	'internalname' => 'k_guid',
	'internalid' => 'k_guid'
));

$k_entryid_input = elgg_view('input/hidden', array(
	'internalname' => 'k_entryid',
	'internalid' => 'k_entryid'
));

$k_bytesloaded_input = elgg_view('input/hidden', array(
	'internalname' => 'k_bytesloaded',
	'internalid' => 'k_bytesloaded'
));

$k_filetype_input = elgg_view('input/hidden', array(
	'internalname' => 'k_filetype',
	'internalid' => 'k_filetype'
));

// Build Form Body
$form_body = <<<EOT
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
	<div id="simplekaltura-upload-dialog">
		<p>Uploading... <span id="simplekaltura-upload-percent"></span></p>
		<div id='simplekaltura-upload-progress'></div>
	</div>
</div>

EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'video_post_form'));
?>