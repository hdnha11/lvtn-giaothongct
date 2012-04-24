--Tạo các cột source, target, và length cho table routing
ALTER TABLE roads_polyline ADD COLUMN source integer;
ALTER TABLE roads_polyline ADD COLUMN target integer;
ALTER TABLE roads_polyline ADD COLUMN length double precision;

--Tạo network topology cho table routing:
SELECT assign_vertex_id('roads_polyline', 0.001, 'the_geom', 'gid');
--Kết quả là một đồ thị được tạo ra với các nút mạng được lưu trong bảng vertices_tmp, liên
--hệ giữa các cạnh với các nút mạng được định nghĩa trong source, target của table routing.

--Tính toán trọng số của cạnh bằng chính độ dài thực tế của các cạnh.
UPDATE roads_polyline SET length = st_length(the_geom);

--Tạo chỉ mục cho source, target và geometry column để tăng tốc độ tìm kiếm cho tập dữ liệu lớn. 
CREATE INDEX source_idx ON roads_polyline(source);
CREATE INDEX target_idx ON roads_polyline(target);
CREATE INDEX geom_idx ON roads_polyline USING GIST(the_geom GIST_GEOMETRY_OPS);