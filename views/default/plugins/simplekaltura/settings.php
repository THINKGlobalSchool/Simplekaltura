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
 * @todo tab layout?
 */

$plugin = $vars['entity'];

// Kaltura.com email account
$email_account = $plugin->kaltura_email_account;

// Kaltura.com password
$account_password = $plugin->kaltura_password_account;

// Partner id
$partner_id = $plugin->kaltura_partnerid;

// Admin tags
$admin_tags = $plugin->kaltura_admin_tags;

// uiConfId
$uiconfid = $plugin->kaltura_uiconfid;

// Player id
$player_id = $plugin->kaltura_custom_player_id;

// Player height
$player_height = $plugin->kaltura_player_height;

// Player width
$player_width = $plugin->kaltura_player_width;

// Embed player id
$embed_player_id = $plugin->kaltura_custom_embed_player_id;

// Allow non public embeds
$allow_non_public_embed = $plugin->kaltura_allow_non_public_embed;

// Embed player height
$embed_player_height = $plugin->kaltura_embed_player_height;

// Embed player width
$embed_player_width = $plugin->kaltura_embed_player_width;

// Thumbnail url
$thumbnail_url = $plugin->kaltura_thumbnail_url;

// Thumbnail dimensions
$smallthumb_height = $plugin->kaltura_smallthumb_height;
$smallthumb_width = $plugin->kaltura_smallthumb_width;

$mediumthumb_height = $plugin->kaltura_mediumthumb_height;
$mediumthumb_width = $plugin->kaltura_mediumthumb_width;

$largethumb_height = $plugin->kaltura_largethumb_height;
$largethumb_width = $plugin->kaltura_largethumb_width;

// Popup dimensions
$popup_height = $plugin->kaltura_popup_height;
$popup_width = $plugin->kaltura_popup_width;

// Upload maximum size
$upload_max = $plugin->kaltura_upload_max;

// Entity title
$entity_title = $plugin->kaltura_entity_title;

// Admin only download
$admin_only_download = $plugin->admin_only_download;

/************** Site Configuration Module **************/
$site_config_label = elgg_echo('simplekaltura:admin:siteconfig');

// Max upload size input
$entity_title_label = elgg_echo('simplekaltura:admin:entitytitle');
$entity_title_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_entity_title]', 
	'value' => $entity_title
));

$site_config_body = <<<HTML
	<div>
		<label>$entity_title_label</label>
		$entity_title_input
	</div>
HTML;

echo elgg_view_module('inline', $site_config_label, $site_config_body);

/************** Kaltura Configuration Module **************/
$config_label = elgg_echo('simplekaltura:admin:kalturaconfig');

// Admin tags input
$admintags_label = elgg_echo('simplekaltura:admin:admintags');
$admintags_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_admin_tags]', 
	'value' => $admin_tags
));

// Partner id
$partnerid_label = elgg_echo('simplekaltura:admin:partnerid');
$partnerid_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_partnerid]', 
	'value' => $partner_id
));

// uiConfID
$uiconfid_label = elgg_echo('simplekaltura:admin:uiconfid');
$uiconfid_desc = elgg_echo('simplekaltura:admin:uiconfiddesc');
$uiconfid_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_uiconfid]', 
	'value' => $uiconfid,
));

// Email account (for kaltura authentication)
$emailaccount_label = elgg_echo('simplekaltura:admin:emailaccount');
$emailaccount_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_email_account]', 
	'value' => $email_account
));

