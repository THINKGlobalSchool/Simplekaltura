<?php
/**
 * Simple Kaltura Group Module
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */

$page_owner = elgg_get_page_owner();

if ($page_owner->simplekaltura_enable != "yes") {
	return;
}

//grab the groups bookmarks 
$videos = elgg_get_entities(array(
	'type' => 'object', 
	'subtype' => 'simplekaltura_video', 
	'container_guid' => $page_owner->getGUID(), 'limit' => 6
));
?>
<div class="group_tool_widget videos">
<span class="group_widget_link"><a href="<?php echo elgg_get_site_url() . "pg/videos/owner/" . elgg_get_page_owner()->username; ?>"><?php echo elgg_echo('link:view:all')?></a></span>
<h3><?php echo elgg_echo('simplekaltura:spotvideo') ?></h3>
<?php	
if($videos){
	foreach($videos as $v){
			
		//get the owner
		$owner = $v->getOwnerEntity();

		//get the time
		$friendlytime = elgg_view_friendly_time($v->time_created);
		
	    $info = "<div class='entity_listing_icon'>" . elgg_view('profile/icon',array('entity' => $v->getOwnerEntity(), 'size' => 'tiny')) . "</div>";

		//get the blog entries body
		$info .= "<div class='entity_listing_info'><p class='entity_title'><a href=\"{$v->getURL()}\">{$v->title}</a></p>";
				
		//get the user details
		$info .= "<p class='entity_subtext'>{$friendlytime}</p>";
		$info .= "</div>";
		//display 
		echo "<div class='entity_listing clearfix'>" . $info . "</div>";
	} 
}
if(elgg_get_page_owner()->isMember(get_loggedin_user())){  
	$add = elgg_get_site_url() . "pg/videos/new/" . $page_owner->getGUID();
	echo "<p class='margin_top'><a href=\"{$add}\">" . elgg_echo("simplekaltura:title:uploadnew") . "</a></p>";
}
echo "</div>";