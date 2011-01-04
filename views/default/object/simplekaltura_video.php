<?php
/**
 * Simple Kaltura Entity View
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

// Check for valid entity
if (elgg_instanceof($vars['entity'], 'object', 'simplekaltura_video') ) { // Simpler check!
	if ($vars['full']) {
		echo elgg_view("simplekaltura/kaltura_video_full", $vars);	
	} else {
		echo elgg_view("simplekaltura/kaltura_video_listing", $vars);
	}
} else {
	// If were here something went wrong..
	$owner = $vars['user'];
	$canedit = false;
	forward(REFERER);
}
?>