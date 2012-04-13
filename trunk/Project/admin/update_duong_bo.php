<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';
require_once dirname(__FILE__) . '/../lib/Paging.php';

if (Login::isLoggedIn()) {

	$ac     = new AccessControl();
	$db     = new PgSQL();
	$paging = new Paging('update_duong_bo.php', 10);
	
	if ($ac->hasPermission('cap_nhat_du_lieu') != true) {
		header("refresh:5;url=index.php");
		// Hiển thị thông báo truy cập trái phép
		include dirname(__FILE__) . '/includes/message.html';
	} else {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'insert':
					$strSQL = sprintf("INSERT INTO duong_bo(ten, diem_dau, diem_cuoi, tong_so_cau,
															tinh_trang_su_dung, id_loai, id_cap, id_co_quan)
									   VALUES ('%s', '%s', '%s', %u, '%s', %u, %u, %u)",
									  $_POST['tenDuong'], $_POST['diemDau'], $_POST['diemCuoi'], $_POST['tsCau'],
									  $_POST['tinhTrang'], $_POST['loaiDuong'], $_POST['capDuong'], $_POST['coQuan']);
					$db->connect();
					$db->query($strSQL);
					break;
				case 'edit':
					$strSQL = sprintf("UPDATE duong_bo SET ten='%s', diem_dau='%s', diem_cuoi='%s', tong_so_cau=%u,
											  tinh_trang_su_dung='%s', id_loai=%u, id_cap=%u, id_co_quan=%u
									   WHERE id_duong=%u",
									   $_POST['tenDuong'], $_POST['diemDau'], $_POST['diemCuoi'], $_POST['tsCau'],
									   $_POST['tinhTrang'], $_POST['loaiDuong'], $_POST['capDuong'], $_POST['coQuan'],
									   $_POST['id']);
					$db->connect();
					$db->query($strSQL);
					break;
				case 'delete':
					$ids = isset($_POST['idDuong']) ? $_POST['idDuong'] : array();
					$strSQL = "DELETE FROM duong_bo WHERE id_duong IN (";
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
<title>Quản lý đường bộ</title>
<link type="text/css" rel="stylesheet" href="css/admin.css" />
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
	if (document.dsDuong.checkAll.checked === true) {
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

<style type="text/css">
#updateDuongContent {
	float: left;
	width: 732px;
	min-height: 82%;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
	background: #F7F7F7; /*Test*/
}

#updateDuongContent #content {
	position: relative;
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

/* Định dạng bảng */
#updateDuongContent table {
	/*font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;*/
	width: 692px;
	margin: 0px auto;
	text-align: left;
	border-collapse: collapse;
}

#updateDuongContent table th {
	font-size: 14px;
	font-weight: bold;
	padding: 10px 8px;
	color: #039;
	background: #8AB1FE;
	border-bottom: 2px solid #3A7ABE;
}

#updateDuongContent table td {
	padding: 8px;
	color: #669;
}

#updateDuongContent table td.rowtitle {
	text-align: left;
}

#updateDuongContent table .even {
	background: #e8edff; 
}

/* Định dạng thanh điều hướng trang */
ul.pageNav {
	height: 20px;
	overflow: hidden;
	padding: 0px;
	margin: 12px 0px 5px 0px;
}

ul.pageNav li {
	display: inline;
	float: left;
	margin: 0px 5px 0px 0px;
	background: #8AB1FE;
}

ul.pageNav li a {
	color: white;
	text-decoration: none;
	display: block;
	padding: 3px 8px;
}

ul.pageNav li:hover {
	background: #3A7ABE;
}

ul.pageNav li.current {
	font-weight: bold;
	padding: 3px 8px;
}

ul.pageNav li.disable {
	font-weight: bold;
	padding: 3px 8px;
}

ul.pageNav li.disable:hover {
	background: #8AB1FE;
}

ul.pageNav li.current:hover {
	background: #8AB1FE;
}

/* Định dạng form search */
form#searchDuong {
	width: 256px;
	position: absolute;
	top: 52px;
	right: 20px;
}

form#searchDuong input#queryStr {
	width: 230px;
	height: 18px;
	padding: 0px 0px 0px 24px;
	border: 1px solid #BFBFBF;
	background: url(images/search.png) 4px 2px no-repeat;
}

