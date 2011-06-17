<?php
/**
 * Simple Kaltura Video Widget
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$player_width 		= elgg_extract('width', $vars, elgg_get_plugin_setting('kaltura_player_width', 'simplekaltura'));
$player_height 		= elgg_extract('height', $vars, elgg_get_plugin_setting('kaltura_player_height', 'simplekaltura'));
$widgetid 			= "_" . elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');   // specific player instance (default to '_'+partnerId)
$uiConfId 			= elgg_get_plugin_setting('kaltura_custom_player_id', 'simplekaltura'); 	// Which player to use
$entryid 			= $vars['entity']->kaltura_entryid;			                        // ENTRY ID TO PLAYBACK
$host 				= "www.kaltura.com";                      							// THE URL TO THE KALTURA SERVER 
$debugmode 			= "0";                                       						// DEBUGGING OF PLUGINS 
$autoPlay 			= elgg_extract('autoplay', $vars, false);                           // BOOLEAN FOR AUTOPLAY
$bufferTime 		= "5";                                      						// TIME IN SECONDS TO BUFFER - ONLY USED FOR 
$downloadBeforePlay = "0";              												// NO PLAYBACK SHOULD BE AVAILABLE BEFORE THE FILE HAS BEEN DOWNLOADED
$disableUrlHashing 	= "1";                       										// INDICATE THAT NO DYNAMIC SUB-DOMAINS SHOULD BE USED
$localUiFunc 		= "";                                      							// FOR DEBUGGING OF LOCAL (IN-PAGE) UICONF XML
$swf_url            = simplekaltura_get_swf_url($vars['entity']);

?>
<object id="kaltura_player"
		name="kaltura_player"
		type="application/x-shockwave-flash"
		allowFullScreen="true"
		allowNetworking="all"
		allowScriptAccess="always"
		height="<?php echo $player_height; ?>"
		width="<?php echo $player_width; ?>"
		data="<?php echo $swf_url; ?>"
>
	<param name="allowFullScreen" value="true" />
	<param name="allowNetworking" value="all" />
	<param name="allowScriptAccess" value="always" />
	<param name="bgcolor" value="#000000" />
	<param name="flashVars" value="&autoPlay=<?php echo $autoPlay; ?>&entryId=<?php echo $entryid; ?>" />
	<param name="movie" value="<?php echo $swf_url;?>" />
</object>