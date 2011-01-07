<?php
/**
 * Simple Kaltura css
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
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

object#simplekaltura-uploader, embed.simplekaltura-uploader {
	position:absolute; top:0; left:0; z-index:999;
}

.z-negative {
	z-index: -999 !important;
}
/* END KSU WIDGET */

.simplekaltura-selected-file {
	font-size: 90% !important;
	color: #333 !important;
	display: block;
}

.simplekaltura-file-size {
	font-size: 85% !important;
	font-style: italic;
	color: #555 !important;
	margin-left: 5px;
}

.simplekaltura-error {
	font-size: 11px;
	color: red;
	font-weight: bold;
}

.simplekaltura_video_description {
	margin-top: 10px;
	margin-bottom: 10px;
	padding-bottom: 10px;
	border-bottom: 1px solid #bbbbbb;
}

.simplekaltura_video_container {
	height: 100%;
	margin-left: auto;
	margin-right: auto;
	width: <?php echo get_plugin_setting('kaltura_player_width', 'simplekaltura'); ?>px;
}

.simplekaltura_video_footer {
	padding-bottom: 10px;
	border-bottom: 1px solid #bbbbbb;
}

.simplekaltura_video_container object {
	border: 3px solid #aaaaaa;
	padding: 0;
	-webkit-box-shadow:0px 0px 6px #333333;
	-moz-box-shadow:0px 0px 6px #333333;
	box-shadow:0px 0px 6px #000000;
}

.kaltura-meta {
	font-size: 85%;
	color: #333333;
	margin-bottom: 2px;
}

/* TWEAKS */
.simplekaltura .entity_listing .icon {
}

.simplekaltura .entity_listing .info {
	margin-left: 135px;
}



/* CUSTOM DIALOG */

#simplekaltura-upload-dialog  {
	padding: 10px;
	border: 8px solid #555555;
	background: #ffffff;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
}

#simplekaltura-upload-dialog p {
	margin-top: 5px;
	margin-bottom: 5px;
	font-weight: bold;
	color: #333333;
	font-size: 110%;
}


.ui-progressbar { 
	height: 2em; 
	text-align: left; 
	border: 2px solid #000; 
}

.ui-progressbar .ui-progressbar-value {
	margin: 0;
	height:100%; 
	background: #9D1520; 
}