<?php
/**
 * Simple Kaltura Settings
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 * 
 */

$plugin = $vars['entity'];

// Player id
$player_id = $plugin->kaltura_custom_player_id ? $plugin->kaltura_custom_player_id : '1000106';

// Player height
$player_height = $plugin->kaltura_player_height ? $plugin->kaltura_player_height : '330'; 

// Player width
$player_width = $plugin->kaltura_player_width ? $plugin->kaltura_player_width : '400';

// Thumbnail url
$thumbnail_url = $plugin->kaltura_thumbnail_url ? $plugin->kaltura_thumbnail_url : 'http://cdn.kaltura.com/p/0/thumbnail/entry_id/'; // Default url;

// Thumbnail dimensions
$smallthumb_height = $plugin->kaltura_smallthumb_height ? $plugin->kaltura_smallthumb_height : '67';
$smallthumb_width = $plugin->kaltura_smallthumb_width ? $plugin->kaltura_smallthumb_width : '120';

$mediumthumb_height = $plugin->kaltura_mediumthumb_height ? $plugin->kaltura_mediumthumb_height : '150';
$mediumthumb_width = $plugin->kaltura_mediumthumb_width ? $plugin->kaltura_mediumthumb_width : '200';

$largethumb_height = $plugin->kaltura_largethumb_height ? $plugin->kaltura_largethumb_height : '230';
$largethumb_width = $plugin->kaltura_largethumb_width ? $plugin->kaltura_largethumb_width : '307';

/************** Configuration Module **************/
$config_label = elgg_echo('simplekaltura:admin:kalturaconfig');

// Admin tags input
$admintags_label = elgg_echo('simplekaltura:admin:admintags');
$admintags_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_admin_tags]', 
	'value' => $plugin->kaltura_admin_tags
));

// Partner id
$partnerid_label = elgg_echo('simplekaltura:admin:partnerid');
$partnerid_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_partnerid]', 
	'value' => $plugin->kaltura_partnerid
));

// Email account (for kaltura authentication)
$emailaccount_label = elgg_echo('simplekaltura:admin:emailaccount');
$emailaccount_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_email_account]', 
	'value' => $plugin->kaltura_email_account
));

// Password (for kaltura authentication)
$passwordaccount_label = elgg_echo('simplekaltura:admin:passwordaccount');
$passwordaccount_input = elgg_view('input/password', array(
	'name' => 'params[kaltura_password_account]', 
	'value' => $plugin->kaltura_password_account
));

$config_body = <<<HTML
	<div>
		<label>$admintags_label</label>
		$admintags_input
	</div><br />
	<div>
		<label>$partnerid_label</label>
		$partnerid_input
	</div><br />
	<div>
		<label>$emailaccount_label</label>
		$emailaccount_input
	</div><br />
	<div>
		<label>$passwordaccount_label</label><br />
		$passwordaccount_input
	</div><br />
HTML;

echo elgg_view_module('inline', $config_label, $config_body);

/************** Player Module **************/
$player_label = elgg_echo('simplekaltura:admin:playerconfig');

// Custom player id
$customplayerid_label = elgg_echo('simplekaltura:admin:customplayerid');
$customplayerid_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_custom_player_id]', 
	'value' => $player_id
));

// Player height
$playerheight_label = elgg_echo('simplekaltura:admin:playerheight');
$playerheight_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_player_height]', 
	'value' => $player_height
));

// Player width
$playerwidth_label = elgg_echo('simplekaltura:admin:playerwidth');
$playerwidth_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_player_width]', 
	'value' => $player_width
));

$player_body = <<<HTML
	<div>
		<label>$customplayerid_label</label>
		$customplayerid_input
	</div><br />
	<div>
		<label>$playerheight_label</label>
		$playerheight_input
	</div><br />
	<div>
		<label>$playerwidth_label</label>
		$playerwidth_input
	</div>
HTML;

echo elgg_view_module('inline', $player_label, $player_body);

/************** Thumbnail Module **************/
$thumb_label = elgg_echo('simplekaltura:admin:thumbconfig');

// Thumbnail base url
$thumbnailurl_label = elgg_echo('simplekaltura:admin:thumbnailurl');
$thumbnailurl_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_thumbnail_url]', 
	'value' => $thumbnail_url
));

// Small thumbnail dimensions
$smallthumb_label = elgg_echo('simplekaltura:admin:smallthumb');

$smallthumb_width_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_smallthumb_width]', 
	'value' => $smallthumb_width,
	'style' => 'width: 75%',
));

$smallthumb_height_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_smallthumb_height]', 
	'value' => $smallthumb_height,
	'style' => 'width: 75%',
));

// Medium thumbnail dimensions
$mediumthumb_label = elgg_echo('simplekaltura:admin:mediumthumb');

$mediumthumb_width_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_mediumthumb_width]', 
	'value' => $mediumthumb_width,
	'style' => 'width: 75%',
));

$mediumthumb_height_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_mediumthumb_height]', 
	'value' => $mediumthumb_height,
	'style' => 'width: 75%',
));

// Large thumbnail dimensions
$largethumb_label = elgg_echo('simplekaltura:admin:largethumb');

$largethumb_width_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_largethumb_width]', 
	'value' => $largethumb_width,
	'style' => 'width: 75%',
));

$largethumb_height_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_largethumb_height]', 
	'value' => $largethumb_height,
	'style' => 'width: 75%',
));

// Heigt/width labels
$width_label = elgg_echo('simplekaltura:admin:width');
$height_label = elgg_echo('simplekaltura:admin:height');

// Build content
$thumb_body = <<<HTML
	<div>
		<label>$thumbnailurl_label</label>
		$thumbnailurl_input
	</div><br />
	<div>
		<table class='elgg-table'>
			<tr>
				<td><label>$smallthumb_label</label></td>
				<td><label>$width_label</label>&nbsp;$smallthumb_width_input</td>
				<td><label>$height_label</label>&nbsp;$smallthumb_height_input</td>
			</tr>
			<tr>
				<td><label>$mediumthumb_label</label></td>
				<td><label>$width_label</label>&nbsp;$mediumthumb_width_input</td>
				<td><label>$height_label</label>&nbsp;$mediumthumb_height_input</td>
			</tr>
			<tr>
				<td><label>$largethumb_label</label></td>
				<td><label>$width_label</label>&nbsp;$largethumb_width_input</td>
				<td><label>$height_label</label>&nbsp;$largethumb_height_input</td>
			</tr>
		</table>
	</div>
HTML;

echo elgg_view_module('inline', $thumb_label, $thumb_body);
