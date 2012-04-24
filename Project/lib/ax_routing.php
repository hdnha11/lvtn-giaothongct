<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File tìm đường đi ngắn nhất
 */
	
require_once dirname(__FILE__) . '/PgSQL.php';

define("TABLE", "roads_polyline"); 

$db = new PgSQL();
$counter = $pathlength = 0;

// Retrieve start point
$start = preg_split('/ /', $_REQUEST['startpoint']);
$startPoint = array($start[0], $start[1]);

// Retrieve end point
$end = preg_split('/ /', $_REQUEST['finalpoint']);
$endPoint = array($end[0], $end[1]);

// Find the nearest edge
$startEdge = findNearestEdge($startPoint);
$endEdge   = findNearestEdge($endPoint);

// FUNCTION findNearestEdge
function findNearestEdge($lonlat) {
	
	// Connect to database
	$db = new PgSQL();
	$db->connect();
	
	$sql = "SELECT gid, source, target, the_geom, 
			 st_distance(the_geom, st_geometryfromtext(
				  'POINT(" . $lonlat[0] . " " . $lonlat[1] . ")', 4326)) AS dist 
			FROM " . TABLE . "  
			WHERE the_geom && st_setsrid(
				  'BOX3D(".($lonlat[0]-200)." 
						 ".($lonlat[1]-200).", 
						 ".($lonlat[0]+200)." 
						 ".($lonlat[1]+200).")'::box3d, 4326) 
			ORDER BY dist LIMIT 1";
	
	$query = $db->query($sql);
	
	$edge['gid']      = pg_fetch_result($query, 0, 0);
	$edge['source']   = pg_fetch_result($query, 0, 1);
	$edge['target']   = pg_fetch_result($query, 0, 2);
	$edge['the_geom'] = pg_fetch_result($query, 0, 3);
	
	// Close database connection
	$db->disconnect();
	
	return $edge;
}

// Function findNearestPoint
function findNearestPoint($point, $line) {
	
	// Connect to database
	$db = new PgSQL();
	$db->connect();
	
	$sql = "SELECT id, st_distance(the_geom, st_pointfromtext('POINT({$point[0]} {$point[1]})', 4326)) AS distance
			FROM vertices_tmp
			WHERE id IN ({$line['source']}, {$line['target']})
			ORDER BY distance";
	
	$result = $db->query($sql);
	
	$row = pg_fetch_object($result);
	
	return $row->id;
}

// Get source and target node
$source = findNearestPoint($startPoint, $startEdge);
$target = findNearestPoint($endPoint, $endEdge);

// Select the routing algorithm	Dijkstra	
$sql = "SELECT rt.gid, st_astext(rt.the_geom) AS wkt, 
		   st_length(rt.the_geom) AS length
		FROM " . TABLE . ",
			(SELECT gid, the_geom 
				FROM dijkstra_sp_delta(
					'" . TABLE . "',
					" . $source . ",
					" . $target . ",
					3000)
			 ) as rt
		WHERE " . TABLE . ".gid = rt.gid;";

// Database connection and query
$db->connect();

$query = $db->query($sql);

// Return route as XML
$xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'."\n";
$xml .= "<route>\n";

// Add edges to XML file
while($edge = pg_fetch_object($query)) {  

	$pathlength += $edge->length;
	
	$xml .= "\t<edge id='" . ++$counter . "'>\n";
	$xml .= "\t\t<wkt>" . $edge->wkt . "</wkt>\n";
	$xml .= "\t\t<length>" . round(($pathlength / 1000), 3) . "</length>\n";
	$xml .= "\t</edge>\n";
}

$xml .= "</route>\n";
	
// Close database connection
$db->disconnect();

// Return routing result
header('Content-type: text/xml', true);
echo $xml;