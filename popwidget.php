<?php
/**
 * Simple Kaltura Video Widget
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * @TODO make into a function to generate the widget?
 */
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
                    
$player_height = get_input('height');  
$player_width  = get_input('width'); 
$entryid = get_input('entryid');
$autoplay = get_input('autoplay', '0');

// Kaltura Widget
$widget = elgg_view('simplekaltura/widget', array(
	'height' => $player_height,
	'width'	=> $player_width,
	'entryid' => $entryid,
	'autoplay' => $autoplay
));

echo $widget;

?>
