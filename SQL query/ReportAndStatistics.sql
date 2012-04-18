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