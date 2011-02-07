<?php
// KDP3 Test widget


$player_width 		= get_plugin_setting('kaltura_player_width', 'simplekaltura');                           
$player_height 		= get_plugin_setting('kaltura_player_height', 'simplekaltura');    
$partner_id 		= get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$widget_id 			= "_" . $partner_id;
$entry_id 			= $vars['entity']->kaltura_entryid;
$uiconf_id 			= get_plugin_setting('kaltura_custom_player_id', 'simplekaltura'); 

echo elgg_view('simplekaltura/kdp_js');
?>
<script type="text/javascript">
	var player_width 	= "<?php echo $player_width; ?>";
	var player_height 	= "<?php echo $player_height; ?>";
	var entry_id 		= "<?php echo $entry_id; ?>";
	var partner_id 		= "<?php echo $parner_id; ?>";
	var uiconf_id 		= "<?php echo $uiconf_id; ?>";
	var swf_path 		= "<?php echo elgg_get_site_url() . 'mod/simplekaltura/vendors/kdp3.swf'; ?>";

	var flashvars = {};
	flashvars.autoPlay = "false";
	flashvars.entryId = entry_id;
	flashvars.entry_id = entry_id;
	flashvars.sourceType = "entryId";
	flashvars.debugMode = "true";
	flashvars.fileSystemMode = "false";
	flashvars.widgetId = "_115012";
	flashvars.wid = "_115012";
	flashvars.partnerId = "115012";
	flashvars.p = "115012";
	flashvars.pluginDomain = "plugins/";
	flashvars.uiConfId = uiconf_id;
	flashvars.uiconf_id = uiconf_id;
	flashvars.host = "www.kaltura.com";
	flashvars.cdnHost = "cdn.kaltura.com";
	flashvars.externalInterfaceDisabled = "true";

	var params = {};
	params.quality = "best";
	params.wmode = "opaque";
	params.allowfullscreen = "true";
	params.allowscriptaccess = "always";
	
	var attributes = {};
	attributes.id = "kdp3";
	attributes.name = "kdp3";
	attributes.styleclass = "player";
	swfobject.embedSWF(swf_path, "kdp3", player_width,  player_height, "10.0.0", "expressInstall.swf", flashvars, params, attributes);
	

	var $vidPlayer = $("#kdp3");
	//var playerWidth = $(".simplekaltura-video-container").width();

	//var $vidPlayer = $("embed");
	var aspect = $vidPlayer.height() / $vidPlayer.width();
	    $(window).resize(function() {
	          var playerWidth = $(".simplekaltura-video-container").width();
	          $vidPlayer
	            .width(playerWidth)
	            .height(playerWidth * aspect);
	    }).trigger("resize");

</script>

<div id="kdp3"></div>
