--Truoc khi chay lenh nay can remove enforce_srid_the_geom CHECK (st_srid(the_geom) = (-1)) tren bang
--Sau khi chay xong add lai check enforce_srid_the_geom CHECK (st_srid(the_geom) = 4326)
--Vao bang geometry_columns sua lai cot srid thanh 4326
UPDATE table_name SET the_geom = ST_SetSRID(the_geom, 4326);