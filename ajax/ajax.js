var listeAjaxAPoster = new Array();
var postEnCours = false;
var divToDisplayCourant;
var redirectCourant;


function getXMLHttpRequest() {
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		} else {
			xhr = new XMLHttpRequest(); 
		}
	} else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	
	return xhr;
}

function ajaxPost(phpLink, parameters, divToDisplay, redirect) {
	document.getElementById(divToDisplay).innerHTML = "<DIV class=\"messageInfo\"><IMG src=\"images/loading.gif\"><DIV>";
	
	if (postEnCours) {
		listeAjaxAPoster.push({'phpLink' : phpLink, 
							   'parameters' : parameters, 
							   'divToDisplay' : divToDisplay, 
							   'redirect' : redirect});
	} else {
		postEnCours = true;
		divToDisplayCourant = divToDisplay;
		redirectCourant = redirect;
		
		xhr = getXMLHttpRequest();
		xhr.open("POST", phpLink, true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(parameters);
		
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status == 200 || xhr.status == 0) {
					if (redirectCourant && xhr.responseText.indexOf("#REDIRECT#") >= 0) {
						location.replace(redirectCourant);
					} else {
						document.getElementById(divToDisplayCourant).innerHTML = xhr.responseText;
						document.getElementById(divToDisplayCourant).value = xhr.responseText;
					}
					
					scripts = document.getElementById(divToDisplayCourant).getElementsByTagName("SCRIPT");
					for (var i = 0; i < scripts.length; i++) {
						eval(scripts[i].innerHTML);
					}
				}
				
				if (xhr.status != 200 && xhr.status != 0) {
					document.getElementById(divToDisplayCourant).innerHTML = "<DIV class=\"messageErreur\">Une erreur est survenue lors de l'appel AJAX<DIV>";
				}
				
				if (listeAjaxAPoster.length > 0) {
					var phpLink = listeAjaxAPoster[0]['phpLink'];
					var parameters = listeAjaxAPoster[0]['parameters'];
					var divToDisplay = listeAjaxAPoster[0]['divToDisplay'];
					var redirect = listeAjaxAPoster[0]['redirect'];
					listeAjaxAPoster.shift();
					
					postEnCours = false;
					ajaxPost(phpLink, parameters, divToDisplay, redirect);
				} else {
					postEnCours = false;
				}
			}
		}
	}
}