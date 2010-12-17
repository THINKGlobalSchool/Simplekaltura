<?php
/**
 * Simple Kaltura library
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

/* Get edit/create content */
function simplekaltura_get_page_content_edit($guid) {
	$vars = array();
	if ($guid) {
		$video = get_entity((int)$guid);

		if (elgg_instanceof($video, 'object', 'simplekaltura_video') && $video->canEdit()) {
			$vars['entity'] = $video;

			elgg_push_breadcrumb($video->title, $video->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view('simplekaltura/forms/edit', $vars);
	
		} else {
			$content = elgg_echo('simplekaltura:error:notfound');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('simplekaltura:label:new'));
		$content = elgg_view('simplekaltura/forms/edit', $vars);
	}

	return array('content' => $content, 'title' => elgg_echo('simplekaltura:title:uploadnew'), 'layout' => 'one_column_with_sidebar');
}

/* View a video  */
function simplekaltura_get_page_content_view($guid) {
	$video = get_entity($guid);
	$owner = get_entity($video->container_guid);
	set_page_owner($owner->getGUID());
	elgg_push_breadcrumb(elgg_echo('simplekaltura:menu:allvideos'), elgg_get_site_url() . 'pg/videos');
	elgg_push_breadcrumb($owner->name, elgg_get_site_url() . 'pg/videos/' . $owner->username);
	elgg_push_breadcrumb($video->title, $video->getURL());
	$content_info['title'] = $video->title;
	$content_info['content'] = elgg_view_entity($video, true);
	$content_info['layout'] = 'one_column_with_sidebar';
	
	return $content_info;
}

?>