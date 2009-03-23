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
	return false;
}