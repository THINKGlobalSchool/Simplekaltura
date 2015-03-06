<?php
/**
 * Simple Kaltura video icon
 *
 * Modified from elgg/views/default/icon/default.php
 *
 * - Includes plugin settings for thumbnail width/height
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       small, medium (default), large
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class for the link
 */

$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$class = elgg_extract('img_class', $vars, '');

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$size = $vars['size'];

$width = elgg_get_plugin_setting("kaltura_{$size}thumb_width", 'simplekaltura');
$height = elgg_get_plugin_setting("kaltura_{$size}thumb_height", 'simplekaltura');

$img = elgg_view('output/img', array(
	'src' => $entity->getIconURL($vars['size']),
	'alt' => $title,
	'class' => $class,
	'width' =>  $width,
	'height' => $height,
));

if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	);
	$class = elgg_extract('link_class', $vars, '');
	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
} else {
	echo $img;
}