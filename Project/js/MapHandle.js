// JavaScript Document
// Ban do
var map;

// Layer hien thi ket qua tim kiem
var showSearchResult;

// Cong cu do khoang cach, dien tich
var measureControls;

// pink tile avoidance
OpenLayers.IMAGE_RELOAD_ATTEMPTS = 5;

// make OL compute scale according to WMS spec
OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

// PHP Proxy dung cho viec goi AJAX Cross Server
OpenLayers.ProxyHost = 'lib/geoproxy.php?url=';

// Su dung tieng Viet
OpenLayers.Lang.setCode('vi');

// Ham khoi tao ban do
function init() {
	
	// Dinh dang anh khi xuat ra trinh duyet
	format = 'image/png';
	
	// Khung bao lon nhat cua ban do
	var bounds = new OpenLayers.Bounds(
		105.225331, 9.920382,
		105.841824, 10.326206
	);
	
	// Cac bien dung de zoom giua ban do khi khoi tao
	var centerPoint = bounds.centerLonLat;
	var zoom = 2;
	
	// Cac tuy chon khi tao ban do
	var options = {
		//controls: [],
		maxExtent: bounds,
		maxResolution: 0.00240817578125,
		projection: "EPSG:4326",
		units: 'degrees'
	};
	
	// Map Constructor
	map = new OpenLayers.Map('map', options);
	
	// Background layer Constructor
	var background = new OpenLayers.Layer.WMS(
		"TP. Cần Thơ",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'cantho_base_map',
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: true,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Tinh_lo layer Constructor
	var tinhLo = new OpenLayers.Layer.WMS(
		"Tỉnh lộ",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'luanvan:tinh_lo_polyline',
			transparent: true,
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: false,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Quoc_lo layer Constructor
	var quocLo = new OpenLayers.Layer.WMS(
		"Quốc lộ",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'luanvan:quoc_lo_polyline',
			transparent: true,
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: false,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Cau layer Constructor
	var cau = new OpenLayers.Layer.WMS(
		"Cầu",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'luanvan:cau_polyline',
			transparent: true,
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: false,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Ben_xe layer Constructor
	var benXe = new OpenLayers.Layer.WMS(
		"Bến xe",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'luanvan:ben_xe_font_point',
			transparent: true,
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: false,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Ben_xe_buyt layer Constructor
	var benXeBuyt = new OpenLayers.Layer.WMS(
		"Bến xe buýt",
		"http://localhost:8088/geoserver/wms",
		{
			layers: 'luanvan:ben_xe_buyt_point',
			transparent: true,
			styles: '',
			format: format
		},
		{
			singleTile: true,
			ratio: 1,
			isBaseLayer: false,
			transitionEffect: 'resize',
			yx : {'EPSG:4326' : true}
		}
	);
	
	// Add layers into Map
	map.addLayers([background, tinhLo, quocLo, cau, benXe, benXeBuyt]);
	
	// Cong cu do khoang cach, dien tich (http://openlayers.org/dev/examples/measure.html)
	// Trang tri cac net ve khi do
	var sketchSymbolizers = {
		"Point": {
			pointRadius: 4,
			graphicName: "square",
			fillColor: "white",
			fillOpacity: 1,
			strokeWidth: 1,
			strokeOpacity: 1,
			strokeColor: "#004422"
		},
		"Line": {
			strokeWidth: 3,
			strokeOpacity: 1,
			strokeColor: "#11aa00",
			strokeDashstyle: "dash"
		},
		"Polygon": {
			strokeWidth: 2,
			strokeOpacity: 1,
			strokeColor: "#11aa00",
			fillColor: "white",
			fillOpacity: 0.3
		}
	};
	var sketchStyle = new OpenLayers.Style();
	sketchStyle.addRules([
		new OpenLayers.Rule({symbolizer: sketchSymbolizers})
	]);
	var styleMap = new OpenLayers.StyleMap({"default": sketchStyle});
	
	// allow testing of specific renderers via "?renderer=Canvas", etc
	var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
	renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;

	measureControls = {
		line: new OpenLayers.Control.Measure(
			OpenLayers.Handler.Path, {
				persist: true,
				handlerOptions: {
					layerOptions: {
						renderers: renderer,
						styleMap: styleMap
					}
				}
			}
		),
		polygon: new OpenLayers.Control.Measure(
			OpenLayers.Handler.Polygon, {
				persist: true,
				handlerOptions: {
					layerOptions: {
						renderers: renderer,
						styleMap: styleMap
					}
				}
			}
		)
	};
	
	var control;
	for(var key in measureControls) {
		control = measureControls[key];
		control.events.on({
			"measure": handleMeasurements,
			"measurepartial": handleMeasurements
		});
		map.addControl(control);
	}
	
	// Add layer switcher
	var ls = new OpenLayers.Control.LayerSwitcher();
	map.addControl(ls);
	
	// Add lat/long read-out to the menu
	map.addControl(new OpenLayers.Control.MousePosition());
	
	// Add zoom and pan controls to map
	var navTool = new OpenLayers.Control.NavToolbar();
	map.addControl(navTool);
	
	// Add Scale line control
	var scaleLine = new OpenLayers.Control.ScaleLine();
	map.addControl(scaleLine);
	
	// Add Overview map control
	map.addControl(new OpenLayers.Control.OverviewMap());
	
	// Mo rong Layer switcher khi trang da load xong
	ls.maximizeControl();
	
	// Zoom giua ban do
	map.setCenter(centerPoint, zoom);
	
	// Zoom toan ban do
	//map.zoomToMaxExtent(bounds);
	
	// Tat che do do khoang cach khi khoi tao xong
	document.getElementById('noneToggle').checked = true;
	document.getElementById('geodesicToggle').checked = false;
	document.getElementById('immediateToggle').checked = false;
	
	// Ho tro GetFeatureInfo
	map.events.register('click', map, function (e) {
		
		//document.getElementById('nodelist').innerHTML = "Đang lấy thông tin... Vui lòng chờ...";
		// Lam tron toa do, tren Firefox toa do lay so thuc
		var x = parseInt(e.xy.x);
		var y = parseInt(e.xy.y);
		
		// Luu toa do x, y cua man hinh lai tren trang chu
		$("div#x").html(e.clientX);
		$("div#y").html(e.clientY);
		
		// Tao cau truy van cho dich vu GetFeatureInfo
		var url =  map.layers[1].getFullRequestString(
			{
				REQUEST: "GetFeatureInfo",
				EXCEPTIONS: "application/vnd.ogc.se_xml",
				BBOX: map.getExtent().toBBOX(),
				X: x,
				Y: y,
				INFO_FORMAT: 'application/vnd.ogc.gml',
				LAYERS: [map.layers[5].params.LAYERS, map.layers[4].params.LAYERS,
						map.layers[3].params.LAYERS, map.layers[2].params.LAYERS, map.layers[1].params.LAYERS],
				QUERY_LAYERS: [map.layers[5].params.LAYERS, map.layers[4].params.LAYERS,
								map.layers[3].params.LAYERS, map.layers[2].params.LAYERS, map.layers[1].params.LAYERS],
				FEATURE_COUNT: 1,
				WIDTH: map.size.w,
				HEIGHT: map.size.h
			},
			"http://localhost:8088/geoserver/wms"
		);
		OpenLayers.loadURL(url, '', this, setHTML, setHTML);
		OpenLayers.Event.stop(e);
	});
	
	// Hien thi ket qua tim kiem
	// Style cho layer ket qua
	// TODO: Style for showSearchResult layer
	var showSearchResultStyle = OpenLayers.Util.applyDefaults({
									externalGraphic: "images/pink_marker.png",
									graphicWidth: 32,
									graphicHeight: 32,
									graphicYOffset: -26,
									graphicOpacity: 1,
									strokeWidth: 6,
									strokeColor: '#FF0004',
									strokeOpacity: 0.8
								}, OpenLayers.Feature.Vector.style['default']);
	
	// Layer hien ket qua
	showSearchResult = new OpenLayers.Layer.Vector(
		"Kết quả tìm kiếm",
		{
			style: showSearchResultStyle,
			srs: 'EPSG:4326',
			displayInLayerSwitcher: false
		}
	);
	
	map.addLayer(showSearchResult);
	
}// End init()

/********* Phan xu ly do khoang cach ********/
// Ham xu ly cho cong cu do khoang cach
function handleMeasurements(event) {
	var geometry = event.geometry;
	var units = event.units;
	var order = event.order;
	var measure = event.measure;
	var element = document.getElementById('measureResult');
	var out = "";
	if(order == 1) {
		out += "Tổng chiều dài: " + measure.toFixed(3) + " " + units;
	} else {
		out += "Diện tích: " + measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
	}
	element.innerHTML = out;
}

// Ham xu ly chuyen doi qua lai giua cac cong cu do
function toggleControl(element) {
	for(key in measureControls) {
		var control = measureControls[key];
		if(element.value == key && element.checked) {
			control.activate();
		} else {
			control.deactivate();
		}
	}
}

// Ham xu ly chuyen doi giua hai che do do: planar va geodesic
function toggleGeodesic(element) {
	for(key in measureControls) {
		var control = measureControls[key];
		control.geodesic = element.checked;
	}
}

// Ham bat tat che do do tuc thoi
function toggleImmediate(element) {
	for(key in measureControls) {
		var control = measureControls[key];
		control.setImmediate(element.checked);
	}
}
/********* Het phan xu ly do khoang cach ********/

/********* Phan xu ly GetFeatureInfo ********/
// sets the HTML provided into the nodelist element
function setHTML(response) {
	
	// Tao mot doi tuong GML
	var g = new OpenLayers.Format.GML();
	
	// Dung ham read doc gia tri tra ve va chuyen sang dang GML
	var features = g.read(response.responseText);
	
	// Dung Ajax lay thong tin doi tuong nho FID
	OpenLayers.loadURL("lib/get_info.php?fid=" + features[0].fid, '', this, showInfo, showInfo);
};

// Hien thi thong tin len cua so Popup
function showInfo(response) {
	
	// Gan ket qua tra ve vao div#info
	$('div#info').html(response.responseText);
	
	// Lay toa do da luu tren trang chu
	var x = eval($("div#x").html());
	var y = eval($("div#y").html());
	
	// Hien Popup, zIndex = 1000 cho phep hien tren cung
	var $dialog = $('div#info').dialog({
						autoOpen: true,
						width: 'auto',
						position: [x, y],
						zIndex: 10000,
						title: 'Thông tin'
					});
}
/********* Het phan xu ly GetFeatureInfo ********/