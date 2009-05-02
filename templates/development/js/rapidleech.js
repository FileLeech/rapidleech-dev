function select(opt) {
	var i;
	var formElements = document.getElementsByName('files[]');
	switch (opt) {
		case 'all':
			for (i=0;i<formElements.length;i++) {
				if (formElements[i].type == "checkbox") {
					formElements[i].checked = true;
				}
			}
			document.getElementById('checkboxAll').checked = true;
			break;
		case 'none':
			for (i=0;i<formElements.length;i++) {
				if (formElements[i].type == "checkbox") {
					formElements[i].checked = false;
				}
			}
			document.getElementById('checkboxAll').checked = false;
			break;
		case 'invert':
			for (i=0;i<formElements.length;i++) {
				if (formElements[i].type == "checkbox") {
					if (formElements[i].checked == true)
						formElements[i].checked = false;
					else
						formElements[i].checked = true;
				}
			}
			if (document.getElementById('checkboxAll').checked == true)
				document.getElementById('checkboxAll').checked = false;
			break;
	}
}

function action(act,filename) {
	switch (act) {
		case 'delete':
			var ans = confirm("Are you sure you want to delete "+filename+"?");
			if (ans) {
				$.ajax({
					type: "GET",
					url: 'index.php',
					data: {mod:"ajaxaction",action:"delete",file:filename,path:currentPath},
					complete: function() {
						updatefTable();
					}
				})
			}
			break;
	}
}


function createDir() {
	var folder = prompt("What do you want your folder's name to be?", "Untitled");
	if (!folder) {
		alert("You didn't enter a comment name!");
		return false;
	}
	$.ajax({
		type: "GET",
		url: 'index.php',
		data: {mod:"createdir", folder:folder, path:currentPath},
		success: function (data) {
			if (data != 'success') {
				alert(data);
				return false;
			} else {
				updatefTable();
			}
		}
	})
}

var progress_id = new Array();
var notimeout = new Array();

function transload() {
	//var link = $('DownloadLink').value;
	var link = jQuery('#DownloadLink').val();
	var id = randomString();
	if (!link || link == 'Enter http:// or ftp:// link you want to transload') {
		alert('You didn\'t enter a download link!');
	} else {
		// Initiate Ajax call for the download link
		//var dllink = 'index.php?mod=transload&id='+id+'&link='+link.URLEncode();
		jQuery.ajax({
			type: "GET",
			url: 'index.php',
			data: {mod:"transload",id:id,link:link, path:currentPath},
			beforeSend: function() {
				// Insert a new row
				jQuery('#dl_table').append(jQuery('#dl_table tr:last').clone());
				jQuery('#dl_table tr:last').html('<td>'+link+'</td>'+
						'<td>Starting...</td>'+
						'<td>&nbsp;</td>'+
						'<td>&nbsp;</td>'+
						'<td><div id="pg_'+id+'" style="width: 150px; height: 20px; margin: 0 auto;"></div></td>'+
						'<td>&nbsp;</td>');
				jQuery('#dl_table tr:last').attr('id','row_'+id);
				jQuery(function() {
					jQuery('#pg_'+id).progressbar({
						value: 0
					})
				})
				notimeout[id] = false;
				setTimeout('checkProgress("'+id+'")',3000);
				jQuery('#DownloadLink').val('Enter http:// or ftp:// link you want to transload');
			}
		})
	}
}

function checkProgress(id) {
	var link = 'index.php?mod=getProgress&id='+id;
	jQuery.getJSON(
			link,
			function(data) {
				jQuery('#row_'+id+' td:eq(1)').html(data.Status);
				jQuery('#row_'+id+' td:eq(2)').html(data.Size);
				jQuery('#row_'+id+' td:eq(3)').html(data.Received);
				jQuery('#pg_'+id).progressbar('option','value',data.Percentage)
				jQuery('#row_'+id+' td:eq(5)').html(data.Speed);
				if (data.Status != 'Finished') {
					setTimeout('checkProgress("'+id+'")',1000);
				} else {
					// Update table data
					updatefTable();
				}
			})
	/*jQuery.ajax({
		type: 'GET',
		url: link,
		complete: function(xmlHttp) {
			var data = xmlHttp.responseText.evalJSON(true);
			jQuery('#row_'+id+' td:eq(1)').html(data.Status);
			jQuery('#row_'+id+' td:eq(2)').html(data.Size);
			jQuery('#row_'+id+' td:eq(3)').html(data.Received);
			jQuery('#pg_'+id).progressbar('option','value',data.Percentage)
			jQuery('#row_'+id+' td:eq(5)').html(data.Speed);
			if (data.Status != 'Finished') {
				setTimeout('checkProgress("'+id+'")',1000);
			}
		}
	})*/
}

function updatefTable() {
	fTable.fnClearTable(0);
	// Retrieve data
	jQuery.getJSON(
			'index.php',
			{mod:'refreshFileTable',nav:currentPath}, 
			function(data) {
				for ( var i in data['files'] ) {
					fTable.fnAddData(data['files'][i]);
				}
				fTable.fnDraw();
				editbox_init();
				$('#fTableItems').html(data['info']['item']);
				$('#fTableSize').html(data['info']['size']);
			}
			);
}

function updateFilenameID() {
	var els = document.getElementsByClassName('editText');
	for (var i = 0; i < els.length; i++) {
		els[i].id = els[i].innerHTML;
	}
}


function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 16;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}
