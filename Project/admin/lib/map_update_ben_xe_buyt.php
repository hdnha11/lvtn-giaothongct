<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File cập nhật dữ liệu bến xe buýt trên bản đồ
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

if (isset($_POST['action'])) {
	$db = new PgSQL();
	
	if ($_POST['id_duong'] != '') {
		$sqlStr = sprintf("UPDATE %s SET dien_giai='%s', dia_chi='%s', di_va_den='%s', id_duong=%u WHERE gid=%u",
						  $_POST['table'], $_POST['diengiai'], $_POST['diachi'], $_POST['divaden'], $_POST['id_duong'],
						  $_POST['id']);
	} else {
		$sqlStr = sprintf("UPDATE %s SET dien_giai='%s', dia_chi='%s', di_va_den='%s', id_duong=NULL WHERE gid=%u",
						  $_POST['table'], $_POST['diengiai'], $_POST['diachi'], $_POST['divaden'], $_POST['id']);
	}
	
	$db->connect();
	$db->query($sqlStr);
	
	echo 'Cập nhật thành công!';
}