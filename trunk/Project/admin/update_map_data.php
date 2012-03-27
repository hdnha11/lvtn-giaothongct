<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản trị GIS - Cập nhật dữ liệu thuộc tính</title>
<link type="text/css" rel="stylesheet" href="../css/ui-lightness/jquery-ui-1.8.18.custom.css" />
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<link rel="stylesheet" type="text/css" href="css/admin-infobox.css" />
<link rel="stylesheet" type="text/css" href="css/map.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../js/jquery/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="../js/OpenLayers-2.11/OpenLayers.js"></script>
<script type="text/javascript" src="../js/OpenLayers-2.11/lang/vi.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript" src="js/AdminMapHandle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#sidebar h3#updateDate").addClass("active");
	$("#sidebar div:not(#updateDateCT)").hide();
});
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