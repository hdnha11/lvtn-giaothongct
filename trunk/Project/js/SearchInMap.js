// JavaScript Document

function searchInMap() {
	var keyWord = $('#searchForm #query').val();
	var layers = $('#searchForm #searchLayer').val();
	
	var url = 'lib/search_map.php?q=' + keyWord + '&layers=' + layers;
	
	OpenLayers.loadURL(url, null, null, getResult, null);
	
	return false;
}

function getResult(response) {
	// Chuyen JSON tu Text ve dang JavaScript Object
	var jsonObj = eval('(' + response.responseText + ')');
	
	if (jsonObj.results.length != 0) {
		
		// Go bo ket qua cu
		$('a.resultItem').remove();
		showSearchResult.removeFeatures(showSearchResult.features);
		
		// Parse the features
		var listItem = '';
		for (var i = 0; i < jsonObj.results.length; i++) {
			parseWKT(jsonObj.results[i].geom);
			
			// Insert text describe for each feature in left side bar
			//$('form#searchForm').after('<a class="resultItem" href="#">' + i + '. ' + jsonObj.results[i].name + '</a>');
			listItem += '<a class="resultItem" href="#">' + (i + 1) + '. ' + jsonObj.results[i].name + '</a>';
		}
		$('form#searchForm').after(listItem);
		
		// Dang ky su kien click cho cac nhan
		$('a.resultItem').click(function(e) {
			// Lay cac ky tu so trong the a
			var pattern = /^\d+/;
			var id = parseInt(e.target.innerHTML.match(pattern)) - 1;
			map.setCenter(showSearchResult.features[id].geometry.bounds.getCenterLonLat(), 3);
		});
		
		// Move va Zoom level 3 toi doi tuong dau tien tim duoc
		map.setCenter(showSearchResult.features[0].geometry.bounds.getCenterLonLat(), 3);
	} else {
		alert("Không tìm thấy dữ liệu");
		// Xoa cac ket qua cu
		$('a.resultItem').remove();
		showSearchResult.removeFeatures(showSearchResult.features);
	}
}

function parseWKT(wkt) {
	var parser = new OpenLayers.Format.WKT();
	var features = parser.read(wkt);
	if (features) {
		showSearchResult.addFeatures(features);
	} else {
		alert("Lỗi định dạng WKT");
	}
}

$(document).ready(function() {
	
	// Dua chuot vao o tu dong xoa du lieu
    $('form#searchForm input#query').focus(function() {
		if ($('form#searchForm input#query').val() === 'Nhập nội dung tìm kiếm') {
			$('form#searchForm input#query').val('');
		}
	});
	
	// Roi khoi o tu dong dien du lieu
	$('form#searchForm input#query').blur(function() {
		if ($('form#searchForm input#query').val() === '') {
			$('form#searchForm input#query').val('Nhập nội dung tìm kiếm');
		}
	});
	
	// if text input field value is not empty show the "X" button
	$('form#searchForm input#query').keyup(function() {
		$('#delete #x').fadeIn();
		if ($.trim($('form#searchForm input#query').val()) == "") {
			$('#delete #x').fadeOut();
		}
	});
	
	// on click of "X", delete input field value and hide "X"
	$('#delete #x').click(function() {
		$('form#searchForm input#query').val('');
		$(this).hide();
	});
});