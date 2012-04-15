<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File lấy đường giao với cầu
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;
$db->connect();
$strSql = <<<SQL
SELECT b.gid, b.id_duong
FROM cau_polyline AS a, quoc_lo_polyline AS b
WHERE a.gid = {$_GET['id']} AND
      st_intersects(a.the_geom, b.the_geom) = TRUE
UNION
SELECT b.gid, b.id_duong
FROM cau_polyline AS a, tinh_lo_polyline AS b
WHERE a.gid = {$_GET['id']} AND
      st_intersects(a.the_geom, b.the_geom) = TRUE
SQL;
$result = $db->query($strSql);

if ($row = pg_fetch_object($result)) {
	if ($row->id_duong != '') {
		$query = "SELECT id_duong, ten FROM duong_bo WHERE id_duong = {$row->id_duong}";
		$db->connect();
		$duong = $db->query($query);
		$duong = pg_fetch_object($duong);
		echo $duong->id_duong . ':' . $duong->ten;
	}
}