<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';

if (Login::isLoggedIn()) {

	$ac = new AccessControl();
	$db = new PgSQL();
	
	if ($ac->hasPermission('quan_tri_nguoi_dung') != true) {
		header("refresh:5;url=index.php");
		include dirname(__FILE__) . '/includes/message.html';
	} else {
		
		if (isset($_POST['action'])) {
			switch($_POST['action']) {
				case 'saveRole':
					$strSQL = sprintf("SELECT replace_into_roles(%u, '%s')", $_POST['roleID'], $_POST['roleName']);
					$db->connect();
					$db->query($strSQL);
					
					$roleID = $_POST['roleID'];
					
					foreach ($_POST as $k => $v) {
						if (substr($k, 0, 5) == 'perm_') {
							$permID = str_replace('perm_', '', $k);
							if ($v == 'X') {
								$strSQL = sprintf("DELETE FROM role_perms WHERE roleid = %u AND permid = %u", $roleID, $permID);
								$db->query($strSQL);
								continue;
							}
							
							$strSQL = sprintf("SELECT replace_into_role_perms(%u, %u, '%s', '%s')",
												$roleID, $permID, $v, date ("Y-m-d H:i:s"));
							$db->query($strSQL);
						}
					}
					
					header("Location: update_role.php");
					break;
					
				case 'delRole':
					$db->connect();
					$strSQL = sprintf("DELETE FROM roles WHERE id = %u", $_POST['roleID']);
					$db->query($strSQL);
					$strSQL = sprintf("DELETE FROM user_roles WHERE roleid = %u", $_POST['roleID']);
					$db->query($strSQL);
					$strSQL = sprintf("DELETE FROM role_perms WHERE roleid = %u", $_POST['roleID']);
					$db->query($strSQL);
					
					header("Location: update_role.php");
					break;
			}
		}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cập nhật nhóm người dùng</title>
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
#updateRoleContent {
	float: left;
	width: 732px;
	min-height: 82%;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
	background: #F7F7F7; /*Test*/
}

#updateRoleContent #content {
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

#updateRoleContent #content p {
	line-height: 20px;
	margin: 10px 0px;
}

#updateRoleContent #content a {
	line-height: 20px;
	margin: 10px 0px;
	color: #000;
	text-decoration: underline;
}

#updateRoleContent #content h2 {
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

/* Định dạng danh sách nhóm người dùng */
#updateRoleContent #content p.rolelisttitle {
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	border-bottom: 1px solid black;
	color: #000;
	font-size: 13px;
	font-weight: bold;
}

#updateRoleContent #content a.rolelist {
	display: block;
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	color: #000;
	text-decoration: none;
}

#updateRoleContent #content a.even {
	background-color: #DFD;
}

#updateRoleContent #content a.odd {
	background-color: #EFE;
}

/* Định dạng bảng */
#updateRoleContent table {
	/*font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;*/
	width: 480px;
	margin: 0px auto;
	text-align: center;
	border-collapse: collapse;
}

#updateRoleContent table th {
	font-size: 14px;
	font-weight: normal;
	padding: 10px 8px;
	color: #039;
}

#updateRoleContent table td {
	padding: 8px;
	color: #669;
}

#updateRoleContent table td.rowtitle {
	text-align: left;
}

#updateRoleContent table .even {
	background: #e8edff; 
}

/* Định dạng các nút bấm */
#updateRoleContent form {
	width: 480px;
	margin: 0px auto;
}

#updateRoleContent form input.btnForm {
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
#updateRoleContent form label {
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
        <div id="updateRoleContent">
        <h1 class="contentTitle">Cập nhật nhóm người dùng</h1>
        <div id="content">
        <?php
			// Hiện danh sách quyền
        	if (!isset($_GET['action'])) {
				echo '<h2>Chọn nhóm người dùng cần quản lý:</h2>';
				echo '<p class="rolelisttitle">Tên nhóm</p>';
				$roles = $ac->getAllRoles('full');
				
				// Biến kiểm tra chẳn lẽ
				$isEven = 0;
				foreach ($roles as $k => $v) {
					$roleClass = ($isEven % 2 == 0) ? 'rolelist even' : 'rolelist odd';
					echo '<a class="' . $roleClass . '" href="?action=role&roleID=' . $v['ID'] . '">' . $v['Name'] . '</a>';
					$isEven++;
				}
				if (count($roles) < 1) {
					echo '<p>Hiện chưa có nhóm người dùng nào.</p>';
				}
			} elseif ($_GET['action'] == 'role') { // Cập nhật nhóm quyền đã chọn
				
				echo '<h2>Quản lý nhóm: (' . $ac->getRoleNameFromID($_GET['roleID']) . ')</h2>';
				echo '<form action="update_role.php" method="post">';
				echo '<label for="roleName">Tên nhóm: </label><input type="text" name="roleName" id="roleName" value="'
						. $ac->getRoleNameFromID($_GET['roleID']) . '" />';
				echo '<table border="0" cellpadding="5" cellspacing="0">';
				echo '<tr><th></th><th>Cho phép</th><th>Từ chối</th><th>Bỏ qua</th></tr>';
				
				$rPerms = $ac->getRolePerms($_GET['roleID']);
				$aPerms = $ac->getAllPerms('full');
				
				// Biến chẳn lẽ dùng trang trí bảng
				$isEven = 0;
				foreach ($aPerms as $k => $v) {
					
					// Thêm class chẳn lẽ cho từng dòng
					$evenClass = ($isEven % 2 === 0) ? 'even' : 'odd';
					
					echo '<tr class="' . $evenClass . '"><td class="rowtitle"><label>' . $v['Name'] . '</label></td>';
					echo '<td><input type="radio" name="perm_' . $v['ID'] . '" id="perm_"' . $v['ID'] . '_1" value="1"';
					if (array_key_exists($v['Key'], $rPerms)) {
						if ($rPerms[$v['Key']]['value'] === true && $_GET['roleID'] != '') {
							echo ' checked="checked"';
						}
					}
					echo ' /></td>';
					echo '<td><input type="radio" name="perm_' . $v['ID'] . '" id="perm_' . $v['ID'] . '_0" value="0"';
					if (array_key_exists($v['Key'], $rPerms)) {
						if ($rPerms[$v['Key']]['value'] != true && $_GET['roleID'] != '') {
							echo ' checked="checked"';
						}
					}
					echo ' /></td>';
					echo '<td><input type="radio" name="perm_' . $v['ID'] . '" id="perm_' . $v['ID'] . '_X" value="X"';
					if ($_GET['roleID'] == '' || !array_key_exists($v['Key'], $rPerms)) {
						echo ' checked="checked"';
					}
					echo ' /></td>';
					echo '</tr>';
					
					// Tăng biến chẳn lẽ
					$isEven++;
				}
				
				echo '</table>';
				echo '<input type="hidden" name="action" value="saveRole" />';
				echo '<input type="hidden" name="roleID" value="' . $_GET['roleID'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '</form>';
				
				echo '<form action="update_role.php" method="post">';
				echo '<input type="hidden" name="action" value="delRole" />';
				echo '<input type="hidden" name="roleID" value="' . $_GET['roleID'] . '" />';
				echo '<input type="submit" name="Delete" class="btnForm" value="Xóa nhóm" />';
				echo '</form>';
				echo '<form action="update_role.php" method="post">';
				echo '<input type="submit" name="Cancel" class="btnForm" value="Hũy bỏ" />';
				echo '</form>';
			}
		?>
        </div>
    </div><!--End updateRoleContent-->
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