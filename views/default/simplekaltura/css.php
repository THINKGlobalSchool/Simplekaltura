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

/* GALLERY */
.simplekaltura-video-gallery-item {
	padding: 5px 10px 10px;
	text-align: center;
}

.simplekaltura-video-gallery-icon {
	margin-left: auto;
	margin-right: auto;
	width: 126px;
	margin-bottom: 5px;
	margin-top: 3px;
}

.simplekaltura-video-gallery-icon img {
	border: 3px solid #FFFFFF;
	box-shadow: 0px 0px 3px #666666;
}