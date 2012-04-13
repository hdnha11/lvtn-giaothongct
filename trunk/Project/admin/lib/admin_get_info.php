<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File lấy thông tin đối tượng từ CSDL khi biết FID
 */
	
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

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
	$queryStr = "SELECT gid, duong, id_duong
				FROM {$table}
				WHERE gid = {$id}";
	
	//$pg = new PgSQL->setConnectionInfo('localhost', 'gth_cantho', 'postgres', 'postgres');
	// Khởi tạo đối tượng PgSQL
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	// Nếu có kết quả trả về sẽ trả về dạng HTML
	if ($pg->numberRows() != 0) {
		
		// Sinh mã HTML
		$html = '<form id="update_info" name="update_info" method="post" action="">
				<fieldset id="duong">
					<legend>Thông tin đường</legend>
					<div>
						<label for="nhan">Nhãn đường:</label>
						<input type="text" name="nhan" id="nhan" value="';
		
		while ($row = pg_fetch_object($result)) {
			$html .= $row->duong . '" />
						<a href="#" id="getTenDuong" class="functionLink">Lấy tên</a>
					</div>
					<div>
						<label for="duong">Thuộc đường:</label>
						<input type="text" name="duong" id="duong" value="';
			
			$tenDuong = '';
			if ($row->id_duong != '') {
				// Lấy tên đường
				$pg->connect();
				$kq = $pg->query("SELECT ten FROM duong_bo WHERE id_duong={$row->id_duong}");
				$duong = pg_fetch_object($kq);
				$tenDuong = $duong->ten;
			}
			
			$html .= $tenDuong . '" />
						<a href="#" id="deleteDuong" class="functionLink">Xóa</a>
						<input type="hidden" name="id_duong" id="id_duong" value="' . $row->id_duong . '" />
					</div>
				</fieldset>
				
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="id" value="' . $row->gid . '" />
				<input type="hidden" name="table" id="table" value="' . $table . '" />
				<input type="submit" name="Submit" class="btnForm" value="Cập nhật" onclick="return updateDuong();" />';
			
			// Nếu đã gán đường bộ cho đối tượng thì hiện Cập nhật chi tiết
			if ($row->id_duong != '') {	
				$html .= '<a id="editDetail" href="update_duong_bo.php?action=edit&id=' . $row->id_duong . '">Cập nhật chi tiết</a>';
			}
				
			$html .= '</form>';
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
	$queryStr = "SELECT ten, dia_chi, dien_thoai, so_dau_xe, thong_ben
				FROM {$table}
				WHERE gid = {$id}";
	
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	
	$html = '<form id="update_info" name="update_info" method="post" action="">
			<fieldset id="thongtinben">
				<legend>Thông tin bến</legend>
				<div>
					<label for="tenben">Bến xe</label>
					<input type="text" name="tenben" id="tenben" value="';
	
	while ($row = pg_fetch_object($result)) {
		$html .= $row->ten . '" />
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
	$queryStr = "SELECT dien_giai, dia_chi, di_va_den
				FROM {$table}
				WHERE gid = {$id}";
	
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
	
	
	$html = '<form id="update_info" name="update_info" method="post" action="">
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
	$queryStr = "SELECT c.ten AS ten_cau, loai, duong, chieu_dai, be_rong, tai_trong, mo_tru, so_nhip, su_dung, su_dung0, cq.ten, dia_chi
				FROM cau_polyline AS c inner join co_quan_quan_ly AS cq
				ON c.id_co_quan = cq.id_co_quan
				WHERE gid = {$id}";
	
	//$pg = new PgSQL->setConnectionInfo('localhost', 'gth_cantho', 'postgres', 'postgres');
	// Khởi tạo đối tượng PgSQL
	$pg = new PgSQL();
	$pg->connect();
	$result = $pg->query($queryStr);
		
	// Sinh mã HTML
	$html = '<form id="update_info" name="update_info" method="post" action="">
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
		$html .= $row->duong . '" />
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
			<fieldset id="coquan">
				<legend>Cơ quan quản lý</legend>
				<div>
					<label for="tencq">Tên cơ quan</label>
					<input type="text" name="tencq" id="tencq" value="';
		$html .= $row->ten . '" />
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

// Kiểm tra nếu tồn tại $table và $id sẽ trả về kết quả dưới dạng HTML
if (isset($table) && isset($id)) {
	//header('Content-type: text/plain'); // Cũng được nếu trả về dạng Plain Text
	echo getInfo($table, $id);
}