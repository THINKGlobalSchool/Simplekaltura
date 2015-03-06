/**
 * Elgg Simple Kaltura Uploader
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */

define(['jquery', 'elgg'], function ($, elgg) {
	var SKUploader = $.fn.SKUploader = function(options) {
		var self = this,
		uploader_id = this.attr('id'),
		formEventsBound = false, // @todo not sure if this needed anymore
		flashObj = null,
		dialog = null,
		progress = null,
		selectedFiles = null,
		debug = false;

		this.KSUDelegates = {};

		// Default options
		var defaults = {
			uploaderInput: null,
			tagsInput: null,
			titleInput: null,
			formSubmit: null,
			flashVars: {},
			uiConfId: null,
			maxUpload: 5000,
			dialogTitle: 'Uploading',
			uploaderFlashId: 'simplekaltura-uploader-flash'
		}

		// Merge options
		var options = $.extend(defaults, options);

		/* ***********************************************
		 * UTILITIES
		 *********************************************** */

		// Helper to register elgg hooks
		function registerElggHook(name, type, handler) {
			elgg.register_hook_handler(name, type, handler);
		}

		/**
		 * Bytes to size helper function
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

		/** Register elgg hooks **/
		// registerElggHook('thing', 'ie', this.dosomething);


		/* ***********************************************
		 * SKUploader functions
		 *********************************************** */

		/**
		 * Init new SKUploader
		 */ 
		this.init = function() {
			// Disable the form submit input
			options.formSubmit.attr('disabled', 'DISABLED').addClass('elgg-state-disabled');

			this.bindEvents();
			this.createDialog();
			this.createSWF();
		}	

		/**
		 * Create the KSU swf object
		 */
		this.createSWF = function() {
			// Create container
			$('<div>').attr({
				id: 'simplekaltura-uploader-flash'
			}).appendTo(this);

			var params = {
				allowScriptAccess: "always",
				allowNetworking: "all",
				wmode: "transparent"
			}

			var attributes  = {
				id: options.uploaderFlashId,
				name: "KUpload",
			};

			var width = options.uploaderInput.innerWidth();
			var height = options.uploaderInput.innerHeight();

			var swfUrl = "//www.kaltura.com/kupload/ui_conf_id/" + options.uiConfId;

			swfobject.embedSWF(swfUrl, options.uploaderFlashId, width, height, "9.0.0", "expressInstall.swf", options.flashVars, params, attributes);
		}

		/**
		 * Bind needed events
		 */
		this.bindEvents = function() {
			// Click handler for save button
			options.formSubmit.on('click', function(event) {
				self.upload();
				event.preventDefault();
			});
			formEventsBound = true;
		}

		/**
		 * Create and initialize the uploader progress dialog box
		 */
		this.createDialog = function() {
			// Create dialog outer element
			var $dlg = $(document.createElement('div'));
			$dlg.addClass('hidden');
			$dlg.attr('id', 'simplekaltura-upload-dialog');
			$dlg.attr('title', options.dialogTitle);

			// Progress bar element
			var $progress = $(document.createElement('div'));
			$progress.attr('id', 'simplekaltura-upload-progress');

			// Add progress to outer element
			$dlg.append($progress);

			progress = $progress;

			// Init ui dialog
			$dlg.dialog({
				autoOpen: false,
				width: 400,
				height: 85,
				modal: true,
				draggable: false,
				resizeable: false,
				open: function(event, ui) {
					$(".ui-dialog-titlebar-close").hide();
				},
				closeOnEscape: false
			});

			dialog = $dlg;
		}

		/**
		 * Create selected files content
		 */
		this.createSelectedFilesHTML = function(files, size) {
			var $ospan = $(document.createElement('span'));
			$ospan.html(files[0].toString());

			var $sspan = $(document.createElement('span'));
			$sspan.addClass('pls');
			$sspan.attr('style', 'font-color: #666;');
			$sspan.html(bytesToSize(size, 2));

			var $remove_link = $(document.createElement('a'));
			$remove_link.addClass('pls')
			$remove_link.on('click', this.removeSelectedFile);

			var $ispan = $(document.createElement('span'));
			$ispan.addClass('elgg-icon elgg-icon-delete float-left prm');
			$ispan.attr('style', 'z-index: 1000;');

			$remove_link.html($ispan);

			$ospan
				.append($sspan)
				.append($remove_link);


			var $selected = $(document.createElement('div'));
			$selected.attr('id', 'simplekaltura-selected-files');

			$selected.append($ospan);

			this.append($selected);

			selectedFiles = $selected;
		}

		/**
		 * Removes a file from the uploader
		 */
		this.removeSelectedFile = function() {
			flashObj.removeFiles(0, 0);

			// Disable the submit button
			options.formSubmit.attr('disabled', 'disabled').addClass('elgg-state-disabled');;

			// Show select button
			$('#' + options.uploaderFlashId).removeClass('z-negative');
			options.uploaderInput.show();

			// Clear selected files
			selectedFiles.remove();
		}

		/**
		 * Start video upload
		 */
		this.upload = function() {
			// Set/check inputs
			if (this.processUserInput()) {
				dialog.show();
				dialog.dialog("open");
				progress.progressbar({ value: 0 });

				// Disable submit
				options.formSubmit.attr('disabled', 'DISABLED').addClass('elgg-state-disabled');;

				// Upload!
				flashObj.upload();
			}
		}

		/**
		 * Process (and validate) user input for the upload form
		 */
		this.processUserInput = function() {
			var title = $(options.titleInput).val();

			var params = {form: $(options.tagsInput).closest('form')};

			if (!elgg.trigger_hook('checkTags', 'typeaheadtags', params, true)) {
				return false;
			}

			var tags = $(options.tagsInput).val().split(",");

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

		/* ***********************************************
		 * KSU DELEGATES
		 *********************************************** */

		 /**
		  * readyHandler - fired when the SWF is ready
		  */
		 this.readyHandler = function() {
	 		flashObj = document.getElementById(options.uploaderFlashId);
			flashObj.setMediaType('video');

			// Only going to allow one upload from this form
			flashObj.setMaxUploads(1);

			if (options.debug) {
				console.log('readyHandler() fired');
				console.log('flashObj:', flashObj);	
			}
		 }

		 /**
		  * selectHandler - fired when the user selects a file
		  */
		 this.selectHandler = function() {
			var fileSize = (flashObj.getTotalSize() / 1024 / 1024);
			if (fileSize < options.maxUpload) {
				// Hide select button
				$('#' + options.uploaderFlashId).addClass('z-negative');
				options.uploaderInput.hide();

				// Enable the submit button
				options.formSubmit.removeAttr('disabled').removeClass('elgg-state-disabled');
				
				// Grab files and display for user
				this.createSelectedFilesHTML(flashObj.getFiles(), flashObj.getTotalSize());
			} else {
				elgg.register_error(elgg.echo('simplekaltura:error:filetoolarge', [maxUpload]));
			}
			
			if (options.debug) {
				console.log('selectHandler() fired');
				console.log('fileSize:', fileSize);
				console.log('maxUpload:', options.maxUpload);
				console.log('flashObj:', flashObj);
			}
		}

		/**
		 * singleUploadCompleteHandler - unused.. but it's here anyway
		 */
		this.singleUploadCompleteHandler = function(args) {
			if (options.debug) {
				console.log('singleUploadCompleteHandler() fired');
				console.log(args);
				console.log('flashObj:', flashObj);	
			}
		}

		/**
		 * allUploadsCompleteHandler - fired when all uploads are complete
		 */
		this.allUploadsCompleteHandler = function() {
			flashObj.addEntries();

			if (options.debug) {
				console.log('allUploadsCompleteHandler() fired');
				console.log('flashObj:', flashObj);	
			}
		}

		/**
		 * entriesAddedHandler - fired when entries are successfully added
		 */
		this.entriesAddedHandler = function(entries) {
			// Get parent form
			var $form = options.formSubmit.parents('form');
			console.log($form);

			// Create and add entryid input
			$('<input>').attr({
				type: 'hidden',
				id: 'k_entryid',
				name: 'k_entryid',
				value: entries[0].entryId
			}).appendTo($form);

			// Create and add bytesloaded input
			$('<input>').attr({
				type: 'hidden',
				id: 'k_bytesloaded',
				name: 'k_bytesloaded',
				value: entries[0].bytesLoaded
			}).appendTo($form);

			// Create and add filetype input
			$('<input>').attr({
				type: 'hidden',
				id: 'k_filetype',
				name: 'k_filetype',
				value: entries[0].extension
			}).appendTo($form);

			// Create and add guid input
			$('<input>').attr({
				type: 'hidden',
				id: 'k_guid',
				name: 'k_guid',
				value: entries[0].guid
			}).appendTo($form);


			if (options.debug) {
				console.log('entriesAddedHandler() fired');
				console.log('entries:', entries);	
			}

			// Finally submit the post form
			$form.submit();
		}

		/**
		 * progressHandler - fired when the uploader makes progress
		 */
		this.progressHandler = function(args) {
			var p_value = Math.round((args[0] / args[1]) * 100);

			progress.progressbar("option", "value", p_value);

			var $dialog_title = $("#simplekaltura-upload-dialog").siblings('.ui-dialog-titlebar').find('span.ui-dialog-title');

			var title = $dialog_title.html().substring(0, elgg.echo('simplekaltura:label:uploadingdialogtitle').length);
			
			$dialog_title.html(title + ' ' + p_value + '%');
		}

		/**
		 * uiConfErrorHandler - fired when the swf encounters a uiconf error
		 */
		this.uiConfErrorHandler = function() {
			if (options.debug) {
				console.log("ui conf loading error");
			}
		}

		this.init();
		return this;
	};

	return SKUploader;
});
