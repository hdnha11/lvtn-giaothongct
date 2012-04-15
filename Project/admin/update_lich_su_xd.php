<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';
require_once dirname(__FILE__) . '/../lib/Paging.php';

if (Login::isLoggedIn()) {
	
	$ac     = new AccessControl();
	$db     = new PgSQL();
	$paging = new Paging('update_lich_su_xd.php?', 10);
	
	if ($ac->hasPermission('cap_nhat_du_lieu') != true) {
		header("refresh:5;url=index.php");
		// Hiển thị thông báo truy cập trái phép
		include dirname(__FILE__) . '/includes/message.html';
	} else {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'insert':
					$strSQL = sprintf("");
					$db->connect();
					$db->query($strSQL);
					break;
				case 'edit':
					$strSQL = sprintf("");
					$db->connect();
					$db->query($strSQL);
					break;
				case 'delete':
					$ids = isset($_POST['idLS']) ? $_POST['idLS'] : array();
					$strSQL = "DELETE FROM lich_su_xay_dung WHERE id_lich_su IN (";
					for ($i =  0; $i < count($ids); $i++) {
						if ($i === (count($ids) - 1)) {
							$strSQL .= $ids[$i] . ")";
						} else {
							$strSQL .= $ids[$i] . ", ";
						}
					}
					
					if (count($ids) > 0) {						
						$db->connect();
						$db->query($strSQL);
					}
					
					break;
			}
		}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản lý lịch sử xây dựng</title>
<link type="text/css" rel="stylesheet" href="css/admin.css" />
<link type="text/css" rel="stylesheet" href="css/lich-su-xd.css" />
<script type="text/javascript" src="../js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	// Mở menu tương ứng với trang, ẩn các menu khác
    $("#sidebar h3#updateDate").addClass("active");
	$("#sidebar div:not(#updateDateCT)").hide();
	
	// Search form
	$("#queryStr").focus(function() {
		if ($("#queryStr").val() === 'Tìm kiếm') {
			$("#queryStr").val('');
		}
	});
	
	$("#queryStr").blur(function() {
		if ($("#queryStr").val() === '') {
			$("#queryStr").val('Tìm kiếm');
		}
	});
});

var check = function(list) {
	if (document.dsLS.checkAll.checked === true) {
		for (i = 0; i < list.length; i++) {
			list[i].checked = true ;
		}
	} else {	
		for (i = 0; i < list.length; i++) {
			list[i].checked = false ;
		}
	}
};
</script>
</head>

