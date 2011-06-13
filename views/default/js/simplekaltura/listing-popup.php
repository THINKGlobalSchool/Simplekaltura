var dlg = '';

/* Creates a popup out of a dialog with given id */
function video_listing_create_popup_with_id(id, width) {
	if (!width) {
		width = 'auto';
	}
	
	dlg = $("#" + id).dialog({
						autoOpen: false,
						width: width, 
						height: 'auto',
						modal: true,
						open: function(event, ui) { 
							console.log('opening');
						//	$(".ui-dialog-titlebar-close").hide(); 	
						},
						buttons: {
							"X": function() { 
								$(this).dialog("close"); 
							} 
	}});
}

/* Loads a popup with given url */
function video_listing_load_popup_by_id(id, load_url) {	
	video_listing_create_popup_with_id(id, 500);
	$("#" + id).dialog("open");
	$("#" + id).load(load_url);
}
