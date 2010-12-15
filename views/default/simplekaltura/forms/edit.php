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
	$entity_hidden  = elgg_view('input/hidden', array('internalname' => 'video_guid', 'value' => $vars['entity']->getGUID()));

} else {
	// No entity, creating new one
	$action 			= "simplekaltura/save";
	$title 				= "";
	$description 		= "";
	$access_id 			= ACCESS_LOGGED_IN;

	$entity_hidden = "";
	
	$widget = elgg_view('simplekaltura/forms/ksu_widget');	
}

$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));	

$container_guid = get_input('container_guid', elgg_get_page_owner_guid());

$container_hidden = elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => $container_guid));


// Labels/Input
$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array('internalname' => 'video_title', 'value' => $title));

$description_label = elgg_echo("simplekaltura:label:description");
$description_input = elgg_view("input/longtext", array('internalname' => 'video_description', 'value' => $description));

$tag_label = elgg_echo('tags');
$tag_input = elgg_view('input/tags', array('internalname' => 'video_tags', 'value' => $tags));
													
$access_label = elgg_echo('access');
$access_content = elgg_view('input/access', array('internalname' => 'access_level', 'internalid' => 'video_access', 'value' => $access_id));
		

// Build Form Body
$form_body = <<<EOT

<div class='margin_top simplekaltura'>
	<div>
		<label>$title_label</label><br />
        $title_input
	</div><br />
	<div>
		<label>$description_label</label><br />
        $description_input
	</div><br />
	<div>
		$widget
	</div>
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
	</div>
</div>

EOT;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body, 'internalid' => 'video_post_form'));
?>