<?php
/**
 * Simple Kaltura video icon
 *
 * C&P from elgg/views/default/icon/default.php to add $vars['link_class']
 *
 * @todo When http://trac.elgg.org/ticket/3567 is implement this file can be removed!
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

$width = elgg_get_plugin_setting("kaltura_{$size}thumb_width");
$height = elgg_get_plugin_setting("kaltura_{$size}thumb_height");

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