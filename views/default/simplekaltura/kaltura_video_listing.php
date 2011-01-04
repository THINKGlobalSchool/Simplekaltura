<?php
/**
 * Simple Kaltura Video Listing
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

// Get entity info
$owner = $vars['entity']->getOwnerEntity();
$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
$address = $vars['entity']->getURL();
$title = $vars['entity']->title;
$parsed_url = parse_url($address);

//sort out the access level for display
$object_acl = get_readable_access_level($vars['entity']->access_id);

// Function above works sometimes.. its weird. So load ACL name if any
if (!$object_acl) {
	$acl = get_access_collection($vars['entity']->access_id);
	$object_acl = $acl->name;
}

if($vars['entity']->description != '')
	$view_desc = "| <a class='link' onclick=\"elgg_slide_toggle(this,'.entity_listing','.note');\">" . elgg_echo('description') . "</a>";
else
	$view_desc = '';


$icon = elgg_view("profile/icon", array('entity' => $owner,'size' => 'tiny',));

//delete
if($vars['entity']->canEdit()){
	$delete .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array(
				'href' => "action/simplekaltura/delete?guid=" . $vars['entity']->guid,
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("simplekaltura:label:deleteconfirm"),
				)) . "</span>";
}

$info = "<div class='entity_metadata'><span {$access_level}>{$object_acl}</span>";

// include a view for plugins to extend
$info .= elgg_view("simplekaltura/options",array('entity' => $vars['entity']));

// Add favorites and likes
$info .= elgg_view("favorites/form",array('entity' => $vars['entity']));
$info .= elgg_view_likes($vars['entity']); // include likes

// include delete
if($vars['entity']->canEdit()){
	$info .= $delete;
}

$info .= "</div>";

$info .= "<p class='entity_title'><a href=\"{$address}\">{$title}</a></p>";
$info .= "<p class='entity_subtext'>" . elgg_echo('simplekaltura:label:posted_by', array("<a href=\"".elgg_get_site_url()."pg/videos/owner/{$owner->username}\">{$owner->name}</a>")) . " {$friendlytime} {$view_desc}</p>";

$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
if (!empty($tags)) {
	$info .= '<p class="tags">' . $tags . '</p>';
}
if($view_desc != ''){
	$info .= "<div class='note hidden'>". $vars['entity']->description . "</div>";
}

//display
echo elgg_view_listing($icon, $info);