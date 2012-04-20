<?php
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;

if (isset($_POST['btnReport'])) {
	$reportSQL = <<<SQL
		SELECT d.ten, l.loai, c.cap, ls.noi_dung_xay_dung, ls.tong_kinh_phi, d.tinh_trang_su_dung
		FROM duong_bo AS d
		INNER JOIN lich_su_xay_dung AS ls ON d.id_duong = ls.id_duong
		INNER JOIN loai_duong AS l ON d.id_loai = l.id_loai
		INNER JOIN cap_duong AS c ON d.id_cap = c.id_cap
		WHERE extract(QUARTER FROM ls.ngay_hoan_thanh) = {$_POST['quarter']} AND
			  extract(YEAR FROM ls.ngay_hoan_thanh) = {$_POST['year']}
SQL;
	
	$reportContent = <<<TABLE
	<table>
		<caption>Danh sách đường</caption>
		<thead>
			<tr>
				<th>STT</th>
				<th>Tên đường</th>
				<th>Loại đường</th>
				<th>Cấp đường</th>
				<th>Nội dung</th>
				<th>Kinh phí</th>
				<th>Tình trạng sử dụng</th>
			</tr>
		</thead>
		<tbody>
TABLE;

	$db->connect();
	$dsDuong = $db->query($reportSQL);
	$stt = 1;
	while ($duong = pg_fetch_object($dsDuong)) {
		$reportContent .= '<tr>';
		$reportContent .= "<td>$stt</td>";
		$reportContent .= "<td>$duong->ten</td>";
		$reportContent .= "<td>$duong->loai</td>";
		$reportContent .= "<td>$duong->cap</td>";
		$reportContent .= "<td>$duong->noi_dung_xay_dung</td>";
		$reportContent .= "<td>". number_format($duong->tong_kinh_phi * 1000000000, 3, '.', ','). "đ</td>";
		$reportContent .= "<td>$duong->tinh_trang_su_dung</td>";
		$reportContent .= '</tr>';
		
		$stt++;
	}
	$reportContent .= "			
		</tbody>
	</table>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Báo cáo xây mới, duy tu, sửa chửa và nâng cấp đường bộ</title>
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
	<p>BÁO CÁO THỐNG KÊ XÂY MỚI, DUY TU SỬA CHỬA VÀ NÂNG CẤP CHO HỆ THỐNG GIAO THÔNG</p>
    <p>QUÍ <?php echo $_POST['quarter']; ?> NĂM <?php echo $_POST['year']; ?></p>
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