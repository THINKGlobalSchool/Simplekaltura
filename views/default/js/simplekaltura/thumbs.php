<?php
/**
 * Simple Kaltura Thumbnail Input lib
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.org
 */
?>
//<script>
elgg.provide('elgg.simplekaltura_thumbs');

// Init function 
elgg.simplekaltura_thumbs.init = function() {
	// Load handler for thumbnail select image
	$('#simplekaltura-select-thumbnail').load(function(event) {
		$(this).show(); // If previously hidden, show the element
	});

	$('#simplekaltura-thumbnail-slider').slider({
		max: $('#simplekaltura-thumbnail-slider').find('span.duration').html(),
		min: 1,
		step: 1,
		change: function(event, ui) {
			// Get slider value
			var value = $(this).slider('value');

			// Find thumbnail element
			var select_thumbnail = $('#simplekaltura-select-thumbnail');
			
			// Hide thumbnail element
			select_thumbnail.hide();

			// Show ajax loader in wrapper
			select_thumbnail.parent().addClass('elgg-ajax-loader');

			// Get the thumbnail url without the vid_sec parameter
		 	var src = select_thumbnail.attr('src').substring(0, select_thumbnail.attr('src').indexOf('vid_sec'));
		 	
		 	// Update thumbnail src
			select_thumbnail.attr('src', src + 'vid_sec/' + value);

			// Set hidden value for thumbnail second
			$("input#thumbs-name").val(value);

			// Update time element
			var time = $(this).siblings('span.elgg-subtext');
			time = time.html(elgg.simplekaltura_thumbs.toHHMMSS(value));
		}, 
		create: function(event, ui) {
			var time = $(this).siblings('span.elgg-subtext');
			time.html(elgg.simplekaltura_thumbs.toHHMMSS($(this).slider('value')));
		}
	});
}

// Convert seconds to friendly HH:MM:SS 
elgg.simplekaltura_thumbs.toHHMMSS = function(seconds) {
    sec_numb    = parseInt(seconds);
    var hours   = Math.floor(sec_numb / 3600);
    var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
    var seconds = sec_numb - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    var time    = hours+':'+minutes+':'+seconds;
    return time;
}

elgg.register_hook_handler('init', 'system', elgg.simplekaltura_thumbs.init);