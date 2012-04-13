SELECT b.gid, b.duong, st_distance(a.the_geom, b.the_geom) AS khoang_cach
FROM ben_xe_font_point AS a, quoc_lo_polyline AS b
WHERE a.ten = 'Bến xe QL. 91B'
ORDER BY khoang_cach;

SELECT b.gid, b.duong
FROM ben_xe_font_point AS a, quoc_lo_polyline AS b
WHERE a.ten = 'Bến xe QL. 91B' AND
      st_distance(a.the_geom, b.the_geom) = (
		SELECT min(st_distance(a.the_geom, b.the_geom))
		FROM ben_xe_font_point AS a, quoc_lo_polyline AS b
      );

SELECT b.gid, b.duong
FROM cau_polyline AS a, quoc_lo_polyline AS b
WHERE a.ten = 'Cầu Cái Răng' AND
      st_intersects(a.the_geom, b.the_geom) = TRUE;