<?php
/**
 * Simple Kaltura Video View
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * Customized version of the KDP Template:
 * http://www.kaltura.org/kdp-2x-legacy-flex-based-kaltura-dynamic-player
 * 
 *
 */


$player_width = 320;                            //THE WIDTH OF THE PLAYER
$player_height = 240;                           //THE HEIGHT OF THE PLAYER
$widgetid = "_" . get_plugin_setting('kaltura_partnerid');                                      // specific player instance (default to '_'+partnerId)
$uiConfId = get_plugin_setting('kaltura_custom_player_id', 'simplekaltura');    // Which player to use
$entryid = $vars['entity']->kaltura_entryid;                                                                    //ENTRY ID TO PLAYBACK OR STATIC PROGRESSIVE FILE URL   
$host = "www.kaltura.com";                      //THE URL TO THE KALTURA SERVER 
$debugmode = "0";                                       //DEBUGGING OF PLUGINS - SHOULD NOT BE USED UNLESS DEBUGGING OF LOCAL TRANSITIONS/OVERLAYS/EFFECTS
$autoPlay = "0";                                        //BOOLEAN FOR AUTOPLAY
$bufferTime = "5";                                      //TIME IN SECONDS TO BUFFER - ONLY USED FOR 
$downloadBeforePlay = "0";              //INSTRUCT THAT NO PLAYBACK SHOULD BE AVAILABLE BEFORE THE WHOLE FILE HAS BEEN DOWNLOADED
$disableUrlHashing = "1";                       //INDICATE THAT NO DYNAMIC SUB-DOMAINS SHOULD BE USED
$localUiFunc = "";                                      //FOR DEBUGGING OF LOCAL (IN-PAGE) UICONF XML
//$seekFromStart = "120000";            //PERFORM SEEK WHEN PLAYER START PLAYBACK, IN MILLISECONDS

// JS
echo elgg_view('simplekaltura/ksu_js');
?>

      <object id="kaltura_player" name="kaltura_player"
       type="application/x-shockwave-flash"
       allowFullScreen="true" allowNetworking="all"
       allowScriptAccess="always" height="330" width="400"
       data="http://www.kaltura.com/index.php/kwidget/wid/<?php echo $widgetid; ?>/uiconf_id/<?php echo $uiConfId ?>/entry_id/<?php echo $entryid;?>">
        <param name="allowFullScreen" value="true" />
        <param name="allowNetworking" value="all" />
        <param name="allowScriptAccess" value="always" />
        <param name="bgcolor" value="#000000" />
        <param name="flashVars" value="&entryId=<?php echo $entryid; ?>" />
        <param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/<?php echo $widgetid; ?>/uiconf_id/<?php echo $uiConfId ?>/entry_id/<?php echo $entryid;?>" />
      </object>
