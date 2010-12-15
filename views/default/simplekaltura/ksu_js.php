<?php
/**
 * Simple Kaltura Widget JS
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<!---	JavaScript handler methods to react to upload events. -->
<script type="text/javascript">
	var flashObj;
	var delegate = {};
	
	// Set some stuff
	$(document).ready(function () {
		onLoadHandler();	
		// @TODO Set title, tags from elgg inputs	
	});

	//KSU handlers
	delegate.readyHandler = function()
	{
		flashObj = document.getElementById("simplekaltura-uploader");
		flashObj.setMediaType('video');
		
		// Only going to allow one upload from this form
		flashObj.setMaxUploads(1);
	}

	delegate.selectHandler = function()
	{
		console.log("selectHandler()");
		console.log(flashObj.getTotalSize());
		
		// Hide select button
		$("#simplekaltura-uploader").addClass('z-negative');
		$("#simplekaltura-uploader-submit").hide();
		
		// Grab files and display for user
		createSelectedFilesHTML(flashObj.getFiles());
	}

	delegate.singleUploadCompleteHandler = function(args)
	{
		console.log("singleUploadCompleteHandler", args[0].title);
	}

	delegate.allUploadsCompleteHandler = function()
	{
		addEntries();
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

	function addEntries()
	{
		flashObj.addEntries();
	}

	function stopUploads()
	{
		flashObj.stopUploads();
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

	//set parameters to be taken from user input field
	function onLoadHandler()
	{

	}
	
	<!-- Helpers -->
	function createSelectedFilesHTML(files) {
		var content = '';
		for (file in files) { 
			content += "<span class='simplekaltura-selected-file'>";
			content += files[file].title;
			content += "<span class='simplekaltura-file-size'>"; 
			content += files[file].bytesTotal;
			content += "</span>";
			content += "<span class='delete_button' style='float: left; margin-top: 3px; margin-right: 4px;'><a onclick='removeSelectedFile(" + file + ");'>x</a></span>";
			content += "</span>";
		}
		
		$('#simplekaltura-selected-files').html(content);
	}
	
	function removeSelectedFile(index) {
		flashObj.removeFiles(index, index);
		
		// Show select button
		$("#simplekaltura-uploader").removeClass('z-negative');
		$("#simplekaltura-uploader-submit").show();
		
		// Grab files and display for user
		createSelectedFilesHTML(flashObj.getFiles());
	}


</script>