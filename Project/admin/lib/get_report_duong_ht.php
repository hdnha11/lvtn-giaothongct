<?php
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;

if (isset($_POST['reportOption'])) {
	if ($_POST['reportOption'] === 'cqql') {
		$reportSQL = <<<SQL
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
			WHERE d.id_co_quan = 
SQL;
		$cqqlSQL = "SELECT id_co_quan, ten FROM co_quan_quan_ly WHERE id_co_quan != 0";
		$db->connect();
		$cqql = $db->query($cqqlSQL);
		$reportContent = '';
		$sttCQ = 1;
		while ($cq = pg_fetch_object($cqql)) {
			//$reportContent .= '<p class="rtContentTitle">' . $sttCQ . '. ' . $cq->ten . '</p>';
			$reportContent .= <<<TABLE
			<table>
				<caption>{$sttCQ}. {$cq->ten}</caption>
				<thead>
					<tr>
						<th>STT</th>
						<th>Tên đường</th>
						<th>Chiều dài</th>
						<th>Rộng nền</th>
						<th>Rộng mặt</th>
						<th>Quy mô</th>
						<th>Tải trọng</th>
						<th>Loại đường</th>
						<th>Cấp đường</th>
						<th>Tình trạng sử dụng</th>
					</tr>
				</thead>
				<tbody>
TABLE;
			$dsDuong = $db->query($reportSQL . $cq->id_co_quan);
			$stt = 1;
			while ($duong = pg_fetch_object($dsDuong)) {
				$reportContent .= '<tr>';
				$reportContent .= "<td>$stt</td>";
				$reportContent .= "<td>$duong->ten</td>";
				$reportContent .= "<td>$duong->chieu_dai</td>";
				$reportContent .= "<td>$duong->rong_nen</td>";
				$reportContent .= "<td>$duong->rong_mat</td>";
				$reportContent .= "<td>$duong->quy_mo</td>";
				$reportContent .= "<td>$duong->tai_trong</td>";
				$reportContent .= "<td>$duong->loai</td>";
				$reportContent .= "<td>$duong->cap</td>";
				$reportContent .= "<td>$duong->tinh_trang_su_dung</td>";
				$reportContent .= '</tr>';
				
				$stt++;
			}
			$reportContent .= "			
				</tbody>
			</table>";
			
			$sttCQ++;
		}
	} elseif ($_POST['reportOption'] === 'loaiDuong') {
		$reportSQL = <<<SQL
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
			WHERE d.id_loai = 
SQL;
		$loaiSQL = "SELECT id_loai, loai FROM loai_duong WHERE id_loai != 0";
		$db->connect();
		$dsLoai = $db->query($loaiSQL);
		$reportContent = '';
		$sttLoai = 1;
		while ($loai = pg_fetch_object($dsLoai)) {
			//$reportContent .= '<p class="rtContentTitle">' . $sttLoai . '. ' . $loai->loai . '</p>';
			$reportContent .= <<<TABLE
			<table>
				<caption>{$sttLoai}. {$loai->loai}</caption>
				<thead>
					<tr>
						<th>STT</th>
						<th>Tên đường</th>
						<th>Chiều dài</th>
						<th>Rộng nền</th>
						<th>Rộng mặt</th>
						<th>Quy mô</th>
						<th>Tải trọng</th>
						<th>Loại đường</th>
						<th>Cấp đường</th>
						<th>Tình trạng sử dụng</th>
					</tr>
				</thead>
				<tbody>
TABLE;
			$dsDuong = $db->query($reportSQL . $loai->id_loai);
			$stt = 1;
			while ($duong = pg_fetch_object($dsDuong)) {
				$reportContent .= '<tr>';
				$reportContent .= "<td>$stt</td>";
				$reportContent .= "<td>$duong->ten</td>";
				$reportContent .= "<td>$duong->chieu_dai</td>";
				$reportContent .= "<td>$duong->rong_nen</td>";
				$reportContent .= "<td>$duong->rong_mat</td>";
				$reportContent .= "<td>$duong->quy_mo</td>";
				$reportContent .= "<td>$duong->tai_trong</td>";
				$reportContent .= "<td>$duong->loai</td>";
				$reportContent .= "<td>$duong->cap</td>";
				$reportContent .= "<td>$duong->tinh_trang_su_dung</td>";
				$reportContent .= '</tr>';
				
				$stt++;
			}
			$reportContent .= "
				</tbody>
			</table>";
			
			$sttLoai++;
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Báo cáo hiện trạng đường bộ</title>
<link type="text/css" rel="stylesheet" href="../css/report-table.css" />
<style type="text/css">
body {
	width: 1024px;
	margin: 20px auto;
}

/* In đậm, in nghiêng */
.bold {
	font-weight: bold;
}

.italic {
	font-style: italic;
}

/* Đầu trang: Tên cơ quan, tiêu ngữ */
#header p {
	margin: 0px;
	font-weight: bold;
	font-size: 16px;
}

#left {
	float: left;
	width: 40%;
	text-align: left;
}

#right {
	float: right;
	width: 60%;	
	text-align: center;
}

/* Phần ngày tháng */
#date {
	clear: both;
	text-align: right;
}

#date p {	
	margin: 0px 40px 40px 0px;
	padding-top: 30px;
}

/* Phần tiêu đề */
#title {
	font-size: 20px;
	text-align: center;
	font-weight: bold;
}

#title p {
	margin: 0px;
}

/* Phần ký tên */
#sign {
	margin: 40px;
}

#sign p {
	text-align: right;
	margin: 0px;
}

/* Định kích thước bảng */
table {
	width: 1024px;
}

/* Khoảng cách trên tiêu đề bảng */
caption {
	margin: 20px 0px 0px 0px;
}
</style>
</head>

<body>
<div id="header">
    <div id="left">
    	<p>BỘ GIAO THÔNG VẬN TẢI</p>
        <p>Sở GTVT Tp. Cần Thơ</p>
    </div>
    <div id="right">
    	<p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p>Độc lập - Tự do - Hạnh phúc</p>
    </div>
</div>
<div id="date">
	<p class="italic">Cần Thơ, ngày <?php echo date('d'); ?> tháng <?php echo date('m'); ?> năm <?php echo date('Y'); ?></p>
</div>
<div id="title">
	<p>BÁO CÁO HIỆN TRẠNG GIAO THÔNG BỘ</p>
    <p>NĂM <?php echo date('Y'); ?></p>
</div>
<?php
// Nội dung báo cáo
echo $reportContent;
?>
<div id="sign">
	<p class="bold">Thủ trưởng đơn vị</p>
    <p class="italic">(Ký tên, đóng dấu)</p>
<div>
</body>
</html>
<?php
} else {
	echo 'Khong cho phep truy cap truc tiep';
}
?>