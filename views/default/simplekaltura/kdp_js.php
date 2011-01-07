<?php
/**
 * Simple Kaltura KDP Widget JS
 * 
 * @package Simplekaltura
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 */
?>
<script type="text/javascript" src="<?php echo elgg_get_site_url() . 'mod/simplekaltura/vendors/swfobject.js'; ?>"></script>
<script type="text/javascript" src="<?php echo elgg_get_site_url() . 'mod/simplekaltura/vendors/html5.js'; ?>"></script> <!-- For HTML5 (Hopefully) -->
<script type="text/javascript">
	var notificationslist = ['startUp',
							'durationChange',
							'initiatApp',
							'changeMedia',
							'cleanMedia',
							'skinLoaded',
							'skinLoadFailed',
							'entryReady',
							'entryFailed',
							'sourceReady',
							'loadMedia',
							'mediaLoading',
							'mediaReady',
							'mediaUnloaded',
							'mediaLoadError',
							'mediaError',
							'rootResize',
							'mediaViewableChange',
							'pre1start',
							'post1start',
							'doPause',
							'doPlay',
							'doPlayEntry',
							'doStop',
							'doSeek',
							'doIntelligentSeek',
							'doSwitch',
							'kdpReady',
							'kdpEmpty',
							'layoutReady',
							'playerStateChange',
							'playerReady',
							'playerContainerReady',
							'playerPaused',
							'playerPlayed',
							'playerSeekStart',
							'playerSeekEnd',
							'playerPlayEnd',
							'playerUpdatePlayhead',
							'playerDimensionChange',
							'openFullScreen',
							'closeFullScreen',
							'changeVolume',
							'volumeChanged',
							'enableGui',
							'fastForward',
							'stopFastForward',
							'bytesDownloadedChange',
							'bytesTotalChange',
							'bufferProgress',
							'bufferChange',
							'playerDownloadComplete',
							'endEntrySession',
							'endPreSession',
							'endPostSession',
							'durationChange',
							'hasCloseFullScreen',
							'hasOpenedFullScreen',
							'switchingChange',
							'scrubberDragStart',
							'scrubberDragEnd',
							'alert',
							'showUiElement',
							'cancelAlerts',
							'enableAlerts',
							'freePreviewEnd'];
	
	function jsCallbackReady () {
		$('#kdp3').get(0).addJsListener("volumeChanged", "volumeChangedHandler");
		$('#kdp3').get(0).addJsListener("playerStateChange", "playerStateChangeHandler");
		$('#kdp3').get(0).addJsListener("durationChange", "durationChangeHandler");
		$('#kdp3').get(0).addJsListener("playerUpdatePlayhead", "playerUpdatePlayheadHandler");
		$('#kdp3').get(0).addJsListener("bytesTotalChange", "bytesTotalChangeHandler");
		$('#kdp3').get(0).addJsListener("bytesDownloadedChange", "bytesDownloadedChangeHandler");
		
		for (var i=0; i < notificationslist.length; ++i) {
			if (notificationslist[i] != 'playerUpdatePlayhead') {
				$('#kdp3').get(0).addJsListener(notificationslist[i], notificationslist[i]+"Handler2");
				codeBlock = 'function '+notificationslist[i]+'Handler2(data, id) {if ($("#stopListeners").attr("checked") == false){$("#notifications").text($("#notifications").text() + "'+notificationslist[i]+': ");printobj(data);}}';
				appendScript (codeBlock);
			}
		}
	}
	
	function printobj (obj) {
		x = '';
		if (typeof(obj) == 'object') {
			for (var key in obj) {
				x += key + " => " + obj[key] + ", ";
			}
		} else {
			x = obj;
		}
		var oldscroll = $("#notifications").scrollTop();
		$("#notifications").text($("#notifications").text() + x + '\n');
		if ($('#autoscroll').attr('checked') == true)
			$("#notifications").scrollTop($("#notifications").get(0).scrollHeight); 
		else
			$("#notifications").scrollTop(oldscroll);
	}
	
	function appendScript (code) {
		x = '<' + 'script type="text/javascript" >' + code +'</' + 'script>';
		$('body').append(x);
	}
	
	function statisticsHandler (data, id) {
		$('#notifications').text($('#notifications').text() + '\n' + data);
	}
	
	function bytesTotalChangeHandler (data, id) {
		$('#bytestotal').text(data.newValue);
	}
	
	function bytesDownloadedChangeHandler (data, id) {
		$('#bytesloaded').text(data.newValue);
	}
	
	var newVolume = 0;
	var oldVolume = 1;
	function volumeChangedHandler (data, id) {
		$('#volumeLevel').text(data.newVolume);
	}
	
	function playerStateChangeHandler (data, id) {
		$('#playerstate').text(data);
	}
	
	function durationChangeHandler (data, id) {
		$('#videoduration').text(data.newValue);
	}
	
	function playerUpdatePlayheadHandler(data, id) {
		if ($('#listenToUpdate').attr('checked') == true) {
			$("#notifications").text($("#notifications").text() + "playerUpdatePlayhead: ");
			printobj(data);
		}
		$('#videotime').text(data);
	}
</script>