select d.ten as ten_duong, d.diem_dau, d.diem_cuoi, d.tong_so_cau, l.loai as loai_duong, c.cap as cap_duong, cq.ten as co_quan_quan_ly, cq.dia_chi as dia_chi_co_quan, d.tinh_trang_su_dung
from tinh_lo_polyline t, duong_bo d, cap_duong c, co_quan_quan_ly cq, loai_duong l
where t.gid = '1'
      and t.id_duong = d.id_duong
      and d.id_cap = c.id_cap
      and d.id_co_quan = cq.id_co_quan
      and d.id_loai = l.id_loai;