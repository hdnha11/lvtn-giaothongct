<?php
	
	require_once(dirname(__FILE__) . '/classes/PgSQL.php');
	
	if (isset($_GET['fid'])) {
		$fid = $_GET['fid'];
		$splited = preg_split("/\./", $fid);
		//print_r($splited);
		$table = $splited[0];
		$id = $splited[1];
	}
	
	function getInfo($table, $id) {
		switch ($table) {
			case 'tinh_lo_polyline':
				return getFromTinhLo($table, $id);
				break;
			case 'quoc_lo_polyline':
				return getFromQuocLo($table, $id);
				break;
			case 'ben_xe_font_point':
				return getFromBenXe($table, $id);
				break;
		}
	}
	
	function getFromTinhLo($table, $id) {
		$queryStr = "SELECT d.ten as ten_duong, d.diem_dau, d.diem_cuoi, d.tong_so_cau, l.loai as loai_duong, c.cap as cap_duong
							, cq.ten as co_quan_quan_ly, cq.dia_chi as dia_chi_co_quan, d.tinh_trang_su_dung
					FROM {$table} t, duong_bo d, cap_duong c, co_quan_quan_ly cq, loai_duong l
					WHERE t.gid = {$id}
						  AND t.id_duong = d.id_duong
						  AND d.id_cap = c.id_cap
						  AND d.id_co_quan = cq.id_co_quan
						  AND d.id_loai = l.id_loai";
		
		$pg = new PgSQL('localhost', 'gth_cantho', 'postgres', 'postgres');
		$pg->connect();
		$result = $pg->query($queryStr);
		
		
		$html = '<form id="tinhlo" name="tinhlo" method="post" action="">
				<fieldset id="duong">
					<legend>Thông tin đường</legend>
					<div>
						<label for="tenduong">Đường</label>
						<input type="text" name="tenduong" id="tenduong" value="';
		
		while ($row = pg_fetch_object($result)) {
			$html .= $row->ten_duong . '" />
					</div>
					<div>
						<label for="diemdau">Điểm đầu</label>
						<input type="text" name="diemdau" id="diemdau" value="';
			$html .= $row->diem_dau . '" />
					</div>
					<div>
						<label for="diemcuoi">Điểm cuối</label>
						<input type="text" name="diemcuoi" id="diemcuoi" value="';
			$html .= $row->diem_cuoi . '" />
					</div>
					<div>
						<label for="tscau">Tổng số cầu</label>
						<input type="text" name="tscau" id="tscau" value="';
			$html .= $row->tong_so_cau . '" />
					</div>
					<div>
						<label for="loai">Loại</label>
						<input type="text" name="loai" id="loai" value="';
			$html .= $row->loai_duong . '" />
					</div>
					<div>
						<label for="cap">Cấp</label>
						<input type="text" name="cap" id="cap" value="';
			$html .= $row->cap_duong . '" />
					</div>
					<div>
						<label for="tinhtrang">Tình trạng sử dụng</label>
						<textarea name="tinhtrang" rows="4" id="tinhtrang">';
			$html .= $row->tinh_trang_su_dung . '</textarea>
					</div>
				</fieldset>
				<fieldset id="coquan">
					<legend>Cơ quan quản lý</legend>
					<div>
						<label for="tencq">Tên cơ quan</label>
						<input type="text" name="tencq" id="tencq" value="';
			$html .= $row->co_quan_quan_ly . '" />
					</div>
					<div>
						<label for="diachi">Địa chỉ</label>
						<textarea name="diachi" rows="4" id="diachi">';
			$html .= $row->dia_chi_co_quan . '</textarea>
					</div>
				</fieldset>
			</form>';
		}
		
		return $html;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test</title>
<link rel="stylesheet" type="text/css" href="css/infobox.css" />
</head>

<body>
	<?php
		if (isset($table) && isset($id)) {
			echo getInfo($table, $id);
		}
	?>
</body>
</html>