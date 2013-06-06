<?php
/**
 * Simple Kaltura feature video action
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid');
$action = get_input('action_type');

$video = get_entity($guid);

if (!elgg_instanceof($video, 'object', 'simplekaltura_video')) {
	register_error(elgg_echo('simplekaltura:error:featured'));
	forward(REFERER);
}

//get the action, is it to feature or unfeature
if ($action == "feature") {
	$video->featured_video = "yes";
	system_message(elgg_echo('simplekaltura:success:featuredon', array($video->title)));
} else {
	$video->featured_video = "no";
	system_message(elgg_echo('simplekaltura:success:featuredoff', array($video->title)));
}

forward(REFERER);
