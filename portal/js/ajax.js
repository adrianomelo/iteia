var Ajax = false;

function AjaxRequest() {
	Ajax = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		Ajax = new XMLHttpRequest();
	} else if (window.ActiveXObject) { // IE
		try {
			Ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				Ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
}

function removeAllChildren(obj) {
	if (obj.hasChildNodes()) {
		while (obj.childNodes.length >= 1)
			obj.removeChild(obj.firstChild);
	}
}