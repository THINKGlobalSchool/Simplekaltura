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
elgg.provide('elgg.simplekalura_utility');

// Init function 
elgg.simplekalura_utility.init = function() {	
	// Set up kaltura lightboxes
	$(".simplekaltura-lightbox").fancybox({
		'onComplete': function() {	
		},
		'onCleanup': function() {
		},
		'onClosed': function() {
		}
	});
}

elgg.register_hook_handler('init', 'system', elgg.simplekalura_utility.init);