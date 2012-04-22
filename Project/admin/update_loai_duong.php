<?php
session_start();
require_once dirname(__FILE__) . '/../lib/AccessControl.php';
require_once dirname(__FILE__) . '/../lib/Login.php';
require_once dirname(__FILE__) . '/../lib/Paging.php';

if (Login::isLoggedIn()) {
	
	$ac     = new AccessControl();
	$db     = new PgSQL();
	$paging = new Paging('update_loai_duong.php?', 10);
	
	if ($ac->hasPermission('cap_nhat_du_lieu') != true) {
		header("refresh:5;url=index.php");
		// Hiển thị thông báo truy cập trái phép
		include dirname(__FILE__) . '/includes/message.html';
	} else {
		if (isset($_POST['action'])) {
			switch ($_POST['action']) {
				case 'insert':
					$strSQL = sprintf("INSERT INTO loai_duong(loai) VALUES ('%s')", $_POST['loaiDuong']);
					$db->connect();
					$db->query($strSQL);
					break;
				case 'edit':
					$strSQL = sprintf("UPDATE loai_duong SET loai='%s' WHERE id_loai=%u", $_POST['loaiDuong'], $_POST['id']);
					$db->connect();
					$db->query($strSQL);
					break;
				case 'delete':
					$ids = isset($_POST['idLoai']) ? $_POST['idLoai'] : array();
					$strSQL = "DELETE FROM loai_duong WHERE id_loai IN (";
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
<title>Quản lý loại đường</title>
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

// Check all
var check = function(list) {
	if (document.dsLoaiDuong.checkAll.checked === true) {
		for (i = 0; i < list.length; i++) {
			list[i].checked = true ;
		}
	} else {	
		for (i = 0; i < list.length; i++) {
			list[i].checked = false ;
		}
	}
};

// Kiểm tra form
var checkForm = function() {
	
	// Kiểm tra không nhập liệu
	var loaiInput = $('#loaiDuong');
	if (loaiInput.val() == '') {
		alert('Bạn phải nhập loại đường');
		loaiInput.focus();
		
		return false;
	}
	
	return true;
};
</script>

<style type="text/css">
#updateLoaiDuongContent {
	float: left;
	width: 732px;
	min-height: 82%;
	margin: 10px 0px 0px 0px !important;
	border: #c4c4c4 solid 1px;
	background: #F7F7F7; /*Test*/
}

#updateLoaiDuongContent #content {
	position: relative;
	margin: 0px;
	padding: 0px 20px 20px 20px;
}

/* Định dạng bảng */
#updateLoaiDuongContent table {
	/*font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	font-size: 12px;
	margin: 45px;*/
	width: 692px;
	margin: 0px auto;
	text-align: left;
	border-collapse: collapse;
}

#updateLoaiDuongContent table th {
	font-size: 14px;
	font-weight: bold;
	padding: 10px 8px;
	color: #039;
	background: #8AB1FE;
	border-bottom: 2px solid #3A7ABE;
}

#updateLoaiDuongContent table td {
	padding: 8px;
	color: #669;
}

#updateLoaiDuongContent table td.rowtitle {
	text-align: left;
}

#updateLoaiDuongContent table .even {
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
form#searchLoai {
	width: 256px;
	position: absolute;
	top: 52px;
	right: 20px;
}

