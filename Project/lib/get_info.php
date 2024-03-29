<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File lấy thông tin đối tượng từ CSDL khi biết FID
 */
	
require_once dirname(__FILE__) . '/PgSQL.php';

if (isset($_GET['fid'])) {
	$fid = $_GET['fid'];
	$splited = preg_split("/\./", $fid);
	$table = $splited[0];
	$id = $splited[1];
}

/**
 * Hàm lấy thông tin
 */
function getInfo($table, $id) {
	switch ($table) {
		case 'tinh_lo_polyline':
		case 'quoc_lo_polyline':
			return getDuong($table, $id);
			break;
		case 'ben_xe_font_point':
			return getBenXe($table, $id);
			break;
		case 'ben_xe_buyt_point':
			return getBenXeBuyt($table, $id);
			break;
		case 'cau_polyline':
			return getCau($table, $id);
			break;
	}
}

/**
 * Hàm lấy thông tin đường bộ
 */
function getDuong($table, $id) {
	$queryStr = "SELECT d.ten as ten_duong, d.diem_dau, d.diem_cuoi, d.tong_so_cau, l.loai as loai_duong, c.cap as cap_duong
						, cq.ten as co_quan_quan_ly, cq.dia_chi as dia_chi_co_quan, d.tinh_trang_su_dung
				FROM {$table} t, duong_bo d, cap_duong c, co_quan_quan_ly cq, loai_duong l
				WHERE t.gid = {$id}
					  AND t.id_duong = d.id_duong
					  AND d.id_cap = c.id_cap
					  AND d.id_co_quan = cq.id_co_quan
					  AND d.id_loai = l.id_loai";
	
	//$pg = new PgSQL->setConnectionInfo('localhost', 'gth_cantho', 'postgres', 'postgres');
	// Khởi tạo đối tượng PgSQL
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	// Nếu có kết quả trả về sẽ trả về dạng HTML
	if ($pg->numberRows() != 0) {
		
		// Sinh mã HTML
		$html = '<form id="duonglo" name="duonglo" method="post" action="">
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
	} else {
		$html = "<p>Không tìm thấy thông tin về đường này</p>";
	}
	
	return $html;
}

/**
 * Hàm lấy thông tin bến xe
 */
function getBenXe($table, $id) {
	$queryStr = "SELECT b.ten AS ten_ben, dia_chi, dien_thoai, so_dau_xe, thong_ben, d.ten AS thuoc_duong
				FROM {$table} AS b
				LEFT JOIN duong_bo AS d
				ON b.id_duong = d.id_duong
				WHERE gid = {$id}";
	
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	
	$html = '<form id="benxe" name="benxe" method="post" action="">
			<fieldset id="thongtinben">
				<legend>Thông tin bến</legend>
				<div>
					<label for="tenben">Bến xe</label>
					<input type="text" name="tenben" id="tenben" value="';
	
	while ($row = pg_fetch_object($result)) {
		$html .= $row->ten_ben . '" />
				</div>
				<div>
					<label for="dienthoai">Số điện thoại</label>
					<input type="text" name="dienthoai" id="dienthoai" value="';
		$html .= $row->dien_thoai . '" />
				</div>
				<div>
					<label for="sodauxe">Số đầu xe</label>
					<input type="text" name="sodauxe" id="sodauxe" value="';
		$html .= $row->so_dau_xe . '" />
				</div>
				<div>
					<label for="thongben">Thông bến</label>
					<input type="text" name="thongben" id="thongben" value="';
		$html .= $row->thong_ben . '" />
				</div>
				<div>
					<label for="thuocDuong">Thuộc đường</label>
					<input type="text" name="thuocDuong" id="thuocDuong" value="';
		$html .= $row->thuoc_duong . '" />
				</div>
				<div>
					<label for="diachi">Địa chỉ</label>
					<textarea name="diachi" rows="4" id="diachi">';
		$html .= $row->dia_chi . '</textarea>
				</div>
			</fieldset>
		</form>';
	}
	
	return $html;
}

/**
 * Hàm lấy thông tin bến xe buýt
 */
