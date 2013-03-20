<?php
/**
 * Simple Kaltura css
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2013
 * @link http://www.thinkglobalschool.com/
 *
 */
?>

/* KSU WIDGET */
#simplekaltura-flashContainer{ 
	position:relative; 
}

#simplekaltura-flashContainer span{ 
	color:#333; font-size:16px; 
}

#simplekaltura-flashContainer span.pls {
	color:#666; font-size: 14px;
}

object#simplekaltura-uploader, embed.simplekaltura-uploader {
	position:absolute; top:0; left:0; z-index:999;
}

.z-negative {
	z-index: -999 !important;
}
/* END KSU WIDGET */


/* GALLERY */
.simplekaltura-video-gallery-item {
	padding: 5px 10px 10px;
	text-align: center;
}

img.simplekaltura-video-gallery-icon {
	border: 3px solid #FFFFFF;
	box-shadow: 0px 0px 3px #666666;
	margin-left: auto;
	margin-right: auto;
	width: 126px;
	margin-bottom: 5px;
}

.simplekaltura-video-gallery-item img.simplekaltura-video-gallery-icon {
	margin-top: 3px;
}

/* OBJECT VIEW */
.elgg-kaltura-player {
	border-top: 1px solid #CCC;
	border-bottom: 1px solid #CCC;
	padding-top: 10px;
	padding-bottom: 10px;
	margin-top: 15px;
}

/* POPUP DIV */
.elgg-kaltura-popup {
	height: 450px;
	width: 600px;
	overflow: hidden;
}

/* Entity Menu Icons */
.elgg-menu-item-download-video {
	background-position: 0 -252px !important;
}

/* EMBED CODE */
.elgg-kaltura-embed-code {
	margin-top: 5px;
	font-size: 12px;
	height: 145px;
}

.simplekaltura-spotcontent-embed {
	font-size: 11px;
}

/** THUMBNAILS INPUT **/
.simplekaltura-edit-thumbnails {
	text-align: center;
	margin-top: 10px;
	margin-bottom: 10px;
	overflow: hidden;
	-moz-border-radius: 5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	border: 2px solid #CCCCCC;
	background: #EEEEEE;
}

.simplekaltura-edit-thumbnail {
	float: left;
	margin: 5px;
	padding: 15px;
}

.simplekaltura-edit-thumbnail-wrapper {
	width: <?php echo elgg_get_plugin_setting('kaltura_largethumb_width', 'simplekaltura');?>px;
	height: <?php echo elgg_get_plugin_setting('kaltura_largethumb_height', 'simplekaltura');?>px;
	-moz-box-shadow: 0 1px 5px #333333;
	-webkit-box-shadow: 0 1px 5px #333333;
	box-shadow: 0 1px 5px #333333;
	padding: 3px;
	margin-bottom: 5px;
}

.simplekaltura-edit-thumbnail .ui-slider-handle {
	background: none repeat scroll 0 0 #CCCCCC !important;
	border: 1px solid #AAAAAA !important;
}

.simplekaltura-edit-thumbnail .ui-slider-handle.ui-state-active {
	background: none repeat scroll 0 0 #AAAAAA !important;
	border: 1px solid #999999 !important;
}
