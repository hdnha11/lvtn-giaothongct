<?php
session_start();
require_once dirname(__FILE__) . '/../lib/Login.php';

if (Login::isLoggedIn()) {
	
	// Nếu đã đăng nhập chuyễn tới trang index
	header('Location: index.php');
		
} else {
	
	// Nếu form login được submit
	if (isset($_POST['submit'])) {
		if (isset($_POST['user']) && isset($_POST['pwd'])) {
			
			// Kiểm tra xem có chọn lưu thông tin
			$issaveinfo = isset($_POST['rememberme']) ? true : false;
			
			if (Login::performLogin($_POST['user'], $_POST['pwd'], $issaveinfo)) {
			
				// Load lại trang
				header('Location: login.php');
			} else {
				
				// Load lại trang với trạng thái thất bại
				header('Location: login.php?status=failed');
			}
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Đăng nhập hệ thống</title>
<link type="text/css" rel="stylesheet" href="css/login-box.css" />
<link type="text/css" rel="stylesheet" href="css/message-box.css" />
</head>

<body>
	
    <?php
		// Kiểm tra khi trang được gọi kèm biến $_GET['status'] để hiện thông báo
		if (isset($_GET['status'])) {
			if ($_GET['status'] === 'failed') {
				include dirname(__FILE__) . '/includes/login-failed.html';
			} elseif ($_GET['status'] === 'notlogin') {
				include dirname(__FILE__) . '/includes/not-login.html';
			}
		}
	?>
    
    <form id="login" name="login" action="login.php" method="post">
    	<H2>Đăng nhập</H2>
		Hệ thống thông tin địa lý phục vụ quản lý giao thông bộ TP. Cần Thơ
        <p class="first">
        	<label for="user">Người dùng:</label><input type="text" name="user" id="user" value="" size="23" />
        </p>
        <p>
        	<label for="pwd">Mật khẩu:</label><input type="password" name="pwd" id="pwd" size="23" />
		</p>
        <p>
            <input name="rememberme" id="rememberme" type="checkbox" checked="checked" />
            <label class="checkbox" for="rememberme">Lưu thông tin</label>
		</p>
        <p class="submit">
        	<input type="submit" name="submit" value="Đăng nhập" />
        </p>
    </form>

</body>
</html>
<?php
}
?>