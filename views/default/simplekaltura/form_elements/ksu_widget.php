<?php
/**
 * Simple Kaltura KSU widget
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
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
 *   http://knowledge.kaltura.com/kaltura-simple-uploader-ksu-website-integration-guide
 *  
 * Previous working uiconfid's:
 * - 8003811 
 */

elgg_load_library('simplekaltura');
elgg_load_library('KalturaClient');
elgg_load_js('simplekaltura:swfobject');

// uiconf
$uiconf = elgg_get_plugin_setting('kaltura_uiconfid', 'simplekaltura');

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
$flashVars["jsDelegate"]   = "uploader";

$video_submit = elgg_view('input/button', array(
	'class' => 'elgg-button elgg-button-submit',
	'id' => 'simplekaltura-uploader-submit',
	'value' => elgg_echo('simplekaltura:label:selectvideo')
));

$dialog_title = elgg_echo('simplekaltura:label:uploadingdialogtitle');

echo <<<HTML
	<div id="simplekaltura-uploader-container">
		$video_submit
	</div>
HTML;

?>	
<script type="text/javascript">
	var uploader;

	// Load in the SKUploader
	require(['simplekaltura/SKUploader'], function (Filtrate) {
		var flashVars = <?php echo json_encode($flashVars); ?>;

		// Init the uploader
		uploader = $('#simplekaltura-uploader-container').SKUploader({
			uploaderInput: $('#simplekaltura-uploader-submit'),
			tagsInput: 'input[name=video_tags]',
			titleInput: 'input#video_title',
			formSubmit: $('#simplekaltura-submit'),
			flashVars: flashVars,
			uiConfId: '<?php echo $uiconf; ?>',
			maxUpload: '<?php echo $upload_max; ?>',
			dialogTitle: elgg.echo('simplekaltura:label:uploadingdialogtitle'),
			debug: true
		});
	});
</script>
