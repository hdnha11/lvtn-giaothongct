<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File tìm kiếm tên đối tượng trong CSDL và trả về JSON Text
 */
 
require_once dirname(__FILE__) . '/PgSQL.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';
$layers = isset($_GET['layers']) ? $_GET['layers'] : '';

switch ($layers) {
	case 'tinhLo':
		$queryStr = "SELECT db.ten, ST_AsText(tl.the_geom) AS wkt
					FROM tinh_lo_polyline tl
					INNER JOIN duong_bo db
					ON tl.id_duong = db.id_duong
					WHERE db.ten ILIKE '%{$query}%'";
		break;
	case 'quocLo':
		$queryStr = "SELECT db.ten, ST_AsText(ql.the_geom) AS wkt
					FROM quoc_lo_polyline ql
					INNER JOIN duong_bo db
					ON ql.id_duong = db.id_duong
					WHERE db.ten ILIKE '%{$query}%'";
		break;
	case 'cau':
		$queryStr = "SELECT ten, ST_AsText(the_geom) AS wkt
					FROM cau_polyline
					WHERE ten ILIKE '%{$query}%'";
		break;
	case 'benXe':
		$queryStr = "SELECT ten, ST_AsText(the_geom) AS wkt
					FROM ben_xe_font_point
					WHERE ten ILIKE '%{$query}%'";
		break;
	case 'benXeBuyt':
		$queryStr = "SELECT dien_giai AS ten, ST_AsText(the_geom) AS wkt
					FROM ben_xe_buyt_point
					WHERE dien_giai ILIKE '%{$query}%'";
		break;
	case 'all':
		$queryStr = "SELECT db.ten, ST_AsText(tl.the_geom) AS wkt
					FROM tinh_lo_polyline tl
					INNER JOIN duong_bo db
					ON tl.id_duong = db.id_duong
					WHERE db.ten ILIKE '%{$query}%'
					UNION ALL
					SELECT db.ten, ST_AsText(ql.the_geom) AS wkt
					FROM quoc_lo_polyline ql
					INNER JOIN duong_bo db
					ON ql.id_duong = db.id_duong
					WHERE db.ten ILIKE '%{$query}%'
					UNION ALL
					SELECT ten, ST_AsText(the_geom) AS wkt
					FROM cau_polyline
					WHERE ten ILIKE '%{$query}%'
					UNION ALL
					SELECT ten, ST_AsText(the_geom) AS wkt
					FROM ben_xe_font_point
					WHERE ten ILIKE '%{$query}%'
					UNION ALL
					SELECT dien_giai AS ten, ST_AsText(the_geom) AS wkt
					FROM ben_xe_buyt_point
					WHERE dien_giai ILIKE '%{$query}%'";
		break;
	default:
		$queryStr = '';
}

if ($queryStr != '') {
	// Khởi tạo đối tượng PgSQL
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	$json = '{"results":[';
	
	while ($row = pg_fetch_object($result)) {
		$json .= '{"geom":"' . $row->wkt . '", "name":"' . $row->ten . '"}, ';
	}
	
	// Bỏ hai ký tự cuối bị thừa
	$json = rtrim($json, ', ');
	
	$json .= ']}';
	
	echo $json;
}