<?php
/**
 * Simple Kaltura KSU Widget JS
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
//<script type="text/javascript">
var flashObj;
var delegate = {};

// Set some stuff
$(document).ready(function () {
	onLoadHandler();
});

//KSU handlers
delegate.readyHandler = function() {
	flashObj = document.getElementById("simplekaltura-uploader");
	flashObj.setMediaType('video');

	// Only going to allow one upload from this form
	flashObj.setMaxUploads(1);
}

delegate.selectHandler = function() {
	// Hide select button
	$("#simplekaltura-uploader").addClass('z-negative');
	$("#simplekaltura-uploader-submit").hide();

	// Enable the submit button
	$('#simplekaltura_submit').removeAttr('disabled');
	$('#simplekaltura_submit').removeClass('disabled');

	// Grab files and display for user
	createSelectedFilesHTML(flashObj.getFiles());
}

delegate.singleUploadCompleteHandler = function(args) {
}

delegate.allUploadsCompleteHandler = function() {
	addEntries();
}

delegate.entriesAddedHandler = function(entries) {
	// Set hidden inputs
	$('#k_entryid').val(entries[0].entryId);
	$('#k_bytesloaded').val(entries[0].bytesLoaded);
	$('#k_filetype').val(entries[0].extension);
	$('#k_guid').val(entries[0].guid);

	// Finally submit the post form
	$('#simplekaltura_submit').parents('form').submit();
}

delegate.progressHandler = function(args) {
	//console.log(args[2].title + ": " + args[0] + " / " + args[1]);
	var p_value = Math.round((args[0] / args[1]) * 100) ;
	$("#simplekaltura-upload-progress").progressbar("option", "value", p_value);
	$("#simplekaltura-upload-percent").html(p_value + '%');
}

delegate.uiConfErrorHandler = function() {
	console.log("ui conf loading error");
}

function upload() {
	// Set/check inputs
	if (processUserInput()) {
		$('#simplekaltura-upload-dialog').show();
		$('#simplekaltura-upload-dialog').dialog("open");
		$("#simplekaltura-upload-progress").progressbar({ value: 0 });
		// Upload!
		flashObj.upload();
	}
}

function addEntries() {
	flashObj.addEntries();
}

function stopUploads() {
	flashObj.stopUploads();
}

//set parameters to be taken from user input field
var tagsInput;
var titleInput;

function onLoadHandler() {
	// Set up user input fields
	tagsInput = $('input[name="video_tags"]');
	titleInput = document.getElementById("video_title");
}


/**
 * Creates HTML to display information about their selected file
 */
function createSelectedFilesHTML(files) {
	var content = "<span>";
	content += files[0].toString();
	content += "<a onclick='removeSelectedFile();'><span style='z-index: 1000;' class='elgg-icon elgg-icon-delete left prm'></span></a>";
	content += "</span>";

	/*
	$(files).each(function(i, e) {
		content += "<span>";
		content += files[0];
		content += "<span class='pls' style='font-color: #666'>";
		content += bytesToSize(e.bytesTotal, 2); // Make this a little easier to read
		content += "</span>";
		content += "<a onclick='removeSelectedFile(" + i + ");'><span class='elgg-icon elgg-icon-delete left prm'></span></a>";
		content += "</span>";
	});
	*/

	$('#simplekaltura-selected-files').html(content);
}


/**
 * Removes a file from the uploader
 */
function removeSelectedFile() {
	flashObj.removeFiles(0, 0);

	// Disable the submit button
	$('#simplekaltura_submit').attr('disabled', 'disabled');
	$('#simplekaltura_submit').addClass('disabled');

	// Show select button
	$("#simplekaltura-uploader").removeClass('z-negative');
	$("#simplekaltura-uploader-submit").show();

	// Clear selected files
	$('#simplekaltura-selected-files').html('');
}

/**
 * Grab and set user input for the uploaded video
 */
function processUserInput() {
	var title = titleInput.value;
	var tags = tagsInput.val().split(",");

	// Check for required title
	if (title) {
		flashObj.setTitle(title, 0, 0);
		flashObj.setTags(tags, 0, 0);
		return true;
	} else {
		$('#video_title_container').append("<span class='simplekaltura-error'>* Title is required</span>");
		return false;
	}
}

/**
 * Convert bytes to human readable
 */
function bytesToSize(bytes, precision) {
	var kilobyte = 1024;
	var megabyte = kilobyte * 1024;
	var gigabyte = megabyte * 1024;
	var terabyte = gigabyte * 1024;

	if ((bytes >= 0) && (bytes < kilobyte)) {
		return bytes + ' B';

	} else if ((bytes >= kilobyte) && (bytes < megabyte)) {
		return (bytes / kilobyte).toFixed(precision) + ' KB';

	} else if ((bytes >= megabyte) && (bytes < gigabyte)) {
		return (bytes / megabyte).toFixed(precision) + ' MB';

	} else if ((bytes >= gigabyte) && (bytes < terabyte)) {
		return (bytes / gigabyte).toFixed(precision) + ' GB';

	} else if (bytes >= terabyte) {
		return (bytes / terabyte).toFixed(precision) + ' TB';

	} else {
		return bytes + ' B';
	}
}