/* Định dạng header-panel */
div#header-panel {
	margin: 0px;
	padding: 0px;
}

div#header-panel ul {
	list-style-type: none;
	margin: 14px 2px 2px 2px;
	padding: 0px;
}

div#header-panel ul li {
	height: 18px;
	line-height: 18px;
}

div#header-panel ul a {
	color: #0076A1;
	text-decoration: none;
}

div#header-panel ul a.add-link {
	background: transparent url(images/add.png) scroll no-repeat left center;
	padding: 2px 0px 2px 20px;
}

div#header-panel ul a.remove-link {
	background: transparent url(images/delete.png) scroll no-repeat left center;
	padding: 2px 0px 2px 20px;
}

/* Định dạng các nút bấm */
form input.btnForm {
	cursor: pointer;
	width: 80px;
	height: 29px;
	line-height: 25px;
	font-size: 12px;
	font-weight: bold;
	color: #fff;
	background: #3c85fe;
	border: 1px solid #3079ED;
	margin: 5px 5px 0px 0px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
}

/* Định dạng label */
form label {
	display: block;
	width: 110px;
	height: 22px;
	line-height: 22px;
	float: left;
	color: #039;
}
</style>
</head>

<body>
<div id="container">
    <!-- Header include -->
    <?php include_once("includes/header.html"); ?>
	<div id="wrapper">
    	<!-- SideBar include -->
        <?php include_once("includes/sidebar.html"); ?>
		<!-- Content -->
        <div id="updateDuongContent">
            <h1 class="contentTitle">Cập nhật đường bộ</h1>
            <div id="content">
            <?php
			if (!isset($_GET['action'])) { // Hiện danh sách đường
				
				// Form tìm kiếm
				echo '<form name="searchDuong" id="searchDuong" method="get" action="update_duong_bo.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="Tìm kiếm" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_duong_bo.php?action=addnew">Thêm đường bộ</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsDuong'". '].submit();">Xóa đường bộ đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị đường
            	echo '<form name="dsDuong" id="dsDuong" method="post" action="update_duong_bo.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$str = "SELECT d.id_duong, d.ten AS ten_duong, cq.ten AS co_quan, d.diem_dau, d.diem_cuoi,
							   d.tong_so_cau, c.cap, l.loai, d.tinh_trang_su_dung
						FROM duong_bo AS d
						INNER JOIN cap_duong AS c ON d.id_cap = c.id_cap
						INNER JOIN loai_duong AS l ON d.id_loai = l.id_loai
						INNER JOIN co_quan_quan_ly AS cq ON d.id_co_quan = cq.id_co_quan";
				$paging->getNav($page, $str);
				
				// Danh sách đường phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsDuong[\'idDuong[]\']);" /></th>';
				echo '<th>Tên đường</th><th>Cơ quan quản lý</th><th>Điểm đầu</th><th>Điểm cuối</th><th>Tổng số cầu</th>
					  <th>Cấp</th><th>Loại</th><th>Tình trạng sử dụng</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idDuong[]" id="idDuong[]" value="' . $row->id_duong . '" /></td>';
					echo '<td>' . $row->ten_duong . '</td>';
					echo '<td>' . $row->co_quan . '</td>';
					echo '<td>' . $row->diem_dau . '</td>';
					echo '<td>' . $row->diem_cuoi . '</td>';
					echo '<td>' . $row->tong_so_cau . '</td>';
					echo '<td>' . $row->cap . '</td>';
					echo '<td>' . $row->loai . '</td>';
					echo '<td>' . $row->tinh_trang_su_dung . '</td>';
					echo '<td><a href="update_duong_bo.php?action=edit&id=' . $row->id_duong . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'search') {
				
				// Form tìm kiếm
				echo '<form name="searchDuong" id="searchDuong" method="get" action="update_duong_bo.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="' . $_GET['queryStr'] . '" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_duong_bo.php?action=addnew">Thêm đường bộ</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsDuong'". '].submit();">Xóa đường bộ đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị đường
            	echo '<form name="dsDuong" id="dsDuong" method="post" action="update_duong_bo.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$str = "SELECT d.id_duong, d.ten AS ten_duong, cq.ten AS co_quan, d.diem_dau, d.diem_cuoi,
							   d.tong_so_cau, c.cap, l.loai, d.tinh_trang_su_dung
						FROM duong_bo AS d
						INNER JOIN cap_duong AS c ON d.id_cap = c.id_cap
						INNER JOIN loai_duong AS l ON d.id_loai = l.id_loai
						INNER JOIN co_quan_quan_ly AS cq ON d.id_co_quan = cq.id_co_quan
						WHERE d.ten ILIKE '%" . $_GET['queryStr'] . "%'";
				$paging->getNav($page, $str);
				
				// Danh sách đường phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsDuong[\'idDuong[]\']);" /></th>';
				echo '<th>Tên đường</th><th>Cơ quan quản lý</th><th>Điểm đầu</th><th>Điểm cuối</th><th>Tổng số cầu</th>
					  <th>Cấp</th><th>Loại</th><th>Tình trạng sử dụng</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idDuong[]" id="idDuong[]" value="' . $row->id_duong . '" /></td>';
					echo '<td>' . $row->ten_duong . '</td>';
					echo '<td>' . $row->co_quan . '</td>';
					echo '<td>' . $row->diem_dau . '</td>';
					echo '<td>' . $row->diem_cuoi . '</td>';
					echo '<td>' . $row->tong_so_cau . '</td>';
					echo '<td>' . $row->cap . '</td>';
					echo '<td>' . $row->loai . '</td>';
					echo '<td>' . $row->tinh_trang_su_dung . '</td>';
					echo '<td><a href="update_duong_bo.php?action=edit&id=' . $row->id_duong . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'addnew') { // Thêm đường
				echo '<form name="addNew" id="addNew" action="update_duong_bo.php" method="post">';
				echo '<p><label for="tenDuong">Tên đường:</label><input type="text" name="tenDuong" id="tenDuong" /></p>';
				
				// Lấy thông tin cơ quan quản lý
				$db->connect();
				$coquan = $db->query("SELECT id_co_quan, ten FROM co_quan_quan_ly");
				
				echo '<p><label for="coQuan">Cơ quan quản lý:</label><select name="coQuan" id="coQuan">';
				while ($row = pg_fetch_object($coquan)) {
					if (intval($row->id_co_quan) === 0) {
						echo '<option value="' . $row->id_co_quan . '" selected="selected">' . $row->ten . '</option>';
					} else {
						echo '<option value="' . $row->id_co_quan . '">' . $row->ten . '</option>';
					}
				}				
				echo '</select></p>';
				
				echo '<p><label for="diemDau">Điểm đầu:</label><textarea name="diemDau" id="diemDau" rows="3"></textarea></p>';
				echo '<p><label for="diemCuoi">Điểm cuối:</label><textarea name="diemCuoi" id="diemCuoi" rows="3"></textarea></p>';
				echo '<p><label for="tsCau">Tổng số cầu:</label><input type="text" name="tsCau" id="tsCau" /></p>';
				
				// Lấy thông tin cấp đường
				$db->connect();
				$cap = $db->query("SELECT id_cap, cap FROM cap_duong");
				
				echo '<p><label for="capDuong">Cấp đường:</label><select name="capDuong" id="capDuong">';
				while ($row = pg_fetch_object($cap)) {
					if (intval($row->id_cap) === 0) {
						echo '<option value="' . $row->id_cap . '" selected="selected">' . $row->cap . '</option>';
					} else {
						echo '<option value="' . $row->id_cap . '">' . $row->cap . '</option>';
					}
				}				
				echo '</select></p>';
				
				// Lấy thông tin loại đường
				$db->connect();
				$loai = $db->query("SELECT id_loai, loai FROM loai_duong");
				
				echo '<p><label for="loaiDuong">Loại đường:</label><select name="loaiDuong" id="loaiDuong">';
				while ($row = pg_fetch_object($loai)) {
					if (intval($row->id_loai) === 0) {
						echo '<option value="' . $row->id_loai . '" selected="selected">' . $row->loai . '</option>';
					} else {
						echo '<option value="' . $row->id_loai . '">' . $row->loai . '</option>';
					}
				}				
				echo '</select></p>';
				
				echo '<p><label for="tinhTrang">Tình trạng sử dụng:</label><textarea name="tinhTrang" id="tinhTrang" rows="4"></textarea></p>';
				echo '<input type="hidden" name="action" value="insert" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Thêm mới" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_duong_bo.php\'" />';
				echo '</form>';
			} elseif ($_GET['action'] === 'edit') { // Cập nhật đường
				$strSQL = sprintf("SELECT id_duong, ten, diem_dau, diem_cuoi, tong_so_cau, tinh_trang_su_dung,
										  id_loai, id_cap, id_co_quan
								   FROM duong_bo
								   WHERE id_duong=%u", $_GET['id']);
				$db->connect();
				$result = $db->query($strSQL);
				$duong = pg_fetch_object($result);
				echo '<form name="edit" id="edit" action="update_duong_bo.php" method="post">';
				
				echo '<p><label for="tenDuong">Tên đường:</label>';
				echo '<input type="text" name="tenDuong" id="tenDuong" value="' . $duong->ten . '" /></p>';
				
				// Lấy thông tin cơ quan quản lý
				$db->connect();
				$coquan = $db->query("SELECT id_co_quan, ten FROM co_quan_quan_ly");
				
				echo '<p><label for="coQuan">Cơ quan quản lý:</label><select name="coQuan" id="coQuan">';
				while ($row = pg_fetch_object($coquan)) {
					if ($row-> id_co_quan === $duong->id_co_quan) {
						echo '<option value="' . $row->id_co_quan . '" selected="selected">' . $row->ten . '</option>';
					} else {
						echo '<option value="' . $row->id_co_quan . '">' . $row->ten . '</option>';
					}
				}				
				echo '</select></p>';
				
				echo '<p><label for="diemDau">Điểm đầu:</label>';
				echo '<textarea name="diemDau" id="diemDau" rows="3">' . $duong->diem_dau . '</textarea></p>';
				
				echo '<p><label for="diemCuoi">Điểm cuối:</label>';
				echo '<textarea name="diemCuoi" id="diemCuoi" rows="3">' . $duong->diem_cuoi . '</textarea></p>';
				
				echo '<p><label for="tsCau">Tổng số cầu:</label>';
				echo '<input type="text" name="tsCau" id="tsCau" value="' . $duong->tong_so_cau . '" /></p>';
				
				// Lấy thông tin cấp đường
				$db->connect();
				$cap = $db->query("SELECT id_cap, cap FROM cap_duong");
				
				echo '<p><label for="capDuong">Cấp đường:</label><select name="capDuong" id="capDuong">';
				while ($row = pg_fetch_object($cap)) {
					if ($row->id_cap === $duong->id_cap) {
						echo '<option value="' . $row->id_cap . '" selected="selected">' . $row->cap . '</option>';
					} else {
						echo '<option value="' . $row->id_cap . '">' . $row->cap . '</option>';
					}
				}				
				echo '</select></p>';
				
				// Lấy thông tin loại đường
				$db->connect();
				$loai = $db->query("SELECT id_loai, loai FROM loai_duong");
				
				echo '<p><label for="loaiDuong">Loại đường:</label><select name="loaiDuong" id="loaiDuong">';
				while ($row = pg_fetch_object($loai)) {
					if ($row->id_loai === $duong->id_loai) {
						echo '<option value="' . $row->id_loai . '" selected="selected">' . $row->loai . '</option>';
					} else {
						echo '<option value="' . $row->id_loai . '">' . $row->loai . '</option>';
					}
				}				
				echo '</select></p>';
				
				echo '<p><label for="tinhTrang">Tình trạng sử dụng:</label>';
				echo '<textarea name="tinhTrang" id="tinhTrang" rows="4">' . $duong->tinh_trang_su_dung . '</textarea></p>';
				
				echo '<input type="hidden" name="action" value="edit" />';
				echo '<input type="hidden" name="id" value="' . $_GET['id'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_duong_bo.php\'" />';
			}
			?>
            </div>
        </div><!--End updateDuongContent-->
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