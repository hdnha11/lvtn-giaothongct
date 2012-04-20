--bao cao hien trang tat ca duong bo
SELECT d.ten, ls.chieu_dai, ls.rong_nen, ls.rong_mat, ls.quy_mo, ls.tai_trong, l.loai, c.cap, d.tinh_trang_su_dung
FROM duong_bo AS d
INNER JOIN (
		SELECT *
		FROM lich_su_xay_dung
		WHERE (id_duong, ngay_hoan_thanh) IN (
			SELECT id_duong, max(ngay_hoan_thanh) AS ngay_hoan_thanh
			FROM lich_su_xay_dung
			GROUP BY id_duong
		)
	   ) AS ls
ON d.id_duong = ls.id_duong
INNER JOIN loai_duong AS l
ON d.id_loai = l.id_loai
INNER JOIN cap_duong AS c
ON d.id_cap = c.id_cap;

--bao cao hien trang theo co quan quan ly
SELECT d.ten, ls.chieu_dai, ls.rong_nen, ls.rong_mat, ls.quy_mo, ls.tai_trong, l.loai, c.cap, d.tinh_trang_su_dung
FROM duong_bo AS d
INNER JOIN (
		SELECT *
		FROM lich_su_xay_dung
		WHERE (id_duong, ngay_hoan_thanh) IN (
			SELECT id_duong, max(ngay_hoan_thanh) AS ngay_hoan_thanh
			FROM lich_su_xay_dung
			GROUP BY id_duong
		)
	   ) AS ls
ON d.id_duong = ls.id_duong
INNER JOIN loai_duong AS l
ON d.id_loai = l.id_loai
INNER JOIN cap_duong AS c
ON d.id_cap = c.id_cap
INNER JOIN co_quan_quan_ly AS cq
ON d.id_co_quan = cq.id_co_quan
WHERE d.id_co_quan = 2;

--lay ve lich su xay dung moi nhat cua duong
SELECT *
FROM lich_su_xay_dung
WHERE (id_duong, ngay_hoan_thanh) IN (
	SELECT id_duong, max(ngay_hoan_thanh) AS ngay_hoan_thanh
	FROM lich_su_xay_dung
	GROUP BY id_duong
);

SELECT id_duong, max(ngay_hoan_thanh) AS ngay_hoan_thanh
FROM lich_su_xay_dung
GROUP BY id_duong;

SELECT id_lich_su, id_duong, ngay_hoan_thanh
FROM lich_su_xay_dung;

--bao cao thong ke xay moi, duy tu, sua chua nang cap theo qui va nam
SELECT d.ten, l.loai, c.cap, ls.noi_dung_xay_dung, ls.tong_kinh_phi, d.tinh_trang_su_dung
FROM duong_bo AS d
INNER JOIN lich_su_xay_dung AS ls ON d.id_duong = ls.id_duong
INNER JOIN loai_duong AS l ON d.id_loai = l.id_loai
INNER JOIN cap_duong AS c ON d.id_cap = c.id_cap
WHERE extract(QUARTER FROM ls.ngay_hoan_thanh) = 2 AND
      extract(YEAR FROM ls.ngay_hoan_thanh) = 2010;

--lay qui va thang tu kieu date
SELECT ls.id_duong, extract(QUARTER FROM ls.ngay_hoan_thanh) AS qui, extract(MONTH FROM ls.ngay_hoan_thanh) AS thang
FROM lich_su_xay_dung AS ls; 

--bao cao hien trang cau
SELECT c.ten AS ten_cau, d.ten AS ten_duong, c.chieu_dai, c.be_rong, c.tai_trong, c.mo_tru, c.so_nhip, c.loai, c.su_dung
FROM cau_polyline AS c
INNER JOIN duong_bo AS d ON c.id_duong = d.id_duong
WHERE d.id_duong = 1;

--bao cao hien trang ben xe
SELECT b.ten AS ten_ben, d.ten AS ten_duong, b.dia_chi, b.dien_thoai, b.so_dau_xe, b.thong_ben
FROM ben_xe_font_point AS b
INNER JOIN duong_bo AS d ON b.id_duong = d.id_duong;

--bao cao hien trang ben xe buyt
SELECT b.dien_giai AS ten_ben, d.ten AS ten_duong, b.dia_chi, b.di_va_den
FROM ben_xe_buyt_point AS b
INNER JOIN duong_bo AS d ON b.id_duong = d.id_duong;