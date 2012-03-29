<?php
require_once dirname(__FILE__) . '/../lib/AccessControl.php';

// TODO: after has a login system, remove this parameter
$ac = new AccessControl(1);
$db = new PgSQL();

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

if ($ac->hasPermission('quan_tri_nguoi_dung') != true) {
	header("refresh:5;url=index.php");
	include dirname(__FILE__) . '/includes/message.html';
} else {
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
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
}

#updateRoleContent #content {
	margin: 0px;
	padding: 0px 20px;
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
        	if (!isset($_GET['action'])) {
				echo '<h2>Chọn nhóm người dùng cần quản lý:</h2>';
				$roles = $ac->getAllRoles('full');
				foreach ($roles as $k => $v) {
					echo '<a href="?action=role&roleID=' . $v['ID'] . '">' . $v['Name'] . '</a><br />';
				}
				if (count($roles) < 1) {
					echo 'Hiện chưa có nhóm người dùng nào.<br />';
				}
			} elseif ($_GET['action'] == 'role') { 
				
				echo '<h2>Quản lý nhóm: (' . $ac->getRoleNameFromID($_GET['roleID']) . ')</h2>';
				echo '<form action="update_role.php" method="post">';
				echo '<label for="roleName">Tên nhóm:</label><input type="text" name="roleName" id="roleName" value="'
						. $ac->getRoleNameFromID($_GET['roleID']) . '" />';
				echo '<table border="0" cellpadding="5" cellspacing="0">';
				echo '<tr><th></th><th>Cho phép</th><th>Từ chối</th><th>Bỏ qua</th></tr>';
				
				$rPerms = $ac->getRolePerms($_GET['roleID']);
				$aPerms = $ac->getAllPerms('full');
				foreach ($aPerms as $k => $v) {
					echo '<tr><td><label>' . $v['Name'] . '</label></td>';
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
				}
				
				echo '</table>';
				echo '<input type="hidden" name="action" value="saveRole" />';
				echo '<input type="hidden" name="roleID" value="' . $_GET['roleID'] . '" />';
				echo '<input type="submit" name="Submit" value="Cập nhật" />';
				echo '</form>';
				
				echo '<form action="update_role.php" method="post">';
				echo '<input type="hidden" name="action" value="delRole" />';
				echo '<input type="hidden" name="roleID" value="' . $_GET['roleID'] . '" />';
				echo '<input type="submit" name="Delete" value="Xóa" />';
				echo '</form>';
				echo '<form action="update_role.php" method="post">';
				echo '<input type="submit" name="Cancel" value="Hũy bỏ" />';
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
?>