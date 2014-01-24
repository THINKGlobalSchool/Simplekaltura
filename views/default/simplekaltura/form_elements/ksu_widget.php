<?php
/**
 * Simple Kaltura KSU widget
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.org
 * 
 * IMPORTANT!!
 *
 * - Kaltura likes to change their 'uiconfs' around all the time.. the uiconfid tells Kaltura
 *   to grab a specific flash widget, they do tend to change time to time. If the uploader is 
 *   suddenly broken, it's probably due to a change on their end. 
 *   
 *   Check the following for the lastest 'integration script' download: 
 *   
 *   http://knowledge.kaltura.com/kaltura-simple-uploader-ksu-website-integration-guid
 *  
 * Previous working uiconfid's:
 * - 8003811 
 */

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');
elgg_load_js('simplekaltura:uploader');
elgg_load_js('simplekaltura:swfobject');

// uiconf
$uiconf = 19831372;

// Session vars
$kaltura_partner_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');
$partner_user_id = elgg_get_plugin_setting('kaltura_partnerid', 'simplekaltura');

// Upload size
$upload_max = elgg_get_plugin_setting('kaltura_upload_max', 'simplekaltura');

// Get client
$client = simplekaltura_create_client();

$flashVars = array();
$flashVars["uid"]          = $partner_user_id;
$flashVars["partnerId"]    = $kaltura_partner_id;
$flashVars["subPId"]       = $kaltura_partner_id *100;
$flashVars["entryId"]      = -1;
$flashVars["ks"]           = $client->getKs();
$flashVars["maxFileSize"]  = $upload_max;
$flashVars["maxTotalSize"] = $upload_max;
$flashVars["uiConfId"]     = $uiconf;
$flashVars["jsDelegate"]   = "delegate";

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

		swfobject.embedSWF("https://www.kaltura.com/kupload/ui_conf_id/<?php echo $uiconf; ?>", "simplekaltura-uploader-container", width, height, "9.0.0", "expressInstall.swf", flashVars, params,attributes);
	</script>
</div>