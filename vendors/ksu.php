<html>
<head>

<!--include external scripts and define constants -->
<?php 
	require_once("kaltura_client/KalturaClient.php"); 
	
	//define constants
	define("KALTURA_PARTNER_ID", '12345');
	define("KALTURA_PARTNER_WEB_SERVICE_SECRET", "ENTER YOUR PARTNER SECRET HERE");

	//define session variables
	$partnerUserID          = 'ANONYMOUS';

	//Construction of Kaltura objects for session initiation
	$config           = new KalturaConfiguration(KALTURA_PARTNER_ID);
	$client           = new KalturaClient($config);
	$ks               = $client->session->start(KALTURA_PARTNER_WEB_SERVICE_SECRET, $partnerUserID, KalturaSessionType::USER);

	$flashVars = array();
	$flashVars["uid"]   = $partnerUserID; 
	$flashVars["partnerId"] 		        = KALTURA_PARTNER_ID;
	$flashVars["subPId"] 		        = KALTURA_PARTNER_ID*100;
	$flashVars["entryId"] 	 = -1;	     
	$flashVars["ks"]   = $ks; 
	$flashVars["conversionProfile"]   = 5; 
	$flashVars["maxFileSize"]   = 200; 
	$flashVars["maxTotalSize"]   = 5000; 
	$flashVars["uiConfId"]   = 11500; 
	$flashVars["jsDelegate"]   = "delegate"; 
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<!---set style to enable widget overlap -->
<style>
	body { margin: 0px; overflow:hidden }
	#flashContainer{ position:relative; }
		#flashContainer span{ color:#333; font-size:16px; }
		object, embed{ position:absolute; top:0; left:0; z-index:999;}
</style>

<!---	JavaScript handler methods to react to upload events. -->
<script type="text/javascript">
	var flashObj;
	var delegate = {};

	//KSU handlers
	delegate.readyHandler = function()
	{
		flashObj = document.getElementById("uploader");
	}

	delegate.selectHandler = function()
	{
		console.log("selectHandler()");
		console.log(flashObj.getTotalSize());
	}

	delegate.singleUploadCompleteHandler = function(args)
	{
		console.log("singleUploadCompleteHandler", args[0].title);
	}

	delegate.allUploadsCompleteHandler = function()
	{
		console.log("allUploadsCompleteHandler");
	}

	delegate.entriesAddedHandler = function(entries)
	{
		console.log(entries);
	}

	delegate.progressHandler = function(args)
	{
		console.log(args[2].title + ": " + args[0] + " / " + args[1]);
	}

	delegate.uiConfErrorHandler = function()
	{
		console.log("ui conf loading error");
	}

	<!--- JavaScript callback methods to activate Kaltura services via the KSU widget.-->
	function upload()
	{
		flashObj.upload();
	}

	function setTags(tags, startIndex, endIndex)
	{
		flashObj.setTags(tags, startIndex, endIndex);
	}

	function addTags(tags, startIndex, endIndex)
	{
		flashObj.addTags(tags, startIndex, endIndex);
	}
	function setTitle(title, startIndex, endIndex)
	{
		flashObj.setTitle(title, startIndex, endIndex);
	}

	function getFiles()
	{
		var files = flashObj.getFiles();
		console.log(files[0].title);
	}

	function addEntries()
	{
		flashObj.addEntries();
	}

	function stopUploads()
	{
		flashObj.stopUploads();
	}

	function setMaxUploads(value)
	{
		flashObj.setMaxUploads(value);
	}
	
		function setPartnerData(value)
	{
		flashObj.setPartnerData(value);
	}

	
	function setMediaType()
	{
		var mediaType = mediaTypeInput.value;
		console.log(mediaType);
		flashObj.setMediaType(mediaType);
	}

	function addTagsFromForm()
	{
		var tags = document.getElementById("tagsInput").value.split(",");
		var startIndex = parseInt(tagsStartIndex.value);
		var endIndex = parseInt(tagsEndIndex.value);
		addTags(tags, startIndex, endIndex);
	}

	function setTagsFromForm()
	{
		var tags = document.getElementById("tagsInput").value.split(",");
		var startIndex = parseInt(tagsStartIndex.value);
		var endIndex = parseInt(tagsEndIndex.value);
		setTags(tags, startIndex, endIndex);
	}

	function setTitleFromForm()
	{
		var startIndex = parseInt(titleStartIndex.value);
		var endIndex = parseInt(titleEndIndex.value);
		setTitle(titleInput.value, startIndex, endIndex);
	}

	function removeFilesFromForm()
	{
		var startIndex = parseInt(removeStartIndex.value);
		var endIndex = parseInt(removeEndIndex.value);
		flashObj.removeFiles(startIndex, endIndex);
		console.log(flashObj.getTotalSize());
	}

	function setGroupId(value)
	{
		flashObj.setGroupId(value);
	}

	function setPermissions(value)
	{
		flashObj.setPermissions(value);
	}

	function setSiteUrl(value)
	{
		flashObj.setSiteUrl(value);
	}

	function setScreenName(value)
	{
		flashObj.setScreenName(value);
	}

	//set parameters to be taken from user input field
	var tagsInput;
	var tagsStartIndex;
	var tagsEndIndex;

	var titleInput;
	var titleStartIndex;
	var titleEndIndex;

	var removeStartIndex;
	var removeEndIndex;
	var maxUploadsInput;

	var partnerDataInput;
	var mediaTypeInput
	var groupId;
	var permissions;
	var screenName;
	var siteUrl;

	function onLoadHandler()
	{
		tagsInput = document.getElementById("tagsInput");
		tagsStartIndex = document.getElementById("tagsStartIndex");
		tagsEndIndex = document.getElementById("tagsEndIndex");

		titleInput = document.getElementById("titleInput");
		titleStartIndex = document.getElementById("titleStartIndex");
		titleEndIndex = document.getElementById("titleEndIndex");

		removeStartIndex = document.getElementById("removeStartIndex");;
		removeEndIndex = document.getElementById("removeEndIndex");

		maxUploadsInput = document.getElementById("maxUploadsInput");
		partnerDataInput = document.getElementById("partnerDataInput");

		groupId = document.getElementById("groupId");
		permissions = document.getElementById("permissions");
		screenName = document.getElementById("screenName");
		siteUrl = document.getElementById("siteUrl");
		mediaTypeInput = document.getElementById("mediaTypeInput");
	}
