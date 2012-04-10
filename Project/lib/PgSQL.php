<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 */
require_once dirname(__FILE__) . '/../Config.php';
 
/**
 * Lớp truy xuất CSDL PostgreSQL
 */
class PgSQL {
	
	private $conn;
	private $host;
	private $user;
	private $pass;
	private $db;
	private $result;
	
	/**
	 * Phương thức khởi tạo
	 */
	function __construct() {
		$this->host = Config::$hostname;
		$this->db   = Config::$database;
		$this->user = Config::$username;
		$this->pass = Config::$password;
	}
	
	/**
	 * Phương thức gán giá trị cho $host, $db, $user và $pass
	 */
	function setConnectionInfo($host, $db, $user, $pass) {
		$this->host = $host;
		$this->db   = $db;
		$this->user = $user;
		$this->pass = $pass;
	}
	
	/**
	 * Phương thức kết nối CSDL
	 */
	function connect() {
		try {
			$this->conn = @pg_connect("host=$this->host dbname=$this->db user=$this->user password=$this->pass");
			if (!$this->conn) {
				throw new Exception('Khong the ket noi voi CSDL PostgreSQL');
			}
		} catch (Exception $ex) {
			die($ex->getMessage());
		}
	}
	
	/**
	 * Phương thức ngắt kết nối CSDL
	 */
	function disconnect() {
		pg_close($this->conn);
	}
	
	/**
	 * Phương thức truy vấn dữ liệu
	 */
	function query($query) {
		try {
			$this->result = @pg_query($this->conn, $query);
			if (!$this->result) {
				throw new Exception('<p>Khong the truy van CSDL</p><p>' . pg_last_error($this->conn) . '</p>');
			}
			return $this->result;
		} catch (Exception $ex) {
			die($ex->getMessage());
		}
	}
	
	/**
	 * Lấy kết quả truy vấn gần nhất
	 */
	function getLastResult() {
		return $this->result;
	}
	
	/**
	 * Trả về số dòng của kết quả gần nhất
	 */
	function numberRows() {
		return @pg_num_rows($this->result);
	}
	
	/**
	 * Trả về số cột của kết quả gần nhất
	 */
	function numberFields() {
		return @pg_num_fields($this->result);
	}
	
	/**
	 * Trả về số lượng record bị ảnh hưởng
	 */
	function affectedRows() {
		return @pg_affected_rows($this->result);
	}
	
	/**
	 * Lấy tên cột trong kết quả trả về
	 */
	function fieldName($index) {
		return @pg_field_name($this->result, $index);
	}
}//End class PgSQL