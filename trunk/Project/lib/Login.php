<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 */
 
require_once dirname(__FILE__) . '/PgSQL.php';

class Login {
	
	static function performLogin($username, $password, $issetcookie) {
		
		// To protect MySQL injection
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = pg_escape_string($username);
		$password = pg_escape_string($password);
		$password = md5($password);
		
		$sql = "SELECT * FROM users WHERE username='$username' and password='$password'";
		$db = new PgSQL();
		$db->connect();
		$result = $db->query($sql);
		
		$count = $db->numberRows();
		
		if ($count == 1) {
			$row = pg_fetch_object($result);
			$_SESSION['userID'] = $row->id;
			
			// Nếu người dùng chọn lưu thông tin
			if ($issetcookie) {
				// Lưu cookie 1 tháng
				$expire = time() + 60 * 60 * 24 * 30;
				setcookie('userIDGisCT', $row->id, $expire);
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	static function isLoggedIn() {
		// Nếu không tồn tại cookie thì xét session
		if (!isset($_COOKIE['userIDGisCT'])) {
			if (isset($_SESSION['userID'])) {
				return true;
			} else {
				return false;
			}
		} else { // Ngược lại return true và set session = cookie
			$_SESSION['userID'] = $_COOKIE['userIDGisCT'];
			return true;
		}
	}
	
	static function logout() {
		unset($_SESSION['userID']);
		
		// Hủy cookie
		setcookie('userIDGisCT', '', time() - 3600);
	}
}