</script>


</head>
<body onload=onLoadHandler()>
	<div id="flashContainer">
		<form>
			<input type="button" value="Step 1 - Browse & Select">
		</form>
		<div id="uploader"> 
		</div>
		<script language="JavaScript" type="text/javascript">
			var params = {
				allowScriptAccess: "always",
				allowNetworking: "all",
				wmode: "transparent"

			};
			var attributes  = {
				id: "uploader",
				name: "KUpload"
			};
			// set flashVar object
			var flashVars = <?php echo json_encode($flashVars); ?>;
			 <!--embed flash object-->
			swfobject.embedSWF("http://www.kaltura.com/kupload/ui_conf_id/11500", "uploader", "200", "30", "9.0.0", "expressInstall.swf", flashVars, params,attributes);
		</script>
	</div>
	<br/>
	<div id="userInput">
		<form>
			<input type="button" value="Step 2 - Upload" 		onclick="upload()">
			<input type="button" value="Step 3 - Add entries" 	onclick="addEntries()">
			<input type="button" value="Cancel" 		        onclick="stopUploads()">
		</form>

		<form>
			<input type="text" id="mediaTypeInput" />
			<input type="button" value="Set media type" onclick="setMediaType()">
		</form>

		<form>
			<input type="button" value="Get Files " onclick="getFiles()">
		</form>

		<form>
			<input type="text" value="enter tags here" id="tagsInput" />
			<input type="text" value="0" id="tagsStartIndex" />
			<input type="text" value="1" id="tagsEndIndex" />
			<input type="button" value="Add tags" onclick="addTagsFromForm()">
			<input type="button" value="Set tags" onclick="setTagsFromForm()">
		</form>

		<form>
			<input type="text" value="enter title here" id="titleInput" />
			<input type="text" value="0" id="titleStartIndex" />
			<input type="text" value="1" id="titleEndIndex" />
			<input type="button" value="Set title" onclick="setTitleFromForm()">
		</form>

		<form>
			<input type="text" value="0" id="removeStartIndex" />
			<input type="text" value="0" id="removeEndIndex" />
			<input type="button" value="Remove Files" onclick="removeFilesFromForm()">
		</form>

		<form>
			<input type="text" value="0" id="maxUploadsInput" />
			<input type="button" value="Set max uploads" onclick="setMaxUploads(parseInt(maxUploadsInput.value))">
		</form>

		<form>
			<input type="text" value="partner data goes here" id="partnerDataInput" />
			<input type="button" value="Set partner data" onclick="setPartnerData(partnerDataInput.value)">
			<input id="groupId" />
			<input type="button" value="set group id " onClick="setGroupId(groupId.value)">
			<input id="permissions" />
			<input type="button" value="set permissions" onClick="setPermissions(permissions.value)">
			<input id="screenName" />
			<input type="button" value="Set screen name" onClick="setScreenName(screenName.value)">
			<input id="siteUrl" />
			<input type="button" value="set site url" onClick="setSiteUrl(siteUrl.value)">
		</form>
	</div>
</body>
</html>