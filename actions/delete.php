<?php
/**
 * Simple Kaltura delete action
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$guid = get_input('guid', null);
$video = get_entity($guid);

if (elgg_instanceof($video, 'object', 'simplekaltura_video') && $video->canEdit()) {
	$container = get_entity($video->container_guid);
	if (simplekaltura_delete_video($video)) {
		system_message(elgg_echo('simplekaltura:success:delete'));
		forward("pg/videos/{$container->username}");
	} else {
		register_error(elgg_echo('simplekaltura:error:delete'));
	}
} else {
	register_error(elgg_echo('simplekaltura:error:notfound'));
}

forward(REFERER);