// Password (for kaltura authentication)
$passwordaccount_label = elgg_echo('simplekaltura:admin:passwordaccount');
$passwordaccount_input = elgg_view('input/password', array(
	'name' => 'params[kaltura_password_account]', 
	'value' => $account_password
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
		<label>$uiconfid_label</label><br />
		<span class='elgg-subtext'>$uiconfid_desc</span>
		$uiconfid_input
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

// Allow non public embeds input
$non_public_embed_label = elgg_echo('simplekaltura:admin:allownonpublicembed');
$non_public_embed_input = elgg_view('input/dropdown', array(
	'name' => 'params[kaltura_allow_non_public_embed]', 
	'options_values' => array(
		0 => elgg_echo('simplekaltura:label:no'),
		1 => elgg_echo('simplekaltura:label:yes')
	),
	'value' => $allow_non_public_embed
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

// Embed player id
$embed_customplayerid_label = elgg_echo('simplekaltura:admin:embedplayerid');
$embed_customplayerid_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_custom_embed_player_id]', 
	'value' => $embed_player_id
));

// Embed player height
$embed_playerheight_label = elgg_echo('simplekaltura:admin:embedplayerheight');
$embed_playerheight_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_embed_player_height]', 
	'value' => $embed_player_height
));

// Embed player width
$embed_playerwidth_label = elgg_echo('simplekaltura:admin:embedplayerwidth');
$embed_playerwidth_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_embed_player_width]', 
	'value' => $embed_player_width
));

$player_body = <<<HTML
	<div>
		<label>$customplayerid_label</label>
		$customplayerid_input
	</div><br />
	<div>
		<label>$playerwidth_label</label>
		$playerwidth_input
	</div><br />
	<div>
		<label>$playerheight_label</label>
		$playerheight_input
	</div><br />
	<div>
		<label>$embed_customplayerid_label</label>
		$embed_customplayerid_input
	</div><br />
	<div>
		<label>$non_public_embed_label</label>
		$non_public_embed_input
	</div><br />
	<div>
		<label>$embed_playerwidth_label</label>
		$embed_playerwidth_input
	</div><br />
	<div>
		<label>$embed_playerheight_label</label>
		$embed_playerheight_input
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


/************** Popup Module **************/
$popup_label = elgg_echo('simplekaltura:admin:popupconfig');

// Popup height
$popupheight_label = elgg_echo('simplekaltura:admin:popupheight');
$popupheight_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_popup_height]', 
	'value' => $popup_height
));

// Popup width
$popupwidth_label = elgg_echo('simplekaltura:admin:popupwidth');
$popupwidth_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_popup_width]', 
	'value' => $popup_width
));

$popup_body = <<<HTML
	<div>
		<label>$popupwidth_label</label>
		$popupwidth_input
	</div><br />
	<div>
		<label>$popupheight_label</label>
		$popupheight_input
	</div>
HTML;

echo elgg_view_module('inline', $popup_label, $popup_body);

/************** Upload Module **************/
$upload_label = elgg_echo('simplekaltura:admin:uploadconfig');

// Max upload size input
$upload_max_label = elgg_echo('simplekaltura:admin:uploadmax');
$upload_max_input = elgg_view('input/text', array(
	'name' => 'params[kaltura_upload_max]', 
	'value' => $upload_max
));

$upload_body = <<<HTML
	<div>
		<label>$upload_max_label</label>
		$upload_max_input
	</div>
HTML;

echo elgg_view_module('inline', $upload_label, $upload_body);


/************** Additional Settings **********/
$additional_settings_label = elgg_echo('simplekaltura:admin:additional_settings');
$admin_download_label = elgg_echo('simplekaltura:admin:admin_only_download');
$admin_download_input = elgg_view('input/dropdown', array(
	'name' => 'params[admin_only_download]',
	'value' => $admin_only_download ? $admin_only_download : 'no',
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no')
	)
));

$additional_settings_body = <<<HTML
	<div>
		<label>$admin_download_label</label>
		$admin_download_input
	</div>
HTML;

echo elgg_view_module('inline', $additional_settings_label, $additional_settings_body);


if (simplekaltura_migration_check()) {
	$url = elgg_normalize_url('admin/simplekaltura/migrate');
		$link = elgg_view('output/url', array(
			'text' => $url,
			'href' => $url
		));
	elgg_add_admin_notice('simplekaltura_migrate_message', elgg_echo('simplekaltura:migrate:message', array($link)));
}
