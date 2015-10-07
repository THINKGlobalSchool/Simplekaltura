<?php
/**
 * Simple Kaltura Embed Code
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

$guid = elgg_extract('video_guid', $vars);

$video = get_entity($guid);
$entryId = $video->kaltura_entryid;

$site_link_label = elgg_echo('simplekaltura:label:viewonsite', array(elgg_get_site_entity()->name));

// Embed player dimensions/config
$width = elgg_get_plugin_setting('kaltura_embed_player_width', 'simplekaltura');
$height = elgg_get_plugin_setting('kaltura_embed_player_height', 'simplekaltura');
$embed_player = elgg_get_plugin_setting('kaltura_custom_embed_player_id', 'simplekaltura');

// General footer for embeds
$embed_footer = "<a href='{$video->getURL()}'>{$site_link_label}</a>";

// Flash object embed
$embed_flash = elgg_view('simplekaltura/widget', array(
	'custom_uiconfid' => $embed_player,
	'width' => $width,
	'height' => $height,
	'entity' => $video, 
)) . $embed_footer;

// IFrame embed
$embed_iframe = elgg_view('simplekaltura/iframe', array(
	'custom_uiconfid' => $embed_player,
	'width' => $width,
	'height' => $height,
	'entity' => $video, 
)) . $embed_footer;

// Build content
$embed_label = "<label>" . elgg_echo('simplekaltura:label:copypaste') . "</label>";

// Flash input
$embed_flash_content .= elgg_view('input/plaintext', array(
	'value' => $embed_flash,
	'readonly' => 'readonly',
	'class' => 'elgg-kaltura-embed-code hidden',
	'id' => 'elgg-kaltura-embed-flash'
));

// IFrame input
$embed_iframe_content .= elgg_view('input/plaintext', array(
	'value' => $embed_iframe,
	'readonly' => 'readonly',
	'class' => 'elgg-kaltura-embed-code',
	'id' => 'elgg-kaltura-embed-iframe'
));

// Tabs to switch flash/iframe
$params = array(
	'id' => 'elgg-kaltura-embed-code-switcher',
	'tabs' => array(
		array('title' => elgg_echo('simplekaltura:label:iframe'), 'url' => "#elgg-kaltura-embed-iframe", 'selected' => true),
		array('title' => elgg_echo('simplekaltura:label:flash'), 'url' => "#elgg-kaltura-embed-flash")
	)
);

$tabs = elgg_view('navigation/tabs', $params);

$embed_content .= $tabs;
$embed_content .= $embed_label;
$embed_content .= $embed_iframe_content;
$embed_content .= $embed_flash_content;

echo "<br />" . elgg_view_module('featured', elgg_echo('simplekaltura:label:embedcode'), $embed_content);