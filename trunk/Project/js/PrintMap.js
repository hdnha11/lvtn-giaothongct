// JavaScript Document
// this assumes that the Map object is a JavaScript variable named "map"
var print_wait_win = null;

function printMap() {
	
    //-- post a wait message
    //alert("Vui lòng chờ giây lát...");

    // go through all layers, and collect a list of objects
    // each object is a tile's URL and the tile's pixel location relative to the viewport
    var size  = map.getSize();
    var tiles = [];
    for (layername in map.layers) {
		
        // if the layer isn't visible at this range, or is turned off, skip it
        var layer = map.layers[layername];
        if (!layer.getVisibility()) continue;
        if (!layer.calculateInRange()) continue;
		
        // iterate through their grid's tiles, collecting each tile's extent and pixel location at this moment
        for (tilerow in layer.grid) {
            for (tilei in layer.grid[tilerow]) {
                var tile     = layer.grid[tilerow][tilei];
                var url      = layer.getURL(tile.bounds);
                var position = tile.position;
                var opacity  = layer.opacity ? parseInt(100 * layer.opacity) : 100;
                tiles[tiles.length] = {url: url, x: position.x, y: position.y, opacity: opacity};
            }
        }
    }

    // hand off the list to our server-side script, which will do the heavy lifting
    var tiles_json = JSON.stringify(tiles);
    var printparams = 'width=' + size.w + '&height=' + size.h + '&tiles=' + escape(tiles_json);
    OpenLayers.Request.POST({
		url: 'lib/ex_map_to_img.php',
		data: OpenLayers.Util.getParameterString({width: size.w, height: size.h, tiles: tiles_json}),
		headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		callback: function(request) {
			window.open('print_preview.php?imgUrl=' + request.responseText);
		}
	});
}

$(document).ready(function() {
    $("#navigation .printmap").click(function() {
		printMap();
	});
});