function getBenXeBuyt($table, $id) {
	$queryStr = "SELECT dien_giai, dia_chi, di_va_den, d.ten AS thuoc_duong
				FROM {$table} AS b
				LEFT JOIN duong_bo AS d
				ON b.id_duong = d.id_duong
				WHERE gid = {$id}";
	
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	
	$html = '<form id="benxebuyt" name="benxebuyt" method="post" action="">
			<fieldset id="thongtinben">
				<legend>Thông tin bến</legend>
				<div>
					<label for="diengiai">Diễn giải</label>
					<input type="text" name="diengiai" id="diengiai" value="';
	
	while ($row = pg_fetch_object($result)) {
		$html .= $row->dien_giai . '" />
				</div>
				<div>
					<label for="divaden">Tuyến đi và đến</label>
					<input type="text" name="divaden" id="divaden" value="';
		$html .= $row->di_va_den . '" />
				</div>
				<div>
					<label for="thuocDuong">Thuộc đường</label>
					<input type="text" name="thuocDuong" id="thuocDuong" value="';
		$html .= $row->thuoc_duong . '" />
				</div>
				<div>
					<label for="diachi">Địa chỉ</label>
					<textarea name="diachi" rows="4" id="diachi">';
		$html .= $row->dia_chi . '</textarea>
				</div>
			</fieldset>
		</form>';
	}
	
	return $html;
}

/**
 * Hàm lấy thông tin cầu
 */
function getCau($table, $id) {
	$queryStr = "SELECT c.ten AS ten_cau, d.ten AS thuoc_duong, loai, chieu_dai::numeric(38, 2), be_rong::numeric(38, 2),
						tai_trong::numeric(38, 2), mo_tru, so_nhip::numeric(38, 2), su_dung, su_dung0
				FROM {$table} AS c
				LEFT JOIN duong_bo AS d
				ON c.id_duong = d.id_duong
				WHERE gid = {$id}";
	
	//$pg = new PgSQL->setConnectionInfo('localhost', 'gth_cantho', 'postgres', 'postgres');
	// Khởi tạo đối tượng PgSQL
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	// Nếu có kết quả trả về sẽ trả về dạng HTML
	if ($pg->numberRows() != 0) {
		// Sinh mã HTML
		$html = '<form id="cau" name="cau" method="post" action="">
				<fieldset id="thongtincau">
					<legend>Thông tin cầu</legend>
					<div>
						<label for="tencau">Cầu</label>
						<input type="text" name="tencau" id="tencau" value="';
		
		while ($row = pg_fetch_object($result)) {
			$html .= $row->ten_cau . '" />
					</div>
					<div>
						<label for="loai">Loại</label>
						<input type="text" name="loai" id="loai" value="';
			$html .= $row->loai . '" />
					</div>
					<div>
						<label for="thuocduong">Thuộc đường</label>
						<input type="text" name="thuocduong" id="thuocduong" value="';
			$html .= $row->thuoc_duong . '" />
					</div>
					<div>
						<label for="chieudai">Chiều dài (m)</label>
						<input type="text" name="chieudai" id="chieudai" value="';
			$html .= $row->chieu_dai . '" />
					</div>
					<div>
						<label for="berong">Bề rộng (m)</label>
						<input type="text" name="berong" id="berong" value="';
			$html .= $row->be_rong . '" />
					</div>
					<div>
						<label for="taitrong">Tải trọng</label>
						<input type="text" name="taitrong" id="taitrong" value="';
			$html .= $row->tai_trong . '" />
					</div>
					<div>
						<label for="motru">Mô trụ</label>
						<input type="text" name="motru" id="motru" value="';
			$html .= $row->mo_tru . '" />
					</div>
					<div>
						<label for="sonhip">Số nhịp</label>
						<input type="text" name="sonhip" id="sonhip" value="';
			$html .= $row->so_nhip . '" />
					</div>
					<div>
						<label for="tinhtrang">Tình trạng sử dụng</label>
						<textarea name="tinhtrang" rows="4" id="tinhtrang">';
			$html .= $row->su_dung . '</textarea>
					</div>
					<div>
						<label for="tinhchat">Tính chất sử dụng</label>
						<textarea name="tinhchat" rows="4" id="tinhchat">';
			$html .= $row->su_dung0 . '</textarea>
					</div>
				</fieldset>
			</form>';
		}
	} else {
		$html = "<p>Không tìm thấy thông tin về cầu này</p>";
	}
	
	return $html;
}

// Kiểm tra nếu tồn tại $table và $id sẽ trả về kết quả dưới dạng HTML
if (isset($table) && isset($id)) {
	//header('Content-type: text/plain'); // Cũng được nếu trả về dạng Plain Text
	echo getInfo($table, $id);
}