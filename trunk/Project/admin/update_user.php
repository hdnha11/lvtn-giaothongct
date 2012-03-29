<?php
require_once dirname(__FILE__) . '/../lib/AccessControl.php';

// TODO: after has a login system, remove this parameter
$ac = new AccessControl(1);
$db = new PgSQL();

// Nếu một trong các form cập nhật được submit
if (isset($_POST['action'])) {
	switch($_POST['action']) {
		// Cập nhật nhóm người dùng
		case 'saveRoles':
			// Dùng load lại trang vừa submit
			$redir = '?action=user&userID=' . $_POST['userID'];
			
			// Duyệt từng phần tử trên form
			foreach ($_POST as $k => $v) {
				//Nếu là các radio button tương ứng với từng nhóm người dùng
				if (substr($k, 0, 5) == 'role_') {
					// Lấy ID nhóm bằng cách bỏ đi role_
					$roleID = str_replace('role_', '', $k);
					// Nếu radio có giá trị 0 thì xóa bỏ record có ID nhóm và userid trong bảng user_roles 
					if ($v == '0') {
						$strSQL = sprintf("DELETE FROM user_roles WHERE userid = %u AND roleid = %u", $_POST['userID'], $roleID);
					} else { // Chỉ cập nhật lại giá trị, replace_into_user_roles là hàm tự viết
						$strSQL = sprintf("SELECT replace_into_user_roles(%u, %u, '%s')",
											$_POST['userID'], $roleID, date ('Y-m-d H:i:s'));
					}
					$db->connect();
					$data = $db->query($strSQL);
				}
			}			
			break;
		
		// Cập nhật quyền
		case 'savePerms':
			$redir = '?action=user&userID=' . $_POST['userID'];
			foreach ($_POST as $k => $v) {
				if (substr($k, 0, 5) == 'perm_') {
					$permID = str_replace('perm_', '', $k);
					if ($v == 'x') {
						$strSQL = sprintf("DELETE FROM user_perms WHERE userid = %u AND permid = %u", $_POST['userID'], $permID);
					} else {
						$strSQL = sprintf("SELECT replace_into_user_perms(%u, %u, '%s', '%s')",
						$_POST['userID'], $permID, $v, date ("Y-m-d H:i:s"));
					}
					$db->connect();
					$data = $db->query($strSQL);
				}
			}
			break;
	}
	
	// Load lại trang vừa submit
	header("Location: update_user.php" . $redir);
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
<title>Cập nhật người dùng</title>
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
#updateUserContent {
	float: left;
	width: 732px;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
}

#updateUserContent #content {
	margin: 0px;
	padding: 0px 20px;
}

#updateUserContent #content p {
	line-height: 20px;
	margin: 10px 0px;
}