<body>
<div id="container">
    <!-- Header include -->
    <?php include_once("includes/header.html"); ?>
	<div id="wrapper">
    	<!-- SideBar include -->
        <?php include_once("includes/sidebar.html"); ?>
		<!-- Content -->
        <div id="updateLichSuXDContent">
            <h1 class="contentTitle">Cập nhật lịch sử xây dựng</h1>
            <div id="content">
            <?php
			if (!isset($_GET['action'])) { // Hiện danh sách lịch sử xây dựng
				
				// Form tìm kiếm
				echo '<form name="searchLS" id="searchLS" method="get" action="update_lich_su_xd.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="Tìm kiếm" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_lich_su_xd.php?action=addnew">Thêm mới lịch sử xây dựng</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsLS'". '].submit();">Xóa lịch sử xây dựng đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị lịch sử xây dựng
            	echo '<form name="dsLS" id="dsLS" method="post" action="update_lich_su_xd.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;				
				$str = "SELECT id_lich_su, chieu_dai, rong_nen, rong_mat, chieu_dai_rai_nhua, quy_mo, tai_trong,
							   noi_dung_xay_dung, tong_kinh_phi, ngay_hoan_thanh, d.ten AS ten_duong
						FROM lich_su_xay_dung AS l
						INNER JOIN duong_bo AS d
						ON l.id_duong = d.id_duong";
				$paging->getNav($page, $str, 4);
				
				// Danh sách lịch sử xây dựng phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsLS[\'idLS[]\']);" /></th>';
				echo '<th>Chiều dài (km)</th><th>Rộng nền (m)</th><th>Rộng mặt (m)</th><th>Chiều dài rải nhựa (km)</th><th>Quy mô</th>';
				echo '<th>Tải trọng (tấn)</th><th>Nội dung</th><th>Tổng kinh phí (tỉ đồng)</th><th>Ngày hoàn thành</th>';
				echo '<th>Của đường</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idLS[]" id="idLS[]" value="' . $row->id_lich_su . '" /></td>';
					echo '<td>' . $row->chieu_dai . '</td>';
					echo '<td>' . $row->rong_nen . '</td>';
					echo '<td>' . $row->rong_mat . '</td>';
					echo '<td>' . $row->chieu_dai_rai_nhua . '</td>';
					echo '<td>' . $row->quy_mo . '</td>';
					echo '<td>' . $row->tai_trong . '</td>';
					echo '<td>' . $row->noi_dung_xay_dung . '</td>';
					echo '<td>' . $row->tong_kinh_phi . '</td>';
					echo '<td>' . $row->ngay_hoan_thanh . '</td>';
					echo '<td>' . $row->ten_duong . '</td>';
					echo '<td><a href="update_lich_su_xd.php?action=edit&id=' . $row->id_lich_su . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'search') {
				// Form tìm kiếm
				echo '<form name="searchLS" id="searchLS" method="get" action="update_lich_su_xd.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="' . $_GET['queryStr'] . '" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_lich_su_xd.php?action=addnew">Thêm mới lịch sử xây dựng</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsLS'". '].submit();">Xóa lịch sử xây dựng đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị lịch sử xây dựng
            	echo '<form name="dsLS" id="dsLS" method="post" action="update_lich_su_xd.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;				
				$str = "SELECT id_lich_su, chieu_dai, rong_nen, rong_mat, chieu_dai_rai_nhua, quy_mo, tai_trong,
							   noi_dung_xay_dung, tong_kinh_phi, ngay_hoan_thanh, d.ten AS ten_duong
						FROM lich_su_xay_dung AS l
						INNER JOIN duong_bo AS d
						ON l.id_duong = d.id_duong
						WHERE d.ten ILIKE '%" . $_GET['queryStr'] . "%'";
				// Fixed Paging when searching
				$paging = new Paging('update_lich_su_xd.php?action=search&queryStr=' . $_GET['queryStr'] . '&', 10);
				$paging->getNav($page, $str, 4);
				
				// Danh sách lịch sử xây dựng phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsLS[\'idLS[]\']);" /></th>';
				echo '<th>Chiều dài (km)</th><th>Rộng nền (m)</th><th>Rộng mặt (m)</th><th>Chiều dài rải nhựa (km)</th><th>Quy mô</th>';
				echo '<th>Tải trọng (tấn)</th><th>Nội dung</th><th>Tổng kinh phí (tỉ đồng)</th><th>Ngày hoàn thành</th>';
				echo '<th>Của đường</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idLS[]" id="idLS[]" value="' . $row->id_lich_su . '" /></td>';
					echo '<td>' . $row->chieu_dai . '</td>';
					echo '<td>' . $row->rong_nen . '</td>';
					echo '<td>' . $row->rong_mat . '</td>';
					echo '<td>' . $row->chieu_dai_rai_nhua . '</td>';
					echo '<td>' . $row->quy_mo . '</td>';
					echo '<td>' . $row->tai_trong . '</td>';
					echo '<td>' . $row->noi_dung_xay_dung . '</td>';
					echo '<td>' . $row->tong_kinh_phi . '</td>';
					echo '<td>' . $row->ngay_hoan_thanh . '</td>';
					echo '<td>' . $row->ten_duong . '</td>';
					echo '<td><a href="update_lich_su_xd.php?action=edit&id=' . $row->id_lich_su . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'addnew') { // Thêm lịch sử xây dựng
				echo '<form name="addNew" id="addNew" action="update_lich_su_xd.php" method="post">';
				
				echo '<p><label for="chieuDai">Chiều dài:</label><input type="text" name="chieuDai" id="chieuDai" /></p>';
				
				echo '<input type="hidden" name="action" value="insert" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Thêm mới" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_lich_su_xd.php\'" />';
				echo '</form>';
			} elseif ($_GET['action'] === 'edit') { // Cập nhật lịch sử xây dựng
				$strSQL = sprintf("SELECT chieu_dai, rong_nen, rong_mat, chieu_dai_rai_nhua, quy_mo, tai_trong,
									      noi_dung_xay_dung, tong_kinh_phi, ngay_hoan_thanh, l.id_duong, d.ten AS ten_duong
								   FROM lich_su_xay_dung AS l
								   INNER JOIN duong_bo AS d
								   ON l.id_duong = d.id_duong
								   WHERE id_lich_su=%u", $_GET['id']);
				$db->connect();
				$result = $db->query($strSQL);
				$row = pg_fetch_object($result);
				echo '<form name="edit" id="edit" action="update_lich_su_xd.php" method="post">';
				
				echo '<p><label for="chieuDai">Chiều dài:</label>';
				echo '<input type="text" name="chieuDai" id="chieuDai" value="' . $row->chieu_dai . '" /></p>';
				
				echo '<input type="hidden" name="action" value="edit" />';
				echo '<input type="hidden" name="id" value="' . $_GET['id'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_lich_su_xd.php\'" />';
			}
			?>
            </div>
        </div><!--End updateLichSuXDContent-->
	</div><!--End Wrapper-->
	
</div><!--End Container-->
</body>
</html>

<?php
	}
} else {
	// Chuyễn tới trang login với status=notlogin
	header("Location: login.php?status=notlogin");
}
?>