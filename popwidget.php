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
 * @TODO make into a function to generate the widget?
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
                    
$player_height 		= get_input('height');  
$widgetid 			= "_" . get_plugin_setting('kaltura_partnerid', 'simplekaltura');   // specific player instance (default to '_'+partnerId)
$uiConfId 			= get_plugin_setting('kaltura_custom_player_id', 'simplekaltura'); 	// Which player to use
$entryid 			= get_input('entryid');                        		// ENTRY ID TO PLAYBACK 	
$host 				= "www.kaltura.com";                      							// THE URL TO THE KALTURA SERVER 
$debugmode 			= "0";                                       						// DEBUGGING OF PLUGINS 
$autoPlay 			= "0";                                        						// BOOLEAN FOR AUTOPLAY
$bufferTime 		= "5";                                      						// TIME IN SECONDS TO BUFFER - ONLY USED FOR 
$downloadBeforePlay = "0";              												// NO PLAYBACK SHOULD BE AVAILABLE BEFORE THE FILE HAS BEEN DOWNLOADED
$disableUrlHashing 	= "1";                       										// INDICATE THAT NO DYNAMIC SUB-DOMAINS SHOULD BE USED
$localUiFunc 		= "";                                      							// FOR DEBUGGING OF LOCAL (IN-PAGE) UICONF XML

// JS
//echo elgg_view('simplekaltura/ksu_js');
?>
<br />
<object id="kaltura_player" 
		name="kaltura_player"
		type="application/x-shockwave-flash"
		allowFullScreen="true" 
		allowNetworking="all"
		allowScriptAccess="always" 
		height="<?php echo $player_height; ?>" 
		width="100%"
		data="http://www.kaltura.com/index.php/kwidget/wid/<?php echo $widgetid; ?>/uiconf_id/<?php echo $uiConfId; ?>/entry_id/<?php echo $entryid;?>"
>
	<param name="allowFullScreen" value="true" />
	<param name="allowNetworking" value="all" />
	<param name="allowScriptAccess" value="always" />
	<param name="bgcolor" value="#000000" />
	<param name="flashVars" value="&autoPlay=true&entryId=<?php echo $entryid; ?>" />
	<param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/<?php echo $widgetid; ?>/uiconf_id/<?php echo $uiConfId ?>/entry_id/<?php echo $entryid;?>" />
</object>
<div style='clear: both;'></div>
