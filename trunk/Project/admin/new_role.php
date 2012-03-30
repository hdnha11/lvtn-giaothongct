<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';

if (Login::isLoggedIn()) {
	$ac = new AccessControl();
	$db = new PgSQL();
	
	// Nếu một trong các form cập nhật được submit
	if (isset($_POST['action'])) {
		
		$strSQL = sprintf("INSERT INTO roles(rolename) VALUES('%s')", $_POST['roleName']);
		$db->connect();
		$db->query($strSQL);
		
		// Load lại trang vừa submit
		header("Location: new_role.php" . $redir);
	}

	if ($ac->hasPermission('quan_tri_nguoi_dung') != true) {
		header("refresh:5;url=index.php");
		include dirname(__FILE__) . '/includes/message.html';
	} else {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thêm mới nhóm người dùng</title>
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Mở menu tương ứng với trang, ẩn các menu khác
    $("#sidebar h3#admin").addClass("active");
	$("#sidebar div:not(#adminCT)").hide();
});
</script>
<style type="text/css">
#newRoleContent {
	float: left;
	width: 732px;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
	background: #F7F7F7; /*Test*/
}

#newRoleContent #content {
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

#newRoleContent #content p {
	line-height: 20px;
	margin: 10px 0px;
}

#newRoleContent #content h2 {
	font-family: Georgia, "Times New Roman", Times, serif;
	letter-spacing: .10em;
	font-size: 24px;
	font-weight: 100;
	border-bottom: groove 2px #CCC;
	width: auto;
	line-height: 24px;
	font-variant: small-caps;
	text-transform: none;
	text-align: center;
}

/* Định dạng các nút bấm */
#newRoleContent form {
	width: 480px;
	margin: 0px auto;
}

#newRoleContent form input.btnForm {
	cursor: pointer;
	width: 80px;
	height: 29px;
	line-height: 25px;
	font-size: 12px;
	font-weight: bold;
	color: #fff;
	background: #3c85fe;
	border: 1px solid #3079ED;
	margin: 5px 0px 0px 200px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
}

/* Định dạng label form edit user */
#newRoleContent form label {
	display: block;
	width: 60px;
	float: left;
	margin-left: 130px;
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
        <div id="newRoleContent">
        <h1 class="contentTitle">Thêm nhóm người dùng mới</h1>
        <div id="content">
			<h2>Thêm nhóm người dùng</h2>
			
			<form action="new_role.php" method="post">
                <p>
                    <label for="roleName">Tên nhóm:</label>
                    <input type="text" name="roleName" id="roleName" value="" maxlength="20" />
                </p>
                <input type="hidden" name="action" value="addRole" />
                <input type="submit" name="Submit" class="btnForm" value="Thêm mới" />
			</form>
            <form action="new_role.php" method="post">
                <input type="submit" name="Cancel" class="btnForm" value="Hũy bỏ" />
			</form>
        </div>
    </div><!--End newRoleContent-->
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>
<?php
	}
} else {
	// Chuyễn tới trang login với status=notlogin
	header("Location: login.php?status=notlogin");
}
?>