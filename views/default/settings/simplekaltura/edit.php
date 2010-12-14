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