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

var progress_id = new Array();
var notimeout = new Array();

function transload() {
	var link = $('DownloadLink').value;
	var id = randomString();
	if (!link || link == 'Enter http:// or ftp:// link you want to transload') {
		alert('You didn\'t enter a download link!');
	} else {
		// Initiate Ajax call for the download link
		var dllink = 'index.php?mod=transload&id='+id+'&link='+link.URLEncode();
		new Ajax.Request(dllink, {
			method: 'get',
			onCreate: function () {
				// Create the download link row
				var tableElement = $('dl_table');
				// Always insert in last row
				var newRow = tableElement.insertRow(-1);
				newRow.insertCell(0).innerHTML = link;
				newRow.insertCell(1).innerHTML = 'Starting...';
				newRow.insertCell(2).innerHTML = '&nbsp;';
				newRow.insertCell(3).innerHTML = '&nbsp;';
				newRow.insertCell(4).innerHTML = '<span id="pg_'+id+'">[ Loading Progress Bar ]</span>';
				newRow.insertCell(5).innerHTML = '&nbsp;';
				newRow.id = 'row_'+id;
				// Create the progressbar
				progress_id[id] = new JS_BRAMUS.jsProgressBar(
					$('pg_'+id),
					0,
					{

						barImage	: Array(
							'images/bramus/percentImage_back4.png',
							'images/bramus/percentImage_back3.png',
							'images/bramus/percentImage_back2.png',
							'images/bramus/percentImage_back1.png'
						)
					}
				);
				// Call a function to always repeatedly check for updates on the download progress
				notimeout[id] = false;
				setTimeout('checkProgress("'+id+'")',3000);
				$('DownloadLink').value = 'Enter http:// or ftp:// link you want to transload';
			}
		});
	}
}

function checkProgress(id) {
	var link = 'index.php?mod=getProgress&id='+id;
	new Ajax.Request(link, {
		method: 'get',
		onSuccess: function (xmlHttp) {
			var data = xmlHttp.responseText.evalJSON(true);
			$('row_'+id).cells[1].innerHTML = data.Status;
			$('row_'+id).cells[2].innerHTML = data.Size;
			$('row_'+id).cells[3].innerHTML = data.Received;
			progress_id[id].setPercentage(data.Percentage);
			$('row_'+id).cells[5].innerHTML = data.Speed;
			if (data.Status == 'Finished') {
				notimeout[id] = true;
			}
		}
	});
	if (!notimeout[id]) {
		setTimeout('checkProgress("'+id+'")',1000);
	}
}

function updateFilenameID() {
	var els = document.getElementsByClassName('editText');
	for (var i = 0; i < els.length; i++) {
		els[i].id = els[i].innerHTML;
	}
}

String.prototype.URLEncode = function URLEncode( )
{
 var SAFECHARS = "0123456789" +     // Numeric
     "ABCDEFGHIJKLMNOPQRSTUVWXYZ" + // Alphabetic
     "abcdefghijklmnopqrstuvwxyz" +
     "-_.!~*'()";     // RFC2396 Mark characters
 var HEX = "0123456789ABCDEF";
 var plaintext = this;
 var encoded = "";
 for (var i = 0; i < plaintext.length; i++ ) {
  var ch = plaintext.charAt(i);
     if (ch == " ") {
      encoded += "+";    // x-www-urlencoded, rather than %20
  } else if (SAFECHARS.indexOf(ch) != -1) {
      encoded += ch;
  } else {
      var charCode = ch.charCodeAt(0);
   if (charCode > 255) {
       alert( "Unicode Character '"
                        + ch
                        + "' cannot be encoded using standard URL encoding.\n" +
              "(URL encoding only supports 8-bit characters.)\n" +
        "A space (+) will be substituted." );
    encoded += "+";
   } else {
    encoded += "%";
    encoded += HEX.charAt((charCode >> 4) & 0xF);
    encoded += HEX.charAt(charCode & 0xF);
   }
  }
 } // for
 return encoded;
};

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
