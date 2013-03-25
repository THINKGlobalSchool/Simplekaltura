# Simplekaltura

Simple Kaltura video uploading and sharing for Elgg.

## Requirements

This plugin is for the latest [Elgg 1.8](http://elgg.org/) release, currently tested and working with Elgg 1.8.14

To use this plugin, you need to have a hosted Kaltura account. See: [http://corp.kaltura.com/](http://corp.kaltura.com/)

## Installation

Download/clone this repository into your Elgg mod directory:

ie:

	cd {my elgg folder}/mod/

	git clone git://github.com/THINKGlobalSchool/Simplekaltura.git simplekaltura

## Setup

You'll need to configure a number of plugin settings to get started

#### Required Plugin Settings

* **Admin Tag**

	*Admin tags identify which Kaltura videos on your account will be polled for updates. This allows you to have multiple Elgg sites use a single hosted Kaltura account.*

* **Partner ID**

	*The Partner ID provided by Kaltura during the registration process*

* **Email Address**

	*This is the email address you use to log into the KMC. Required to connect to the Kaltura API*

* **Password**

	*Password associated with the above email address. Also required by the Kaltura API*

#### Other Plugin Settings

All other settings will be initted with default values.

* **Entity title**

	*Change this to control the text displayed in the Site Menu as well as in the item:object:simplekaltura language string*

* **Custom Player ID / Player Width / Player Height**

	*Kaltura allows you create custom players, enter your Player ID and desired dimensions*	

* **Custom Embed Player ID / Embed Player Width / Embed Player Height**

	*A seperate custom player ID for embedded videos*

* **Thumbnail URL** 

	*Customize video thumbnails using the [Kaltura Thumbnail API](http://corp.kaltura.com/wiki/index.php/KalturaAPI:thumbnail)*

* **Thumbnail Dimensions**

	*Specify the height and width of the small, medium and large thumbnails*

* **Popup Width/Height**

	*Specify the height and width of the video displayed in popups*

* **Maxium Upload Size**

	*Specify the maximum size of a video that can be uploaded*

#### Elgg Setup

This plugin makes use of Elgg's cron features to periodically update local videos with information (ie: play counts) from Kaltura. 

You'll need to create a crontab file as described in [Elgg's cron documentation](http://docs.elgg.org/wiki/Cron)

#### Kaltura Player Info

With a Kaltura hosted account you can create and customize your own video players through the [KMC](http://www.kaltura.org/project/Kaltura_Management_Console) 

This plugin makes use of a custom embed button that you need to configure in the KMC Studio. For more information on custom buttons, see the following knowledge article: 

[http://knowledge.kaltura.com/javascript-api-kaltura-media-players#CreatingCallstoActionCustomButtons](http://knowledge.kaltura.com/javascript-api-kaltura-media-players#CreatingCallstoActionCustomButtons)