<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';

if (Login::isLoggedIn()) {
	$ac = new AccessControl();
	
	if ($ac->hasPermission('lap_bao_cao') != true) {
		header("refresh:5;url=index.php");
		// Hiển thị thông báo truy cập trái phép
		include dirname(__FILE__) . '/includes/message.html';
	} else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Báo cáo xây mới, duy tu, sửa chửa và nâng cấp đường bộ</title>
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Mở menu tương ứng với trang, ẩn các menu khác
    $("#sidebar h3#admin").addClass("active");
	$("#sidebar div:not(#roadReportCT)").hide();
	
	// Tạo báo cáo dạng PDF
	$('#btnPDF').click(function() {
		$.post('lib/get_report_duong_xm_dt_sc_pdf.php', $('#reportDuongXMDTSC').serialize());
	});
});
</script>

<style type="text/css">
#roadReportContent {
	float: left;
	width: 732px;
	min-height: 82%;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
	background: #F7F7F7; /*Test*/
}

#roadReportContent #content {
	position: relative;
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

#roadReportContent #reportDuongXMDTSC {
	padding: 40px 0px 0px 20px;
}

/* Định dạng các nút bấm */
form input.btnForm {
	cursor: pointer;
	width: 120px;
	height: 29px;
	line-height: 25px;
	font-size: 12px;
	font-weight: bold;
	color: #fff;
	background: #3c85fe;
	border: 1px solid #3079ED;
	margin: 20px 5px 0px 0px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
}

/* Định dạng label */
form label {
	width: 125px;
	height: 22px;
	line-height: 22px;
	color: #039;
}
</style>
</head>

<body>
<div id="container">
    <!-- Header include -->
    <?php include_once("includes/header.html"); ?>
	<div id="wrapper">
    	<!-- SideBar include -->
        <?php include_once("includes/sidebar.html"); ?>
		<!-- Content -->
        <div id="roadReportContent">
            <h1 class="contentTitle">Báo cáo xây mới, duy tu, sửa chửa và nâng cấp đường bộ</h1>
            <div id="content">
            	<form name="reportDuongXMDTSC" id="reportDuongXMDTSC" action="lib/get_report_duong_xm_dt_sc.php" method="post" target="_blank">
                	<div>
                    	<label for="quarter">Quí:</label>
                        <select name="quarter" id="quarter">
                        	<option value="1">I</option>
                            <option value="2">II</option>
                            <option value="3">III</option>
                            <option value="4">IV</option>
                        </select>
                        <label for="year">Năm:</label>
                        <select name="year" id="year">
                        <?php
							for ($i = 1990; $i <= date('Y'); $i++) {
                        		echo '<option value="' . $i . '">' . $i . '</option>';
							}
						?>
                        </select>
                    </div>
                    <div>
                    	<input type="submit" name="btnReport" id="btnReport" class="btnForm" value="Tạo bản in" />
                        <input type="button" name="btnPDF" id="btnPDF" class="btnForm" value="Báo cáo dạng PDF" />
                    </div>
                </form>
            </div>
		</div><!--End roadReportContent-->
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>
<?php
	}
} else {
	// Chuyễn tới trang login
	header("Location: login.php");
}
?>