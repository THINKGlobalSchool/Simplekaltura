<!--include external scripts and define constants -->
<?php
	require_once(elgg_get_plugin_path() . "simplekaltura/vendors/kaltura_client_v3/KalturaClient.php"); 
	
	// Session vars
	$kaltura_partner_id = get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	$partner_user_id = get_plugin_setting('kaltura_partnerid', 'simplekaltura');
	
	// Get client
	$client = simplekaltura_create_client();
	
	$flashVars = array();
	$flashVars["uid"]   			= $partner_user_id; 
	$flashVars["partnerId"]			= $kaltura_partner_id;
	$flashVars["subPId"] 			= $kaltura_partner_id *100;
	$flashVars["entryId"] 	 		= -1;	     
	$flashVars["ks"]   				= $client->getKs(); 
	$flashVars["conversionProfile"]	= 5; 
	$flashVars["maxFileSize"]   	= 500; 
	$flashVars["maxTotalSize"]   	= 5000; 
	$flashVars["uiConfId"]   		= 11500; 
	$flashVars["jsDelegate"]   		= "delegate"; 
	
	// Include JS
	echo elgg_view('simplekaltura/ksu_js');
?>

<div id="simplekaltura-flashContainer">
	<input type="button" id="simplekaltura-uploader-submit" class="submit_button" value="<?php echo elgg_echo('simplekaltura:label:selectvideo'); ?>">	
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
			name: "KUpload"
		};
		// set flashVar object
		var flashVars = <?php echo json_encode($flashVars); ?>;
		 <!--embed flash object-->
		swfobject.embedSWF("http://www.kaltura.com/kupload/ui_conf_id/11500", "simplekaltura-uploader-container", "200", "30", "9.0.0", "expressInstall.swf", flashVars, params,attributes);
	</script>
</div>