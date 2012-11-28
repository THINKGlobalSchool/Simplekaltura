<?php

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');
elgg_load_js('simplekaltura:uploader');
elgg_load_js('simplekaltura:widget');
elgg_load_js('simplekaltura:swfobject');

// Session vars
$kaltura_partner_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$partner_user_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');

// Get client
$client = simplekaltura_create_client();

$flashVars = array();
$flashVars["uid"]   			= $partner_user_id;
$flashVars["partnerId"]			= $kaltura_partner_id;
$flashVars["subPId"] 			= $kaltura_partner_id *100;
$flashVars["entryId"] 	 		= -1;
$flashVars["ks"]   				= $client->getKs();
$flashVars["maxFileSize"]   	= 5000;
$flashVars["maxTotalSize"]   	= 5000;
$flashVars["uiConfId"]   		= 8003811;
$flashVars["jsDelegate"]   		= "delegate";

?>

<div id="simplekaltura-flashContainer">
	<input type="button" id="simplekaltura-uploader-submit" value="<?php echo elgg_echo('simplekaltura:label:selectvideo'); ?>">
	<div id="simplekaltura-selected-files">
	</div>
	<div id="simplekaltura-uploader-container">
	</div>
	<script language="JavaScript" type="text/javascript">
		var params = {
			allowScriptAccess: "always",
			allowNetworking: "all",
			wmode: "transparent"

		};
		var attributes  = {
			id: "simplekaltura-uploader",
			name: "KUpload",
		};
		// set flashVar object
		var flashVars = <?php echo json_encode($flashVars); ?>;

		// get the width and height of the button to mask
		// @todo this feels like a really odd way to make this button.
		var width = $('#simplekaltura-uploader-submit').innerWidth()
		var height = $('#simplekaltura-uploader-submit').innerHeight()

		swfobject.embedSWF("http://www.kaltura.com/kupload/ui_conf_id/8003811", "simplekaltura-uploader-container", width, height, "9.0.0", "expressInstall.swf", flashVars, params,attributes);
	</script>
</div>