form#searchLoai input#queryStr {
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
	width: 80px;
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
        <div id="updateLoaiDuongContent">
            <h1 class="contentTitle">Cập nhật loại đường</h1>
            <div id="content">
            <?php
			if (!isset($_GET['action'])) { // Hiện danh sách loại đường
				
				// Form tìm kiếm
				echo '<form name="searchLoai" id="searchLoai" method="get" action="update_loai_duong.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="Tìm kiếm" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_loai_duong.php?action=addnew">Thêm loại đường mới</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsLoaiDuong'". '].submit();">Xóa loại đường đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị loại đường
            	echo '<form name="dsLoaiDuong" id="dsLoaiDuong" method="post" action="update_loai_duong.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$str = "SELECT id_loai, loai FROM loai_duong";
				$paging->getNav($page, $str, 4);
				
				// Danh sách loại đường phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsLoaiDuong[\'idLoai[]\']);" /></th>';
				echo '<th>Loại đường</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idLoai[]" id="idLoai[]" value="' . $row->id_loai . '" /></td>';
					echo '<td>' . $row->loai . '</td>';
					echo '<td><a href="update_loai_duong.php?action=edit&id=' . $row->id_loai . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'search') {
				// Form tìm kiếm
				echo '<form name="searchLoai" id="searchLoai" method="get" action="update_loai_duong.php">';				
				echo '<input type="hidden" name="action" id="action" value="search" />';
				echo '<input type="text" name="queryStr" id="queryStr" value="' . $_GET['queryStr'] . '" />';
				echo '</form>';
				
				// Thanh thêm, xóa
				echo '<div id="header-panel">';
				echo '<ul>';
				echo '<li>';
				echo '<a class="add-link" href="update_loai_duong.php?action=addnew">Thêm loại đường mới</a>';
				echo '</li>';
				echo '<li>';
				echo '<a class="remove-link" href="#" onclick="document.forms[' . "'dsLoaiDuong'". '].submit();">Xóa loại đường đã chọn</a>';
				echo'</li>';
				echo '</ul>';
				echo '</div>';
				
				// Form hiển thị loại đường
            	echo '<form name="dsLoaiDuong" id="dsLoaiDuong" method="post" action="update_loai_duong.php">';
				echo '<input type="hidden" name="action" value="delete" />';
				
				// Các biến dùng cho phân trang
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
				$str = "SELECT id_loai, loai FROM loai_duong WHERE loai ILIKE '%" . $_GET['queryStr'] . "%'";
				$paging = new Paging('update_loai_duong.php?action=search&queryStr=' . $_GET['queryStr'] . '&', 10);
				$paging->getNav($page, $str, 4);
				
				// Danh sách cấp đường phân trang
                echo '<table>';
                echo '<tr class="rowtitle">';
				echo '<th><input type="checkbox" name="checkAll" onclick="check(document.dsLoaiDuong[\'idLoai[]\']);" /></th>';
				echo '<th>Loại đường</th><th>Cập nhật</th></tr>';
				
				// lấy về trang $page
				$result = $paging->getPage($page, $str);
				
				// In trang ra màn hình
				$i = 0;
				foreach ($result as $row) {
					
					$value = ($i % 2 === 0) ? 'even' : 'odd';
					echo '<tr class="'. $value . '">';
					echo '<td><input type="checkbox" name="idLoai[]" id="idLoai[]" value="' . $row->id_loai . '" /></td>';
					echo '<td>' . $row->loai . '</td>';
					echo '<td><a href="update_loai_duong.php?action=edit&id=' . $row->id_loai . '">Sửa</a></td>';
					echo '</tr>';
					
					$i++;
				}
                echo '</table>';
                echo '</form>';
			} elseif ($_GET['action'] === 'addnew') { // Thêm loại đường
				echo '<form name="addNew" id="addNew" action="update_loai_duong.php" method="post" onsubmit="return checkForm();">';
				echo '<p><label for="loaiDuong">Loại đường:</label><input type="text" name="loaiDuong" id="loaiDuong" /></p>';
				echo '<input type="hidden" name="action" value="insert" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Thêm mới" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_loai_duong.php\'" />';
				echo '</form>';
			} elseif ($_GET['action'] === 'edit') { // Cập nhật loại đường
				$strSQL = sprintf("SELECT loai FROM loai_duong WHERE id_loai=%u", $_GET['id']);
				$db->connect();
				$result = $db->query($strSQL);
				$row = pg_fetch_object($result);
				echo '<form name="edit" id="edit" action="update_loai_duong.php" method="post" onsubmit="return checkForm();">';
				echo '<p><label for="loaiDuong">Loại đường:</label>';
				echo '<input type="text" name="loaiDuong" id="loaiDuong" value="' . $row->loai . '" /></p>';
				echo '<input type="hidden" name="action" value="edit" />';
				echo '<input type="hidden" name="id" value="' . $_GET['id'] . '" />';
				echo '<input type="submit" name="Submit" class="btnForm" value="Cập nhật" />';
				echo '<input type="button" name="Cancel" class="btnForm" value="Hũy bỏ" onclick="window.location=\'update_loai_duong.php\'" />';
			}
			?>
            </div>
        </div><!--End updateLoaiDuongContent-->
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