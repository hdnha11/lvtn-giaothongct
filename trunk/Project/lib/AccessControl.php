<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 */
 
require_once dirname(__FILE__) . '/PgSQL.php';

/**
 * Lớp kiểm soát quyền người dùng
 */
class AccessControl {
	
	public $perms = array();		//Array : Stores the permissions for the user
	public $userID = 0;			//Integer : Stores the ID of the current user
	public $userRoles = array();	//Array : Stores the roles of the current user
	private $db;
	
	function __constructor($userID = '') {
		if ($userID != '') {
			$this->userID = floatval($userID);
		} else {
			$this->userID = isset($_SESSION['userID']) ? floatval($_SESSION['userID']) : '';
		}
		$this->db = new PgSQL();
		$this->db->connect();
		$this->userRoles = $this->getUserRoles();
		$this->buildAC();
	}
	
	function AccessControl($userID = '') {
		$this->__constructor($userID);
		//crutch for PHP4 setups
	}
	
	function buildAC() {
		//first, get the rules for the user's role
		if (count($this->userRoles) > 0) {
			$this->perms = array_merge($this->perms, $this->getRolePerms($this->userRoles));
		}
		//then, get the individual user permissions, if has the same key will get later value
		$this->perms = array_merge($this->perms, $this->getUserPerms($this->userID));
	}
	
	/**
	 * Hàm lấy khóa của quyền từ id
	 */
	function getPermKeyFromID($permID) {
		$strSQL = "SELECT permkey FROM permissions WHERE id = " . floatval($permID) . " LIMIT 1";
		$data = $this->db->query($strSQL);
		$row = pg_fetch_array($data);
		return $row[0];
	}
	
	/**
	 * Hàm lấy tên của quyền từ id
	 */
	function getPermNameFromID($permID) {
		$strSQL = "SELECT permname FROM permissions WHERE id = " . floatval($permID) . " LIMIT 1";
		$data = $this->db->query($strSQL);
		$row = pg_fetch_array($data);
		return $row[0];
	}
	
	/**
	 * Hàm lấy tên vai trò từ id
	 */
	function getRoleNameFromID($roleID) {
		$strSQL = "SELECT rolename FROM roles WHERE id = " . floatval($roleID) . " LIMIT 1";
		$data = $this->db->query($strSQL);
		$row = pg_fetch_array($data);
		return $row[0];
	}
	
	/**
	 * Hàm lấy vai trò của user hiện tại, trả về mãng id của vai trò
	 */
	function getUserRoles() {
		$strSQL = "SELECT * FROM user_roles WHERE userid = " . floatval($this->userID) . " ORDER BY adddate ASC";
		$data = $this->db->query($strSQL);
		$resp = array();
		while ($row = pg_fetch_array($data)) {
			$resp[] = $row['roleid'];
		}
		return $resp;
	}
	
	/**
	 * Hàm lấy tất cả các vai trò
	 * Truyền tham số ids sẽ nhận về mãng id của vai trò
	 * Truyền tham số full sẽ nhận về mãng đầy đủ
	 */
	function getAllRoles($format='ids') {
		$format = strtolower($format);
		$strSQL = "SELECT * FROM roles ORDER BY rolename ASC";
		$data = $this->db->query($strSQL);
		$resp = array();
		while ($row = pg_fetch_array($data)) {
			if ($format == 'full') {
				$resp[] = array("ID" => $row['id'], "Name" => $row['rolename']);
			} else {
				$resp[] = $row['id'];
			}
		}
		return $resp;
	}
	
	/**
	 * Hàm lấy tất cả các quyền
	 * Truyền tham số ids sẽ nhận về mãng id của quyền
	 * Truyền tham số full sẽ nhận về mãng đầy đủ
	 */
	function getAllPerms($format = 'ids') {
		$format = strtolower($format);
		$strSQL = "SELECT * FROM permissions ORDER BY permname ASC";
		$data = $this->db->query($strSQL);
		$resp = array();
		while ($row = pg_fetch_assoc($data)) {
			if ($format == 'full') {
				$resp[$row['permkey']] = array('ID' => $row['id'], 'Name' => $row['permname'], 'Key' => $row['permkey']);
			} else {
				$resp[] = $row['id'];
			}
		}
		return $resp;
	}
	
	/**
	 * Hàm lấy các quyền của vai trò truyền vào
	 */
	function getRolePerms($role) {
		if (is_array($role)) {
			$roleSQL = "SELECT * FROM role_perms WHERE roleid IN (" . implode(",", $role) . ") ORDER BY id ASC";
		} else {
			$roleSQL = "SELECT * FROM role_perms WHERE roleid = " . floatval($role) . " ORDER BY id ASC";
		}
		$data = $this->db->query($roleSQL);
		$perms = array();
		while ($row = pg_fetch_assoc($data)) {
			$pK = strtolower($this->getPermKeyFromID($row['permid']));
			if ($pK == '') {
				continue;
			}
			if ($row['value'] == 't' || $row['value'] == 'true' || $row['value'] == '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK, 'inheritted' => true, 'value' => $hP,
								'Name' => $this->getPermNameFromID($row['permid']), 'ID' => $row['permid']);
		}
		return $perms;
	}
	
	/**
	 * Hàm lấy quyền của user riêng lẽ (nếu muốn biết user có quyền gì ngoài thừa hưởng từ vai trò)
	 */
	function getUserPerms($userID) {
		$strSQL = "SELECT * FROM user_perms WHERE userid = " . floatval($userID) . " ORDER BY adddate ASC";
		$data = $this->db->query($strSQL);
		$perms = array();
		while ($row = pg_fetch_assoc($data)) {
			$pK = strtolower($this->getPermKeyFromID($row['permid']));
			if ($pK == '') {
				continue;
			}
			if ($row['value'] == 't' || $row['value'] == 'true' || $row['value'] == '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK, 'inheritted' => false, 'value' => $hP,
								'Name' => $this->getPermNameFromID($row['permid']),'ID' => $row['permid']);
		}
		return $perms;
	}
	
	/**
	 * Hàm kiểm tra xem user có vai trò nào đó hay không
	 */
	function userHasRole($roleID) {
		foreach ($this->userRoles as $k => $v) {
			if (floatval($v) === floatval($roleID)) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Hàm kiểm tra xem user có quyền nào đó hay không
	 */
	function hasPermission($permKey) {
		$permKey = strtolower($permKey);
		if (array_key_exists($permKey, $this->perms)) {
			if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * Hàm lấy tên của user có id truyền vào
	 */
	function getUsername($userID) {
		$strSQL = "SELECT username FROM users WHERE id = " . floatval($userID) . " LIMIT 1";
		$data = $this->db->query($strSQL);
		$row = pg_fetch_array($data);
		return $row[0];
	}
}