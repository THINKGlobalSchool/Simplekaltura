<?php
/**
 * Simple Kaltura iframe widget
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 * Generated from: http://kaltura.github.io/EmbedCodeGenerator/demo/
 */

$player_width 		= elgg_extract('width', $vars, elgg_get_plugin_setting('kaltura_player_width', 'simplekaltura'));
$player_height 		= elgg_extract('height', $vars, elgg_get_plugin_setting('kaltura_player_height', 'simplekaltura'));
$partner_id         = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$sp_id              = $partner_id * 100;
$uiConfId 			= elgg_extract('custom_uiconfid', $vars, elgg_get_plugin_setting('kaltura_custom_player_id', 'simplekaltura')); 	// Which player to use
$entryid 			= $vars['entity']->kaltura_entryid;			                        // ENTRY ID TO PLAYBACK

echo "<iframe id='kaltura_player' src='//cdnsecakmi.kaltura.com/p/{$partner_id}/sp/{$sp_id}/embedIframeJs/uiconf_id/{$uiConfId}/partner_id/{$partner_id}?iframeembed=true&playerId=kaltura_player&entry_id={$entryid}' width='{$player_width}' height='{$player_height}' allowfullscreen webkitallowfullscreen mozAllowFullScreen frameborder='0'></iframe>";