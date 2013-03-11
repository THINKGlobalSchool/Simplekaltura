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
	// Delegate change handler for radio buttons
	$(document).delegate('input[name="thumbnail_second_radio"]', 'change', elgg.simplekaltura_thumbs.radioChange);

	// Delegate keyup handler for thumbnail second text input
	$(document).delegate('input[name="thumbnail_second"]', 'keyup', elgg.simplekaltura_thumbs.secondKeyup);	

	// Delegate click handler for regenerate thumbs button
	$(document).delegate('input.simplekaltura-regenerate-thumbs', 'click', elgg.simplekaltura_thumbs.regenerateClick);

	// Delegate click handler for random image divs
	$(document).delegate('.simplekaltura-random-thumbnail', 'click', elgg.simplekaltura_thumbs.randomClick);
}

// On Change handler for radio buttons
elgg.simplekaltura_thumbs.radioChange = function(event) {
	var second = $(this).val();
	$(this).parent('.simplekaltura-random-thumbnail').addClass('selected').siblings().removeClass('selected');
	$('input[name="thumbnail_second"]').val(second);
	event.preventDefault();
}

// Keyup handler for thumbnail second input
elgg.simplekaltura_thumbs.secondKeyup = function(event) {
	// Uncheck selected radio button
	$('input[name="thumbnail_second_radio"]:checked').prop('checked', false);
	event.preventDefault();
}

// Click handler for regenerate thumbs button
elgg.simplekaltura_thumbs.regenerateClick = function(event) {
	var name = $('input#thumbs-name').attr('name');
	var video_guid = $('input[name="video_guid"]').val();

	$('.simplekaltura-random-thumbnails').html('<div class="elgg-ajax-loader"></div>');

	elgg.get({
			url: elgg.config.wwwroot + 'ajax/view/input/simplekaltura_thumbs',
			data: {
				name: name,
				video_guid: video_guid
			},
			dataType: "html",
			cache: true,
			success: function(data) {
				$('.simplekaltura-random-thumbnails').replaceWith($(data).filter('.simplekaltura-random-thumbnails'));
			}
	});
	event.preventDefault();
}

// Click handler for regenerate thumbs button
elgg.simplekaltura_thumbs.randomClick = function(event) {
	// Select and trigger change for radio button child
	$(this).children('input[name="thumbnail_second_radio"]').prop('checked', true).trigger('change');
}

elgg.register_hook_handler('init', 'system', elgg.simplekaltura_thumbs.init);