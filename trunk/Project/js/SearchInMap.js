// JavaScript Document

function searchInMap() {
	var keyWord = $('#searchForm #query').val();
	var layers = $('#searchForm #searchLayer').val();
	
	OpenLayers.loadURL('lib/search_map.php?q=' + keyWord + '&layers=' + layers, null, null, getResult, null);
	
	return false;
}

function getResult(response) {
	// Chuyen JSON tu Text ve dang JavaScript Object
	var jsonObj = eval('(' + response.responseText + ')');
	
	if (jsonObj.results.length != 0) {
		showSearchResult.removeFeatures(showSearchResult.features);
		
		// parse the features
		for (var i = 0; i < jsonObj.results.length; i++) {
			parseWKT(jsonObj.results[i].geom);
			
			// TODO: Insert text describe for each feature in left side bar
		}
		
		// TODO: Fix move to a Polyline object
		map.moveTo(new OpenLayers.LonLat(showSearchResult.features[0].geometry.x, showSearchResult.features[0].geometry.y), 4);
	}
}

function parseWKT(wkt) {
	var parser = new OpenLayers.Format.WKT();
	// TODO: Fix call read two times
	var geometry = parser.read(wkt)
	var features = parser.read(wkt);
	if (features) {
		showSearchResult.addFeatures(features);
	} else {
		alert("wrong");
		element.value = 'Bad WKT';
	}
}