#updateUserContent #content a {
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
        <div id="updateUserContent">
        <h1 class="contentTitle">Cập nhật người dùng</h1>
        <div id="content">
        <?php
			// Hiện danh sách user
        	if (!isset($_GET['action'])) {
				echo '<h2>Chọn người dùng muốn cập nhật:</h2>';
				
				$strSQL = "SELECT * FROM users ORDER BY username ASC";
				$db->connect();
				$data = $db->query($strSQL);
				while ($row = pg_fetch_assoc($data)) {
					echo '<a href="?action=user&userID=' . $row['id'] . '">' . $row['username'] . '</a><br />';
				}
			} elseif ($_GET['action'] == 'user' ) { // Hiện trang quản lý của người dùng đã chọn
				$userAC = new AccessControl($_GET['userID']);
				
				echo '<h2>Cập nhật cho ' . $ac->getUsername($_GET['userID']) . ':</h2>';
				
				// TODO: Some form to edit user info here
				
				echo '<h3>Người dùng thuộc nhóm:   (<a href="?action=roles&userID=' . $_GET['userID'] . '">Quản lý nhóm</a>)</h3>';
				echo '<ul>';
				$roles = $userAC->getUserRoles();
				
				// Hiển thị nhóm của người dùng đang thuộc về
				foreach ($roles as $k => $v) {
					echo '<li>' . $userAC->getRoleNameFromID($v) . '</li>';
				}
				
				echo '</ul>';
				echo '<h3>Người dùng có quyền:   (<a href="?action=perms&userID=' . $_GET['userID'] . '">Quản lý quyền</a>)</h3>';
				echo '<ul>';
				$perms = $userAC->perms;
				
				// Hiện danh sách quyền của người dùng đang có
				foreach ($perms as $k => $v) {
					if ($v['value'] === false) {
						continue;
					}
					echo '<li>' . $v['Name'];
					if ($v['inheritted']) {
						 echo '  (Được thừa hưởng)';
					}
					echo '</li>';
				}
				echo '</ul>';
			} elseif ($_GET['action'] == 'roles') { // Quản lý nhóm người dùng của người dùng đã chọn
                echo '<h2>Quản lý nhóm người dùng: (' . $ac->getUsername($_GET['userID']) . ')</h2>';
                echo '<form action="update_user.php" method="post">';
                echo '<table border="0" cellpadding="5" cellspacing="0">';
                echo '<tr><th></th><th>Thành viên</th><th>Không phải thành viên</th></tr>';
				
                $roleAC = new AccessControl($_GET['userID']);
                $roles = $roleAC->getAllRoles('full');
                foreach ($roles as $k => $v) {
                    echo '<tr><td><label>' . $v['Name'] . '</label></td>';
                    echo '<td><input type="radio" name="role_' . $v['ID'] . '" id="role_' . $v['ID'] . '_1" value="1"';
                    if ($roleAC->userHasRole($v['ID'])) { // Nếu thuộc nhóm này
						echo ' checked="checked"';
					}
                    echo ' /></td>';
                    echo '<td><input type="radio" name="role_' . $v['ID'] . '" id="role_' . $v['ID'] . '_0" value="0"';
                    if (!$roleAC->userHasRole($v['ID'])) { // Nếu không thuộc nhóm này
						echo ' checked="checked"';
					}
                    echo ' /></td>';
                    echo '</tr>';
                }
                
				// Form dùng cập nhật nhóm người dùng
                echo '</table>';
                echo '<input type="hidden" name="action" value="saveRoles" />';
                echo '<input type="hidden" name="userID" value="' . $_GET['userID'] . '" />';
                echo '<input type="submit" name="Submit" value="Cập nhật" />';
                echo '</form>';
				
				// Thoát không cập nhật
                echo '<form action="update_user.php" method="post">';
                echo '<input type="button" name="Cancel" onclick="window.location=' . "'?action=user&userID=" 
					. $_GET['userID'] ."'" . '" value="Hũy bỏ" />';
                echo '</form>';
			} elseif ($_GET['action'] == 'perms') { // Quản lý quyền của người dùng đã chọn
				echo '<h2>Quản lý quyền người dùng: (' . $ac->getUsername($_GET['userID']) . ')</h2>';
				echo '<form action="update_user.php" method="post">';
				echo '<table border="0" cellpadding="5" cellspacing="0">';
				echo '<tr><th></th><th></th></tr>';
				
				$userAC = new AccessControl($_GET['userID']);
				$rPerms = $userAC->perms;
				$aPerms = $userAC->getAllPerms('full');
				foreach ($aPerms as $k => $v) {
					echo '<tr><td>' . $v['Name'] . '</td>';
					echo '<td><select name="perm_' . $v['ID'] . '">';
					echo '<option value="1"';
					if (array_key_exists($v['Key'], $rPerms)) { // Nếu tồn tại khóa này trong danh sách quyền của người dùng
						// Nếu người dùng có quyền này và quyền không được thừa hưởng từ nhóm
						if ($userAC->hasPermission($v['Key']) && $rPerms[$v['Key']]['inheritted'] != true) {
							// Chọn mục này
							echo ' selected="selected"';
						}
					}
					echo '>Cho phép</option>';
					echo '<option value="0"';
					if (array_key_exists($v['Key'], $rPerms)) { // Nếu tồn tại khóa này trong danh sách quyền của người dùng
						// Nếu người dùng không có quyền này và quyền không được thừa hưởng từ nhóm
						if (!$userAC->hasPermission($v['Key']) && $rPerms[$v['Key']]['inheritted'] != true) {
							// Chọn mục này
							echo ' selected="selected"';
						}
					}
					echo '>Từ chối</option>';
					echo '<option value="x"';
					if (array_key_exists($v['Key'], $rPerms)) { // Nếu tồn tại khóa này trong danh sách quyền của người dùng
						// Nếu quyền này thừa hưởng từ nhóm
						if ($rPerms[$v['Key']]['inheritted'] == true) {
							// Chọn mục này
							echo ' selected="selected"';
							// Nếu người dùng có quyền này
							if ($rPerms[$v['Key']]['value'] === true) {
								$iVal = '(Cho phép)';
							} else {
								$iVal = '(Từ chối)';
							}
						} else { // Nếu không thừa hưởng từ nhóm
							$iVal = '';
						}
					} else { // Nếu không tồn tại khóa
						// Chọn mục này và ghi là Thừa hưởng từ chối
						echo ' selected="selected"';
						$iVal = '(Từ chối)';
					}
					echo '>Thừa hưởng ' . $iVal . '</option>';
					echo '</select></td></tr>';
				}
				
				// Form dùng cập nhật quyền
				echo '</table>';
				echo '<input type="hidden" name="action" value="savePerms" />';
				echo '<input type="hidden" name="userID" value="'. $_GET['userID'] . '" />';
				echo '<input type="submit" name="Submit" value="Cập nhật" />';
				echo '</form>';
				
				// Form dùng thoát không cập nhật
				echo '<form action="update_user.php" method="post">';
				echo '<input type="button" name="Cancel" onclick="window.location=' . "'?action=user&userID=" .
					$_GET['userID'] . "'" . '" value="Hũy bỏ" />';
				echo '</form>';
			}
		?>
        </div>
    </div><!--End updateUserContent-->
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>
<?php
}
?>