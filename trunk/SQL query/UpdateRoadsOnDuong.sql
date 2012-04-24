update roads_polyline set duong = (
	select duong from quoc_lo_polyline
	where roads_polyline.the_geom = quoc_lo_polyline.the_geom
);

update roads_polyline set duong = (
	select duong from tinh_lo_polyline
	where roads_polyline.the_geom = tinh_lo_polyline.the_geom
)
where roads_polyline.duong is null;