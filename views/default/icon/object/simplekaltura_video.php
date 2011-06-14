<?php
/**
 * Simple Kaltura video icon
 *
 * C&P from elgg/views/default/icon/default.php to add $vars['link_class']
 *
 * @todo When http://trac.elgg.org/ticket/3567 is implement this file can be removed!
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['link_class'] The class to pass to the output/url view.
 */

$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img_src = $entity->getIconURL($vars['size']);
$img = "<img src=\"$img_src\" alt=\"$title\" />";

if ($url) {
	$class = elgg_extract('link_class', $vars, array());
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $img,
		'class' => $class
	));
} else {
	echo $img;
}
