<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';

if (Login::isLoggedIn()) {

	$ac = new AccessControl();
	$db = new PgSQL();
	
	if ($ac->hasPermission('cap_nhat_du_lieu') != true) {
		header("refresh:5;url=index.php");
		include dirname(__FILE__) . '/includes/message.html';
	} else {

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản trị GIS - Cập nhật dữ liệu thuộc tính</title>
<link type="text/css" rel="stylesheet" href="../css/ui-lightness/jquery-ui-1.8.18.custom.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery.autocomplete.css" />
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<link rel="stylesheet" type="text/css" href="css/admin-infobox.css" />
<link rel="stylesheet" type="text/css" href="css/map.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../js/jquery/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery/jquery.autocomplete.pack.js"></script>
<script type="text/javascript" src="../js/OpenLayers-2.11/OpenLayers.js"></script>
<script type="text/javascript" src="../js/OpenLayers-2.11/lang/vi.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript" src="js/AdminMapHandle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Mở menu tương ứng với trang, ẩn các menu khác
    $("#sidebar h3#updateDate").addClass("active");
	$("#sidebar div:not(#updateDateCT)").hide();
});

var updateDuong = function() {
	
	$.post("lib/map_update_duong.php", $("#update_info").serialize(), function(data) {
		$("div#info").html(data);
		// Refresh layer tinhLo
		tinhLo.redraw(true);
		quocLo.redraw(true);
	});
	
	return false;
};

var setResult = function() {
	$("div#info input#duong").result(function(event, data, formatted) {
		$("div#info input#id_duong").val(data[0]);
	});
};

var setAutocomplete = function() {
	var tableName = $('div#info form#update_info input#table').val();
	switch (tableName) {
		case 'tinh_lo_polyline':
			// Autocomplete đường tỉnh
			$("div#info input#duong").autocomplete('lib/autocomplete_tinh_lo.php', {
				formatItem: function(data) {
					return data[1];
				},
				formatResult: function(data) {
					return data[1];
				}
			});
				
			// Xóa đường liên kết với tỉnh lộ
			$("div#info a#deleteDuong").click(function() {
				$("div#info input#duong").val('');
				$("div#info input#id_duong").val('');
				
				return false;
			});
			
			// Lấy tên đường gán cho nhãn
			$("div#info a#getTenDuong").click(function() {
				if ($("div#info input#duong").val() !== '') {
					$("div#info input#nhan").val($("div#info input#duong").val());
				}
				
				return false;
			});
			break;
			
		case 'quoc_lo_polyline':
			// Autocomplete đường tỉnh
			$("div#info input#duong").autocomplete('lib/autocomplete_quoc_lo.php', {
				formatItem: function(data) {
					return data[1];
				},
				formatResult: function(data) {
					return data[1];
				}
			});
				
			// Xóa đường liên kết với tỉnh lộ
			$("div#info a#deleteDuong").click(function() {
				$("div#info input#duong").val('');
				$("div#info input#id_duong").val('');
				
				return false;
			});
			
			// Lấy tên đường gán cho nhãn
			$("div#info a#getTenDuong").click(function() {
				if ($("div#info input#duong").val() !== '') {
					$("div#info input#nhan").val($("div#info input#duong").val());
				}
				
				return false;
			});
			break;
	
	}
};
</script>
</head>

<body onload="init();">
<div id="container">
    <!-- Header include -->
    <?php include_once("includes/header.html"); ?>
	<div id="wrapper">
    	<!-- SideBar include -->
        <?php include_once("includes/sidebar.html"); ?>
		<!-- Map include -->
        <?php include_once("includes/map.html"); ?>
	</div><!--End Wrapper-->
	
</div><!--End Container-->
<!-- Show feature info in a dialog -->
<div id="info"></div>
<!-- Show feature info in a dialog -->
<div id="x" style="display:none"></div>
<div id="y" style="display:none"></div>
</body>
</html>
<?php
	}
} else {
	// Chuyễn tới trang login với status=notlogin
	header("Location: login.php?status=notlogin");
}
?>