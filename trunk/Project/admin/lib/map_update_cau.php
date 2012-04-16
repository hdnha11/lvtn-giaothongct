<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File cập nhật dữ liệu cầu trên bản đồ
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

if (isset($_POST['action'])) {
	$db = new PgSQL();
	
	if ($_POST['id_duong'] != '') {
		$sqlStr = sprintf("UPDATE %s SET ten='%s', loai='%s', chieu_dai=%.3f, be_rong=%.3f, tai_trong=%.3f, mo_tru='%s',
										 so_nhip=%.3f, su_dung='%s', su_dung0='%s', id_duong=%u WHERE gid=%u",
						  $_POST['table'], $_POST['tencau'], $_POST['loai'], $_POST['chieudai'], $_POST['berong'],
						  $_POST['taitrong'], $_POST['motru'], $_POST['sonhip'], $_POST['tinhtrang'], $_POST['tinhchat'],
						  $_POST['id_duong'], $_POST['id']);
	} else {
		$sqlStr = sprintf("UPDATE %s SET ten='%s', loai='%s', chieu_dai=%.3f, be_rong=%.3f, tai_trong=%.3f, mo_tru='%s',
										 so_nhip=%.3f, su_dung='%s', su_dung0='%s', id_duong=NULL WHERE gid=%u",
						  $_POST['table'], $_POST['tencau'], $_POST['loai'], $_POST['chieudai'], $_POST['berong'],
						  $_POST['taitrong'], $_POST['motru'], $_POST['sonhip'], $_POST['tinhtrang'], $_POST['tinhchat'],
						  $_POST['id']);
	}
	
	$db->connect();
	$db->query($sqlStr);
	
	echo 'Cập nhật thành công!';
}