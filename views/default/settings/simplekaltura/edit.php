<?php
/**
 * Simple Kaltura Settings
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */
?>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:admintags'); ?></label><br /><br />
	<?php 
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_admin_tags]', 
										'value' => $vars['entity']->kaltura_admin_tags)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:partnerid'); ?></label><br /><br />
	<?php 
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_partnerid]', 
										'value' => $vars['entity']->kaltura_partnerid)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:emailaccount'); ?></label><br /><br />
	<?php 
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_email_account]', 
										'value' => $vars['entity']->kaltura_email_account)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:passwordaccount'); ?></label><br /><br />
	<?php 
	echo elgg_view('input/password', array(
										'internalname' => 'params[kaltura_password_account]', 
										'value' => $vars['entity']->kaltura_password_account)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:customplayerid'); ?></label><br /><br />
	<?php 
	$id = $vars['entity']->kaltura_custom_player_id;
	if (!$id) {
		$id = '1000106'; // Default Kaltura ID
	}
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_custom_player_id]', 
										'value' => $id)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:playerheight'); ?></label><br /><br />
	<?php 
	$height = $vars['entity']->kaltura_player_height;
	if (!$height) {
		$height = '330'; // Default Height
	}
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_player_height]', 
										'value' => $height)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:playerwidth'); ?></label><br /><br />
	<?php 
	$width = $vars['entity']->kaltura_player_width;
	if (!$width) {
		$width = '400'; // Default Width
	}
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_player_width]', 
										'value' => $width)
										); 
	?>
</p>
<p>
	<label><?php echo elgg_echo('simplekaltura:label:thumbnailurl'); ?></label><br /><br />
	<?php 
	$url = $vars['entity']->kaltura_thumbnail_url;
	if (!$url) {
		$url = 'http://cdn.kaltura.com/p/0/thumbnail/entry_id/'; // Default Width
	}
	echo elgg_view('input/text', array(
										'internalname' => 'params[kaltura_thumbnail_url]', 
										'value' => $url)
										); 
	?>
</p>