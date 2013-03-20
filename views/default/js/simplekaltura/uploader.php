<?php
/**
 * Simple Kaltura KSU Widget JS
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 * @todo make this a typical elgg js lib
 */
?>
//<script type="text/javascript">
var flashObj;
var delegate = {};

var maxUpload = "<?php echo elgg_get_plugin_setting('kaltura_upload_max', 'simplekaltura'); ?>";

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

	// Debug
	//console.log('readyHandler()');
}

delegate.selectHandler = function() {
	// Debug code
	//console.log('selectHandler()');
	//console.log(flashObj);

	var fileSize = (flashObj.getTotalSize() / 1024 / 1024);

	if (fileSize < maxUpload) {
		// Hide select button
		$("#simplekaltura-uploader").addClass('z-negative');
		$("#simplekaltura-uploader-submit").hide();

		// Enable the submit button
		$('#simplekaltura-submit').removeAttr('disabled').removeClass('elgg-state-disabled');

		// Grab files and display for user
		createSelectedFilesHTML(flashObj.getFiles(), flashObj.getTotalSize());
	} else {
		elgg.register_error(elgg.echo('simplekaltura:error:filetoolarge', [maxUpload]));
	}
}

delegate.singleUploadCompleteHandler = function(args) {
}

delegate.allUploadsCompleteHandler = function() {
	addEntries();

	// Debug
	//console.log('allUploadsCompleteHandler()');
}

delegate.entriesAddedHandler = function(entries) {
	// Set hidden inputs
	$('#k_entryid').val(entries[0].entryId);
	$('#k_bytesloaded').val(entries[0].bytesLoaded);
	$('#k_filetype').val(entries[0].extension);
	$('#k_guid').val(entries[0].guid);

	// Finally submit the post form
	$('#simplekaltura-submit').parents('form').submit();

	// Debug
	//console.log('entriesAddedHandler()');
}

delegate.progressHandler = function(args) {
	//console.log(args[2].title + ": " + args[0] + " / " + args[1]);
	var p_value = Math.round((args[0] / args[1]) * 100) ;
	$("#simplekaltura-upload-progress").progressbar("option", "value", p_value);

	var $dialog_title = $("#ui-dialog-title-simplekaltura-upload-dialog");

	var title = $dialog_title.html().substring(0, elgg.echo('simplekaltura:label:uploadingdialogtitle').length);
	
	$dialog_title.html(title + ' ' + p_value + '%');
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

		// Disable submit
		$('#simplekaltura-submit').attr('disabled', 'disabled').addClass('elgg-state-disabled');;

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
function createSelectedFilesHTML(files, size) {
	//console.log(size);
	var content = "<span>";
	content += files[0].toString();
	content += "<span class='pls' style='font-color: #666'>";
	content += bytesToSize(size, 2); // Make this a little easier to read
	content += "</span>";
	content += "<a class='pls' onclick='removeSelectedFile();'><span style='z-index: 1000;' class='elgg-icon elgg-icon-delete left prm'></span></a>";
	content += "</span>";

	$('#simplekaltura-selected-files').html(content);
}


/**
 * Removes a file from the uploader
 */
function removeSelectedFile() {
	flashObj.removeFiles(0, 0);

	// Disable the submit button
	$('#simplekaltura-submit').attr('disabled', 'disabled').addClass('elgg-state-disabled');;

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

	var params = {form: tagsInput.closest('form')};

	if (!elgg.trigger_hook('checkTags', 'typeaheadtags', params, null)) {
		return false;
	}

	var tags = tagsInput.val().split(",");

	// Check for required title
	if (title) {
		flashObj.setTitle(title, 0, 0);
		flashObj.setTags(tags, 0, 0);
		return true;
	} else {
		elgg.register_error(elgg.echo('simplekaltura:error:titlerequired'));
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