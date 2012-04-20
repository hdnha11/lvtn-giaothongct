<?php
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;

if (isset($_POST['reportOption'])) {
	if ($_POST['reportOption'] === 'all') {
		$reportSQL = <<<SQL
			SELECT b.dien_giai AS ten_ben, d.ten AS ten_duong, b.dia_chi, b.di_va_den
			FROM ben_xe_buyt_point AS b
			INNER JOIN duong_bo AS d ON b.id_duong = d.id_duong
SQL;
		
		$reportContent = <<<TABLE
		<table>
			<caption>Danh sách bến xe buýt</caption>
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên bến</th>
					<th>Thuộc đường</th>
					<th>Địa chỉ</th>
					<th>Tuyến đi và đến</th>
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
			$reportContent .= "<td>$duong->ten_ben</td>";
			$reportContent .= "<td>$duong->ten_duong</td>";
			$reportContent .= "<td>$duong->dia_chi</td>";
			$reportContent .= "<td>$duong->di_va_den</td>";
			$reportContent .= '</tr>';
			
			$stt++;
		}
		$reportContent .= "			
			</tbody>
		</table>";

	} else {
		$reportSQL = <<<SQL
			SELECT b.dien_giai AS ten_ben, d.ten AS ten_duong, b.dia_chi, b.di_va_den
			FROM ben_xe_buyt_point AS b
			INNER JOIN duong_bo AS d ON b.id_duong = d.id_duong
			WHERE d.id_duong = {$_POST['reportOption']}
SQL;
		
		$reportContent = <<<TABLE
		<table>
			<caption>Danh sách bến xe buýt</caption>
			<thead>
				<tr>
					<th>STT</th>
					<th>Tên bến</th>
					<th>Thuộc đường</th>
					<th>Địa chỉ</th>
					<th>Tuyến đi và đến</th>
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
			$reportContent .= "<td>$duong->ten_ben</td>";
			$reportContent .= "<td>$duong->ten_duong</td>";
			$reportContent .= "<td>$duong->dia_chi</td>";
			$reportContent .= "<td>$duong->di_va_den</td>";
			$reportContent .= '</tr>';
			
			$stt++;
		}
		$reportContent .= "
			</tbody>
		</table>";
			
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Báo cáo hiện trạng bến xe buýt</title>
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
	<p>BÁO CÁO HIỆN TRẠNG BẾN XE BUÝT</p>
    <p>NĂM <?php echo date('Y'); ?></p>
    <?php
		$subTitle = '';
		if ($_POST['reportOption'] != 'all') {
			$db->connect();
			$dsDuong = $db->query("SELECT ten FROM duong_bo WHERE id_duong = {$_POST['reportOption']}");
			$duong = pg_fetch_object($dsDuong);
			$subTitle = '<p class="italic">Trên tuyến đường ' . $duong->ten . '</p>';
		}
		echo $subTitle;
	?>
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