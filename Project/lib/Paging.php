<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 */
 
require_once dirname(__FILE__) . '/PgSQL.php';

/**
 * Lớp phân trang
 */
class Paging {
	private $display;
	private $url;
	private $db;
	
	public function __construct($url, $display) {
		$this->url     = $url;
		$this->display = $display;
		$this->db      = new PgSQL();
	}
	
	/**
	 * In ra thanh Navigation cho trang, voi do rong <= $range
	 */
	public function getNav($currentPage, $sqlStr, $range) {
		
		$this->db->connect();
		$result = $this->db->query($sqlStr);
		
		// Sinh thanh điều hướng trang
		$numberPages = ceil($this->db->numberRows() / $this->display);
		
		echo '<ul class="pageNav">';
		if ($numberPages > 0) {			
			
			// Nút trang đầu tiên
			if ($currentPage != 1) {
				echo '<li><a href="' . $this->url . 'page=1"><<</a></li>';
			} else {
				echo '<li class="disable"><<</li>';
			}
			
			// Nút quay lui
			if ($currentPage > 1) {
				echo '<li><a href="' . $this->url . 'page=' . ($currentPage - 1) . '"><</a></li>';
			} else {
				echo '<li class="disable"><</li>';
			}
			
			// We need the sliding effect only if there are more pages than is the sliding range
			if ($numberPages > $range) {
			
				if ($currentPage < $range) { // When closer to the beginning
					
					for ($i = 1; $i <= ($range + 1); $i++) {
						if ($i != $currentPage) {
							echo '<li><a href="' . $this->url . 'page=' . $i .'">' . $i . '</a></li>';
						} else {
							echo '<li class="current">' . $i . '</li>';
						}
					}
				} elseif ($currentPage >= ($numberPages - ceil(($range / 2)))) { // When closer to the end
				
					for ($i = $numberPages - $range; $i <= $numberPages; $i++) {
						if ($i != $currentPage) {
							echo '<li><a href="' . $this->url . 'page=' . $i .'">' . $i . '</a></li>';
						} else {
							echo '<li class="current">' . $i . '</li>';
						}
					}
				} elseif ($currentPage >= $range && $currentPage < ($numberPages - ceil(($range  /2)))) { // Somewhere in the middle
				
					for ($i = ($currentPage - ceil($range / 2)); $i <= ($currentPage + ceil(($range / 2))); $i++) {
						if ($i != $currentPage) {
							echo '<li><a href="' . $this->url . 'page=' . $i .'">' . $i . '</a></li>';
						} else {
							echo '<li class="current">' . $i . '</li>';
						}
					}
				}
				
			} else { // Less pages than the range, no sliding effect needed
					
				for ($i = 1; $i <= $numberPages; $i++) {
					if ($i != $currentPage) {
						echo '<li><a href="' . $this->url . 'page=' . $i .'">' . $i . '</a></li>';
					} else {
						echo '<li class="current">' . $i . '</li>';
					}
				}
			}
			
			// Nút trang kế tiếp
			if ($currentPage < $numberPages) {
				echo '<li><a href="' . $this->url . 'page=' . ($currentPage + 1) . '">></a></li>';
			} else {
				echo '<li class="disable">></li>';
			}
			
			// Nút trang cuối cùng
			if ($currentPage != $numberPages) {
				echo '<li><a href="' . $this->url . 'page=' . $numberPages . '">>></a></li>';
			} else {
				echo '<li class="disable">>></li>';
			}
		}
		echo '</ul>';
	}
	
	/**
	 * Lấy về trang với số trang truyền vào
	 */
	public function getPage($page, $sqlStr) {
		
		$start = ($page - 1) * $this->display;
		$end = $page * $this->display;
		
		$this->db->connect();
		$result = $this->db->query($sqlStr);
		
		$resultTable = array();
		
		$i = 0;
		// Lặp trong khi còn dữ liệu và stt dòng hiện tại nhỏ hơn end
		while (($row = pg_fetch_object($result)) && ($i < $end)) {
			
			// Bắt đầu xuất nội dung từ hàng thứ start
			if ($i >= $start) {
				$resultTable[] = $row;
			}
			$i++;
		}
		
		return $resultTable;
	}
}