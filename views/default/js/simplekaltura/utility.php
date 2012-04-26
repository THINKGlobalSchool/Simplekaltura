<?php
/**
 * Simple Kaltura Video Utility JS Library
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */
?>
//<script>
elgg.provide('elgg.simplekaltura_utility');

// Init function 
elgg.simplekaltura_utility.init = function() {	
	elgg.simplekaltura_utility.lightbox_init();
}

// Init simplekaltura lightboxes
elgg.simplekaltura_utility.lightbox_init = function() {
	// Set up kaltura lightboxes
	$(".simplekaltura-lightbox").fancybox({
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
			
			var kdp = null;
			
			kWidget.embed({
				'targetId': target_id,
				'wid': wid,
				'uiconf_id' : uiconf_id,
				'entry_id' : entry_id,
				'flashvars':{
					'externalInterfaceDisabled' : false,
					'autoPlay' : autoplay,
					'width' : 600,
					'height' : 450,
				},
				'readyCallback': function( playerId ){
					/* Don't need this */
					//kdp = document.getElementById(playerId);
					//kdp.addJsListener( 'doPlay', 'doPlayCallback');
				}
			});
		},
		'onCleanup': function() {
		},
		'onClosed': function() {
		}
	});
}

elgg.register_hook_handler('init', 'system', elgg.simplekaltura_utility.init);
elgg.register_hook_handler('populated', 'modules', elgg.simplekaltura_utility.lightbox_init);