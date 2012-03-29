<?php
require_once dirname(__FILE__) . '/../lib/AccessControl.php';

// TODO: after has a login system, remove this parameter
$ac = new AccessControl(1);

if ($ac->hasPermission('quan_tri_nguoi_dung') != true && $ac->hasPermission('lap_bao_cao') != true &&
	$ac->hasPermission('cap_nhat_du_lieu') != true
) {
	//header('Location: ../index.php');
	// Mở trang ../index.php sau 5 giây
	header("refresh:5;url=../index.php");
	// Hiển thị thông báo truy cập trái phép
	include dirname(__FILE__) . '/includes/message.html';
} else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản trị GIS - Giao thông đường bộ Tp. Cần Thơ</title>
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<link type="text/css" rel="stylesheet" href="css/index-content.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Mở menu tương ứng với trang, ẩn các menu khác
    $("#sidebar h3#admin").addClass("active");
	$("#sidebar div:not(#adminCT)").hide();
});
</script>
</head>

<body>
<div id="container">
    <!-- Header include -->
    <?php include_once("includes/header.html"); ?>
	<div id="wrapper">
    	<!-- SideBar include -->
        <?php include_once("includes/sidebar.html"); ?>
		<!-- Index content include -->
        <?php include_once("includes/index-content.php"); ?>
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>
<?php
}
?>