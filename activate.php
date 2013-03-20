<?php
/**
 * Simple Kaltura plugin activate script
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

// Get the plugin entity
$plugin = elgg_get_plugin_from_id('simplekaltura');

$defaults = array(
	// Player id
	'kaltura_custom_player_id' => '1000106',

	// Player dimensions
	'kaltura_player_height' => '330',
	'kaltura_player_width' => '400',
	
	// Thumbnail url
	'kaltura_thumbnail_url' => 'http://cdn.kaltura.com/p/0/thumbnail/entry_id/',

	// Thumbnail dimensions
	'kaltura_smallthumb_height' => '67',
	'kaltura_smallthumb_width' => '120',

	'kaltura_mediumthumb_height' => '150',
	'kaltura_mediumthumb_width' => '200',

	'kaltura_largethumb_height' => '230',
	'kaltura_largethumb_width' => '307',

	// Popup dimensions
	'kaltura_popup_height' => '450',
	'kaltura_popup_width' => '600',

	// Maximum upload (MB)
	'kaltura_upload_max' => 5000,

	// Site menu title
	'kaltura_menu_title' => 'Videos',
);

// Set default config values
foreach ($defaults as $setting => $value) {
	if (!$plugin->getSetting($setting)) {
		$plugin->setSetting($setting, $value);
	}
}