<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File autocomplte đường tỉnh
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;
$db->connect();
$result = $db->query("SELECT id_duong, ten FROM duong_bo WHERE id_loai=2 AND ten ILIKE '%" . $_GET['q'] . "%'");

while ($row = pg_fetch_object($result)) {
	echo $row->id_duong . ':' . $row->ten . "\n";
}