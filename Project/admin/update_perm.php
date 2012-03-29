<?php
require_once dirname(__FILE__) . '/../lib/AccessControl.php';

// TODO: after has a login system, remove this parameter
$ac = new AccessControl(1);
$db = new PgSQL();

if (isset($_POST['action'])) {
	switch($_POST['action']) {
		case 'savePerm':
			$strSQL = sprintf("SELECT replace_into_permissions(%u, '%s', '%s')", $_POST['permID'], $_POST['permKey'], $_POST['permName']);
			$db->connect();
			$db->query($strSQL);
			break;
		case 'delPerm':
			$strSQL = sprintf("DELETE FROM permissions WHERE id = %u", $_POST['permID']);
			$db->connect();
			$db->query($strSQL);
			break;
	}
	
	header("Location: update_perm.php");
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
<title>Cập nhật quyền</title>
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
#updatePermContent {
	float: left;
	width: 732px;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
}

#updatePermContent #content {
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

#updatePermContent #content p {
	line-height: 20px;
	margin: 10px 0px;
}

#updatePermContent #content a {
	line-height: 20px;
	margin: 10px 0px;
	color: #000;
	text-decoration: underline;
}

#updatePermContent #content h2 {
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

/* Định dạng danh sách quyền */
#updatePermContent #content p.rolelisttitle {
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	border-bottom: 1px solid black;
	color: #000;
	font-size: 13px;
	font-weight: bold;
}

#updatePermContent #content a.rolelist {
	display: block;
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	color: #000;
	text-decoration: none;
}

#updatePermContent #content a.even {
	background-color: #DFD;
}

#updatePermContent #content a.odd {
	background-color: #EFE;
}

/* Định dạng các nút bấm */
#updatePermContent form {
	width: 480px;
	margin: 0px auto;
}

#updatePermContent form input.btnForm {
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

/* Định dạng label */
#updatePermContent form label {
	display: block;
	width: 80px;
	float: left;
	margin-left: 110px;
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
        <div id="updatePermContent">
        <h1 class="contentTitle">Cập nhật quyền</h1>
        <div id="content">
        <?php
			// Hiện danh sách quyền
			if (!isset($_GET['action'])) {
                echo '<h2>Chọn quyền cần quản lý:</h2>';
				echo '<p class="rolelisttitle">Tên quyền</p>';
                
                $roles = $ac->getAllPerms('full');
				
				// Biến kiểm tra chẳn lẽ
				$isEven = 0;
                foreach ($roles as $k => $v) {
					$roleClass = ($isEven % 2 == 0) ? 'rolelist even' : 'rolelist odd';
                    echo '<a class="' . $roleClass . '" href="?action=perm&permID=' . $v['ID'] . '">' . $v['Name'] . '</a>';
					// Tăng biến chẳn lẽ
					$isEven++;
                }
                if (count($roles) < 1) {
                    echo '<p>Hiện chưa có quyền nào.</p>';
                }
			} elseif ($_GET['action'] == 'perm') { // Cập nhật cho quyền đã chọn
				echo '<h2>Quản lý quyền: ('. $ac->getPermNameFromID($_GET['permID']) . ')</h2>';
				echo '<form action="update_perm.php" method="post">';
				echo '<p><label for="permName">Tên quyền:</label><input type="text" name="permName" id="permName" value="'
						. $ac->getPermNameFromID($_GET['permID']) . '" maxlength="30" /></p>';
				echo '<p><label for="permKey">Khóa:</label><input type="text" name="permKey" id="permKey" value="'
						. $ac->getPermKeyFromID($_GET['permID']) . '" maxlength="30" /></p>';
				echo '<input type="hidden" name="action" value="savePerm" />';
				echo '<input type="hidden" name="permID" value="' . $_GET['permID'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '</form>';
				echo '<form action="update_perm.php" method="post">';
				echo '<input type="hidden" name="action" value="delPerm" />';
				echo '<input type="hidden" name="permID" value="' . $_GET['permID'] . '" />';
				echo '<input type="submit" name="Delete" class="btnForm" value="Xóa" />';
				echo '</form>';
				echo '<form action="update_perm.php" method="post">';
				echo '<input type="submit" name="Cancel" class="btnForm" value="Hũy bỏ" />';
				echo '</form>';
			}
		?>
        </div>
    </div><!--End updatePermContent-->
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>
<?php
}
?>