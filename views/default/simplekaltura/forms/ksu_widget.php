<!--include external scripts and define constants -->
<?php
	require_once(elgg_get_plugin_path() . "simplekaltura/vendors/kaltura_client_v3/KalturaClient.php"); 
	
	//define constants
	define("KALTURA_PARTNER_ID", get_plugin_setting('kaltura_partnerid', 'simplekaltura'));

	//define session variables
	$partnerUserID = get_plugin_setting('kaltura_partnerid', 'simplekaltura');

	//Construction of Kaltura objects for session initiation
	$config           = new KalturaConfiguration(KALTURA_PARTNER_ID);
	$client           = new KalturaClient($config);
	$partner 		  = $client->partner->getSecrets(KALTURA_PARTNER_ID, 
											get_plugin_setting('kaltura_email_account', 'simplekaltura'), 
											get_plugin_setting('kaltura_password_account', 'simplekaltura'
						));
	$ks               = $client->session->start($partner->secret, $partnerUserID, KalturaSessionType::USER);

	$flashVars = array();
	$flashVars["uid"]   			= $partnerUserID; 
	$flashVars["partnerId"]			= KALTURA_PARTNER_ID;
	$flashVars["subPId"] 			= KALTURA_PARTNER_ID*100;
	$flashVars["entryId"] 	 		= -1;	     
	$flashVars["ks"]   				= $ks; 
	$flashVars["conversionProfile"]	= 5; 
	$flashVars["maxFileSize"]   	= 200; 
	$flashVars["maxTotalSize"]   	= 5000; 
	$flashVars["uiConfId"]   		= 11500; 
	$flashVars["jsDelegate"]   		= "delegate"; 
	
	// Include JS
	echo elgg_view('simplekaltura/ksu_js');
?>

<div id="simplekaltura-flashContainer">
	<form>
		<input type="button" id="simplekaltura-uploader-submit" class="submit_button" value="<?php echo elgg_echo('simplekaltura:label:selectvideo'); ?>">
	</form>
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