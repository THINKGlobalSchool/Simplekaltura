<?php
/**
 * Simple Kaltura English Translation
 *
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.com/
 *
 */

$entity_title = elgg_get_plugin_setting('kaltura_entity_title', 'simplekaltura');

return array(

	// Generic
	'video' => 'Video',
	'videos' => 'Videos',
	'item:object:simplekaltura_video' => $entity_title,
	'simplekaltura_video:new' => 'Upload New Video',
	'simplekaltura_video' => 'Videos',
	'simplekaltura:owner' => '%s\'s videos',
	'simplekaltura:none' => 'No Videos',

	// Titles
	'admin:plugin_settings:simplekaltura' => 'Simple Kaltura Settings',

	'videos:add' => 'Upload New Video',
	'simplekaltura:edit' => 'Edit Video',
	'simplekaltura:all' => 'All Videos',
	'simplekaltura:friends' => '%s\'s Friends\' Videos',

	// Labels
	'simplekaltura:label:subtypes_settings_submit' => 'Submit Subtype Settings',
	'simplekaltura:label:title' => 'Title',
	'simplekaltura:label:description' => 'Description',
	'simplekaltura:label:deleteconfirm' => 'Are you sure you want to delete this Video?',
	'simplekaltura:label:submitted_by' => 'Submitted by %s',
	'simplekaltura:label:leaveacomment' => 'Leave a Comment / ',
	'simplekaltura:label:new' => 'Upload New Video',
	'simplekaltura:label:selectvideo' => 'Select a video..',
	'simplekaltura:label:deleteconfirm' => 'Are you sure you want to delete this video?',
	'simplekaltura:label:posted_by' => 'Posted by %s',
	'simplekaltura:label:friends' => 'Friends',
	'simplekaltura:label:vidlength' => 'Length: %s ',
	'simplekaltura:label:vidplays' => 'Plays: %s ',
	'simplekaltura:label:unavailable' => 'Unavailable',
	'simplekaltura:label:default' => 'Default',
	'simplekaltura:label:download' => 'Download Video',
	'simplekaltura:label:groupvideos' => 'Group ' . strtolower($entity_title),
	'simplekaltura:label:mostplayed' => 'Most Played',
	'simplekaltura:label:embedcode' => 'Embed Code',
	'simplekaltura:label:viewonsite' => 'View this video on %s',
	'simplekaltura:label:copypaste' => 'Copy and paste the code below:',
	'simplekaltura:label:viewvideo' => 'View Original Video',
	'simplekaltura:label:currentthumbnail' => 'Current Thumbnail',
	'simplekaltura:label:selectthumbnail' => 'Select Thumbnail',
	'simplekaltura:label:editvideo' => 'Edit Video: "%s"',
	'simplekaltura:label:uploadingdialogtitle' => 'Uploading...',
	'simplekaltura:label:makefeatured' => 'Make Featured',
	'simplekaltura:label:makeunfeatured' => 'Unfeature',
	'simplekaltura:label:featured' => 'Featured Videos',
	'simplekaltura:label:no' => 'No',
	'simplekaltura:label:yes' => 'Yes',
	'simplekaltura:label:flash' => 'Flash',
	'simplekaltura:label:iframe' => 'iframe',

	// Admin
	'simplekaltura:admin:kalturaconfig' => 'Kaltura Configuration',
	'simplekaltura:admin:playerconfig' => 'Player Configuration',
	'simplekaltura:admin:thumbconfig' => 'Thumbnail Configuration',
	'simplekaltura:admin:popupconfig' => 'Popup Configuration',
	'simplekaltura:admin:uploadconfig' => 'Upload Configuration',
	'simplekaltura:admin:siteconfig' => 'General Site Configuration',
	'simplekaltura:admin:admintags' => 'Admin Tag (Used to identify this particular Elgg site\'s videos)',
	'simplekaltura:admin:partnerid' => 'Partner ID',
	'simplekaltura:admin:emailaccount' => 'Email Address',
	'simplekaltura:admin:passwordaccount' => 'Password',
	'simplekaltura:admin:customplayerid' => 'Custom Player ID',
	'simplekaltura:admin:embedplayerid' => 'Embed Player ID',
	'simplekaltura:admin:playerheight' => 'Player Height',
	'simplekaltura:admin:playerwidth' => 'Player Width',
	'simplekaltura:admin:embedplayerheight' => 'Embed Player Height',
	'simplekaltura:admin:embedplayerwidth' => 'Embed Player Width',
	'simplekaltura:admin:thumbnailurl' => 'Thumbnail URL (See: http://corp.kaltura.com/wiki/index.php/KalturaAPI:thumbnail)',
	'simplekaltura:admin:smallthumb' => 'Small Thumbnail',
	'simplekaltura:admin:mediumthumb' => 'Medium Thumbnail',
	'simplekaltura:admin:largethumb' => 'Large Thumbnail',
	'simplekaltura:admin:width' => 'Width',
	'simplekaltura:admin:height' => 'Height',
	'simplekaltura:admin:popupheight' => 'Popup Height',
	'simplekaltura:admin:popupwidth' => 'Popup Width',
	'simplekaltura:admin:uploadmax' => 'Maximum Upload Size (MB)',
	'simplekaltura:admin:entitytitle' => 'Entity title (includes main menu item)',
	'simplekaltura:admin:uiconfid' => 'KSU uiConfId',
	'simplekaltura:admin:uiconfiddesc' => 'See: http://knowledge.kaltura.com/kaltura-simple-uploader-ksu-website-integration-guide',
	'simplekaltura:admin:allownonpublicembed' => 'Allow non-public video embeds',
	'simplekaltura:admin:additional_settings' => 'Additional Settings',
	'simplekaltura:admin:admin_only_download' => "Restrict video downloads to admin only?",

	// River
	'river:create:object:simplekaltura_video' => '%s uploaded a Video titled %s',
	'river:comment:object:simplekaltura_video' => '%s commented on a Video titled %s',

	// Notifications
	'simplekaltura:notification:subject' => 'New Video',
	'simplekaltura:notification:summary' => 'New Video: %s',
	'simplekaltura:notification:body' => "%s posted a new Video titled: %s\n\n%s\n\nTo watch the video click here:\n%s
",

	// Messages
	'simplekaltura:success:save' => 'Successfully saved Video',
	'simplekaltura:success:delete' => 'Video successfully deleted',
	'simplekaltura:success:update' => 'Video successfully updated',
	'simplekaltura:success:featuredon' => '%s is now a featured video.',
	'simplekaltura:success:featuredoff' => '%s has been removed from featured videos.',
	'simplekaltura:error:save' => 'Error saving Video',
	'simplekaltura:error:delete' => 'There was an error deleting the Video',
	'simplekaltura:error:notfound' => 'Video not found',
	'simplekaltura:error:apierror' => 'Kaltura API Error',
	'simplekaltura:error:titlerequired' => 'Title is required',
	'simplekaltura:error:nonpublic' => 'Video must be public!',
	'simplekaltura:error:invalidsecond' => 'Invalid value for thumbnail second',
	'simplekaltura:error:invalidsecondduration' => 'Value must be less than video duration (%s seconds)',
	'simplekaltura:error:pluginnotconfigured' => 'Simplekaltura is not properly configured!<br />One or more required settings is not set.<br />Check the <a href="' . elgg_get_site_url() .'/admin/plugin_settings/simplekaltura">plugin settings</a> page.',
	'simplekaltura:error:filetoolarge' => 'Selected file exceeds maximum upload size (%s MB)',
	'simplekaltura:error:featured' => 'Invalid video.',
	'simplekaltura:notconverted' => 'Warning: Video has not yet finished converting. Thumbnail selection is unavailable while conversion in progress.',

	// Other content
	'simplekaltura:no_content' => 'No content',
	'groups:enablesimplekaltura' => 'Enable group videos',
	'simplekaltura:migrate:message' => "Videos from another Kaltura plugin can be migrated to be used by SimpleKaltura.  Click here for more info: %s",
	'admin:simplekaltura' => "Simple Kaltura",
	'admin:simplekaltura:migrate' => "Content Migration",
	'simplekaltura:migration:possible' => "There is/are currently %s video(s) that can be migrated to SimpleKaltura.  Doing this will change the content to types that can be used by this plugin, they will no longer be usable in pages provided by the original plugin.  All information (owner, time created, title, description, etc) will be retained.  Click the button below to initiate the migration.  Note - for large amounts of content this may take a while.",
	'simplekaltura:migration:notpossible' => "There are no videos available for migration.",
	'simplekaltura:migrate' => "Migrate",
	'simplekaltura:migrate:inprogress' => "A previous migration is already in progress.",
	'simplekaltura:migration:complete' => 'Kaltura videos have been migrated',
);