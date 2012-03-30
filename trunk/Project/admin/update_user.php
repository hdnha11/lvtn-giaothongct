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
		
		// Nếu một trong các form cập nhật được submit
		if (isset($_POST['action'])) {
			switch($_POST['action']) {
				
				// Cập nhật user
				case 'saveUser':
					$strSQL = sprintf("SELECT replace_into_users(%u, '%s', '%s')", $_POST['userID'], $_POST['userName'], md5($_POST['password']));
					$db->connect();
					$db->query($strSQL);
					break;
					
				// Xóa user
				case 'delUser':
					$strSQL = sprintf("DELETE FROM users WHERE id = %u", $_POST['userID']);
					$db->connect();
					$db->query($strSQL);
					break;
				
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
	background: #F7F7F7; /*Test*/
}

#updateUserContent #content {
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

#updateUserContent #content p {
	line-height: 20px;
	margin: 10px 0px;
}

#updateUserContent #content h2 {
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

/* Định dạng danh sách người dùng */
#updateUserContent #content p.userlisttitle {
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	border-bottom: 1px solid black;
	color: #000;
	font-size: 13px;
	font-weight: bold;
}

#updateUserContent #content a.userlist {
	display: block;
	line-height: 18px;
	margin: 0px;
	padding: 2px 10px;
	color: #000;
	text-decoration: none;
}

#updateUserContent #content a.even {
	background-color: #DFD;
}

#updateUserContent #content a.odd {
	background-color: #EFE;
}

/* Định dạng danh sách lồng */
#updateUserContent {
	color:#111;
}

#updateUserContent ul {
	list-style-type: none;
	font-weight: bold;
}

#updateUserContent ul li {
	margin: 20px 0px 0px 0px;
}

#updateUserContent ul li ul {
	/*list-style-image: url("images/nested.png");*/
	padding: 5px 0 5px 18px;
}

#updateUserContent ul li ul li {
	display: block;
	color: #3E7E9D;
	background: url("images/nested.png") no-repeat;
	height: 25px;
	line-height: 25px;
	text-indent: 30px;
	margin: 0px;
}

#updateUserContent ul li a {
	color: #F8AF5A;
}

#updateUserContent ul li a:hover {
	text-decoration: underline;
}

/* Định dạng bảng */
#updateUserContent table {
	/*font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;*/
	width: 480px;
	margin: 0px auto;
	text-align: center;
	border-collapse: collapse;
}

#updateUserContent table th {
	font-size: 14px;
	font-weight: normal;
	padding: 10px 8px;
	color: #039;
}

#updateUserContent table td {
	padding: 8px;
	color: #669;
}

#updateUserContent table td.rowtitle {
	text-align: left;
}

#updateUserContent table .even {
	background: #e8edff; 
}

/* Định dạng các nút bấm */
#updateUserContent form {
	width: 480px;
	margin: 0px auto;
}

#updateUserContent form input.btnForm {
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

/* Định dạng các dropdown box */
#updateUserContent form select {
	width: 172px;
}

/* Định dạng label */
#updateUserContent form label {
	color: #039;
}

/* Định dạng phần cập nhật user */
#updateUserContent div#editUser {
	border: 1px solid #c4c4c4;
	padding: 10px;
}

