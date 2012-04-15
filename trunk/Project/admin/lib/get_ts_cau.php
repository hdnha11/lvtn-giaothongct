<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File tổng số cầu của đường
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;
$db->connect();
$strSql = <<<SQL
SELECT count(gid) AS tscau
FROM cau_polyline AS c
JOIN duong_bo AS d
ON c.id_duong = d.id_duong
WHERE c.id_duong = {$_GET['id_duong']}
SQL;
$result = $db->query($strSql);
$tscau = pg_fetch_object($result);
$tscau = $tscau->tscau;
echo $tscau;