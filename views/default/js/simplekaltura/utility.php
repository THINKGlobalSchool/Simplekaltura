<?php
/**
 * Simple Kaltura Video Utility JS Library
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.org
 */
?>
//<script>
elgg.provide('elgg.simplekaltura_utility');

elgg.simplekaltura_utility.popupWidth = "<?php echo elgg_get_plugin_setting('kaltura_popup_width'); ?>";
elgg.simplekaltura_utility.popupHeight = "<?php echo elgg_get_plugin_setting('kaltura_popup_height'); ?>";

// Init function 
elgg.simplekaltura_utility.init = function() {	
	elgg.simplekaltura_utility.lightbox_init();
}

// Init simplekaltura lightboxes
elgg.simplekaltura_utility.lightbox_init = function() {
	// Set up kaltura lightboxes (if elements exist)
	if ($('.simplekaltura-lightbox').length) {
		$(".simplekaltura-lightbox").fancybox(elgg.simplekaltura_utility.get_lightbox_init());
	}
}

elgg.simplekaltura_utility.get_lightbox_init = function() {
	return {
		'onComplete': function() {
			// Get kaltura settings/options
			var $container	= $('#kaltura-dynamic-container');	
			var entry_id	= $container.find('input[name=entry_id]').val();
			var wid 		= $container.find('input[name=wid]').val();
			var uiconf_id 	= $container.find('input[name=uiconf_id]').val();
			var autoplay 	= $container.find('input[name=autoplay]').val();
			var target_id	= $container.attr('id');

			window['doPlayCallback'] = function(playerId ){
				//
			};

			if (!kWidget.isIOS()) {
				entry_id += '/video.swf';
			}
	
			kWidget.embed({
				'targetId': target_id,
				'wid': wid,
				'uiconf_id' : uiconf_id,
				'entry_id' : entry_id,
				'flashvars':{
					'externalInterfaceDisabled' : false,
					'autoPlay' : autoplay,
					'width' : elgg.simplekaltura_utility.popupWidth,
					'height' : elgg.simplekaltura_utility.popupHeight,
				},
				'readyCallback': function( playerId ){
					//
				}
			});
		},
		'onCleanup': function() {
		},
		'onClosed': function() {
		}
	};
}

elgg.register_hook_handler('init', 'system', elgg.simplekaltura_utility.init);

// Custom Kaltura Buttons
function customFunc1 (entryId){ 
	// Get video guid
	var video_guid = $('#video_guid_' + entryId).val();

	// Ajax Spinner
	$('.elgg-kaltura-embed-container').addClass('elgg-ajax-loader');

	// Get embed
	elgg.action('simplekaltura/get_embed', {
		data: {
			video_guid: video_guid,
		}, 
		success: function(data) {	
			$('.elgg-kaltura-embed-container').removeClass('elgg-ajax-loader');
			if (data.status != -1) {
				$('.elgg-kaltura-embed-container').html(data.output);
			}
		},
	});
}