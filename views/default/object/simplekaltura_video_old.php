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


$player_width = 320;				//THE WIDTH OF THE PLAYER
$player_height = 240;				//THE HEIGHT OF THE PLAYER
$widgetid = "_" . get_plugin_setting('kaltura_partnerid');  					// specific player instance (default to '_'+partnerId)
$uiConfId = get_plugin_setting('kaltura_custom_player_id', 'simplekaltura');	// Which player to use
$entryid = $vars['entity']->kaltura_entryid;					 				//ENTRY ID TO PLAYBACK OR STATIC PROGRESSIVE FILE URL	
$host = "www.kaltura.com"; 			//THE URL TO THE KALTURA SERVER 
$debugmode = "0";					//DEBUGGING OF PLUGINS - SHOULD NOT BE USED UNLESS DEBUGGING OF LOCAL TRANSITIONS/OVERLAYS/EFFECTS
$autoPlay = "0";					//BOOLEAN FOR AUTOPLAY
$bufferTime = "5";					//TIME IN SECONDS TO BUFFER - ONLY USED FOR 
$downloadBeforePlay = "0";  		//INSTRUCT THAT NO PLAYBACK SHOULD BE AVAILABLE BEFORE THE WHOLE FILE HAS BEEN DOWNLOADED
$disableUrlHashing = "1";  			//INDICATE THAT NO DYNAMIC SUB-DOMAINS SHOULD BE USED
$localUiFunc = "";					//FOR DEBUGGING OF LOCAL (IN-PAGE) UICONF XML
//$seekFromStart = "120000";		//PERFORM SEEK WHEN PLAYER START PLAYBACK, IN MILLISECONDS

// JS
echo elgg_view('simplekaltura/ksu_js');
?>

<script type="text/javascript">
 if (swfobject.hasFlashPlayerVersion("9.0.0")) {
   var fn = function() {
     var att = { data:"http://www.kaltura.com/index.php/kwidget/wid/<?= $widgetid ?>/uiconf_id/<?= $uiConfId ?>", 
		width:"<?= $player_width ?>", height:"<?= $player_height ?>",
		id:"kdp_inst", name:"kdp_inst" };
     var par = { flashvars:
			"entryId=<?php echo($entryid); ?>" +
			"&autoPlay=<?php echo($autoPlay); ?>" +
			"&bufferTime=<?php echo($bufferTime); ?>" +
			"&disableUrlHashing=<?php echo($disableUrlHashing); ?>" +
			<?php if ($bufferTime > -1) echo '"&bufferTime='.$bufferTime.'" +'?>
			<?php if ($downloadBeforePlay > 0) echo '"&downloadBeforePlay='.$downloadBeforePlay.'" +'?>
			<?php if ($localUiFunc != '') echo '"&localUiFunc='.$localUiFunc.'" +'?>
			<?php if ($seekFromStart > -1) echo '"&seekFromStart='.$seekFromStart.'" +'?>
			<?php if ($debugmode > -1) echo '"&debugmode='.$debugmode.'" +'?>
			"&host=<?php echo($host); ?>" ,
		allowScriptAccess:"always",
		allowfullscreen:"true",
		bgcolor:"000000"};
     var id = "content";
     var myObject = swfobject.createSWF(att, par, id);
   };
   swfobject.addDomLoadEvent(fn);
 }
</script>
<div id="content">kdp should be loading here...</div>