/* Định dạng label form edit user */
#updateUserContent div#editUser form label {
	display: block;
	width: 120px;
	float: left;
	margin-left: 90px;
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
        <div id="updateUserContent">
        <h1 class="contentTitle">Cập nhật người dùng</h1>
        <div id="content">
        <?php
			// Hiện danh sách user
        	if (!isset($_GET['action'])) {
				echo '<h2>Chọn người dùng muốn cập nhật:</h2>';
				echo '<p class="userlisttitle">Tên đăng nhập</p>';
				$strSQL = "SELECT * FROM users ORDER BY username ASC";
				$db->connect();
				$data = $db->query($strSQL);
				
				// Biến kiểm tra chẳn lẽ
				$isEven = 0;
				while ($row = pg_fetch_assoc($data)) {
					// Kiểm tra chẳn lẽ dùng cho mục đích trang trí
					if ($isEven % 2 === 0) {
						echo '<a class="userlist even" href="?action=user&userID=' . $row['id'] . '">' . $row['username'] . '</a>';
					} else {
						echo '<a class="userlist odd" href="?action=user&userID=' . $row['id'] . '">' . $row['username'] . '</a>';
					}
					$isEven++;
				}
			} elseif ($_GET['action'] == 'user' ) { // Hiện trang quản lý của người dùng đã chọn
				$userAC = new AccessControl($_GET['userID']);
				
				echo '<h2>Cập nhật cho <span class="username">' . $ac->getUsername($_GET['userID']) . '</span>:</h2>';
				
				// Forms to edit user info here
				echo '<div id="editUser">';
				echo '<form action="update_user.php" method="post">';
				echo '<p><label for="userName">Tài khoản:</label><input type="text" name="userName" id="userName" value="'
						. $ac->getUsername($_GET['userID']) . '" maxlength="20" /></p>';
				echo '<p><label for="password">Mật khẩu:</label>'
						. '<input type="password" name="password" id="password" value="" maxlength="20" /></p>';
				echo '<input type="hidden" name="action" value="saveUser" />';
				echo '<input type="hidden" name="userID" value="' . $_GET['userID'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '</form>';
				echo '<form action="update_user.php" method="post">';
				echo '<input type="hidden" name="action" value="delUser" />';
				echo '<input type="hidden" name="userID" value="' . $_GET['userID'] . '" />';
				echo '<input type="submit" name="Delete" class="btnForm" value="Xóa" />';
				echo '</form>';
				echo '<form action="update_user.php" method="post">';
				echo '<input type="submit" name="Cancel" class="btnForm" value="Hũy bỏ" />';
				echo '</form>';
				echo '</div>';
				
				echo '<ul>';
				echo '<li>Người dùng thuộc nhóm:   (<a href="?action=roles&userID=' . $_GET['userID'] . '">Quản lý nhóm</a>)';
				echo '<ul>';
				$roles = $userAC->getUserRoles();
				
				// Hiển thị nhóm của người dùng đang thuộc về
				foreach ($roles as $k => $v) {
					echo '<li>' . $userAC->getRoleNameFromID($v) . '</li>';
				}
				
				echo '</ul>';
				echo '</li>';
				
				echo '<li>Người dùng có quyền:   (<a href="?action=perms&userID=' . $_GET['userID'] . '">Quản lý quyền</a>)';
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
				echo '</li>';
				echo '</ul>';
			} elseif ($_GET['action'] == 'roles') { // Quản lý nhóm người dùng của người dùng đã chọn
                echo '<h2>Quản lý nhóm người dùng: (<span class="username">' . $ac->getUsername($_GET['userID']) . '</span>)</h2>';
                echo '<form action="update_user.php" method="post">';
                echo '<table border="0" cellpadding="5" cellspacing="0">';
                echo '<tr><th></th><th>Thành viên</th><th>Không phải thành viên</th></tr>';
				
                $roleAC = new AccessControl($_GET['userID']);
                $roles = $roleAC->getAllRoles('full');
				
				// Biến chẳn lẽ dùng trang trí bảng
				$isEven = 0;
                foreach ($roles as $k => $v) {
					// Thêm class chẳn lẽ cho từng dòng
					$evenClass = ($isEven % 2 === 0) ? 'even' : 'odd';
                    echo '<tr class="' . $evenClass . '"><td class="rowtitle"><label>' . $v['Name'] . '</label></td>';
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
					
					// Tăng biến chẳn lẽ
					$isEven++;
                }
                
				// Form dùng cập nhật nhóm người dùng
                echo '</table>';
                echo '<input type="hidden" name="action" value="saveRoles" />';
                echo '<input type="hidden" name="userID" value="' . $_GET['userID'] . '" />';
                echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
                echo '</form>';
				
				// Thoát không cập nhật
                echo '<form action="update_user.php" method="post">';
                echo '<input type="button" name="Cancel" class="btnForm" onclick="window.location=' . "'?action=user&userID=" 
					. $_GET['userID'] ."'" . '" value="Hũy bỏ" />';
                echo '</form>';
			} elseif ($_GET['action'] == 'perms') { // Quản lý quyền của người dùng đã chọn
				echo '<h2>Quản lý quyền người dùng: (<span class="username">' . $ac->getUsername($_GET['userID']) . '</span>)</h2>';
				echo '<form action="update_user.php" method="post">';
				echo '<table border="0" cellpadding="5" cellspacing="0">';
				echo '<tr><th>Tên quyền</th><th>Quyền người dùng</th></tr>';
				
				$userAC = new AccessControl($_GET['userID']);
				$rPerms = $userAC->perms;
				$aPerms = $userAC->getAllPerms('full');
				
				// Biến chẳn lẽ dùng trang trí bảng
				$isEven = 0;
				foreach ($aPerms as $k => $v) {
					
					// Thêm class chẳn lẽ cho từng dòng
					$evenClass = ($isEven % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="' . $evenClass . '"><td class="rowtitle">' . $v['Name'] . '</td>';
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
					
					// Tăng biến chẳn lẽ
					$isEven++;
				}
				
				// Form dùng cập nhật quyền
				echo '</table>';
				echo '<input type="hidden" name="action" value="savePerms" />';
				echo '<input type="hidden" name="userID" value="'. $_GET['userID'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '</form>';
				
				// Form dùng thoát không cập nhật
				echo '<form action="update_user.php" method="post">';
				echo '<input type="button" name="Cancel" class="btnForm" onclick="window.location=' . "'?action=user&userID=" .
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
} else {
	// Chuyễn tới trang login với status=notlogin
	header("Location: login.php?status=notlogin");
}
?>