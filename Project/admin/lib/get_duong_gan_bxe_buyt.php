<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File lấy đường gần bến xe nhất
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;
$db->connect();
$strSql = <<<SQL
SELECT b.gid, b.id_duong, st_distance(a.the_geom, b.the_geom) AS khoang_cach
FROM ben_xe_buyt_point AS a, quoc_lo_polyline AS b
WHERE a.gid = {$_GET['id']} AND
      st_distance(a.the_geom, b.the_geom) = (
		SELECT min(st_distance(a.the_geom, b.the_geom))
		FROM ben_xe_buyt_point AS a, quoc_lo_polyline AS b
		WHERE a.gid = {$_GET['id']} AND
			  b.id_duong IS NOT NULL
      )
UNION
SELECT b.gid, b.id_duong, st_distance(a.the_geom, b.the_geom) AS khoang_cach
FROM ben_xe_buyt_point AS a, tinh_lo_polyline AS b
WHERE a.gid = {$_GET['id']} AND
      st_distance(a.the_geom, b.the_geom) = (
		SELECT min(st_distance(a.the_geom, b.the_geom))
		FROM ben_xe_buyt_point AS a, tinh_lo_polyline AS b
		WHERE a.gid = {$_GET['id']} AND
			  b.id_duong IS NOT NULL
      )
ORDER BY khoang_cach
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