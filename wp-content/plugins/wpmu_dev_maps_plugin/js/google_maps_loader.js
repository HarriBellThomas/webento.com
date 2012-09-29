/**
 * Asynchrounously load Google Maps API. 
 */


/**
 * Global API loaded flag.
 */
var _agmMapIsLoaded = false;


/**
 * Callback - triggers loaded flag setting. 
 */
function agmInitialize () {
	_agmMapIsLoaded = true;
}

/**
 * Handles the actual loading of Google Maps API.
 */
function loadGoogleMaps () {
	var protocol = '';
	var language = '';
	try { protocol = document.location.protocol; } catch (e) { protocol = 'http:'; }
	if (typeof(_agmLanguage) != "undefined") {
		try { language = '&language=' + _agmLanguage; } catch (e) { language = ''; }
	}
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = protocol + "//maps.google.com/maps/api/js?v=3&libraries=panoramio&sensor=false" + language + "&callback=agmInitialize";
	document.body.appendChild(script);
}

jQuery(window).load(loadGoogleMaps);
