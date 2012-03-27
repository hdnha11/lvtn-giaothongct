// JavaScript Document
// Ban do
var map;

// Layer hien thi ket qua tim kiem
var showSearchResult;

// pink tile avoidance
OpenLayers.IMAGE_RELOAD_ATTEMPTS = 5;

// make OL compute scale according to WMS spec
OpenLayers.DOTS_PER_INCH = 25.4 / 0.28;

// PHP Proxy dung cho viec goi AJAX Cross Server
OpenLayers.ProxyHost = '../lib/geoproxy.php?url=';

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
	
	// Add layers into Map
	map.addLayers([background, tinhLo, quocLo, benXe]);
	
	
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
	
	// Zoom giua ban do
	map.setCenter(centerPoint, zoom);
	
	// Zoom toan ban do
	//map.zoomToMaxExtent(bounds);
	
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
				LAYERS: [map.layers[1].params.LAYERS, map.layers[2].params.LAYERS, map.layers[3].params.LAYERS],
				QUERY_LAYERS: [map.layers[1].params.LAYERS, map.layers[2].params.LAYERS, map.layers[3].params.LAYERS],
				FEATURE_COUNT: 1,
				WIDTH: map.size.w,
				HEIGHT: map.size.h
			},
			"http://localhost:8088/geoserver/wms"
		);
		OpenLayers.loadURL(url, '', this, setHTML, setHTML);
		OpenLayers.Event.stop(e);
	});
	
	
}// End init()


/********* Phan xu ly GetFeatureInfo ********/
// sets the HTML provided into the nodelist element
function setHTML(response) {
	
	// Tao mot doi tuong GML
	var g = new OpenLayers.Format.GML();
	
	// Dung ham read doc gia tri tra ve va chuyen sang dang GML
	var features = g.read(response.responseText);
	
	// Dung Ajax lay thong tin doi tuong nho FID
	OpenLayers.loadURL("../lib/get_info.php?fid=" + features[0].fid, '', this, showInfo, showInfo);
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