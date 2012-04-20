<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * Xuất báo cáo hiện trạng bến xe buýt PDF
 */
 
require_once dirname(__FILE__) . '/../../lib/tcpdf/config/lang/eng.php';
require_once dirname(__FILE__) . '/../../lib/tcpdf/tcpdf.php';
require_once dirname(__FILE__) . '/../../lib/PgSQL.php';

$db = new PgSQL;

if (isset($_POST['reportOption'])) {
	
	// Lấy về nội dung báo cáo
	$reportContent = '';
	
	if ($_POST['reportOption'] === 'all') {
		$reportSQL = <<<SQL
			SELECT b.dien_giai AS ten_ben, d.ten AS ten_duong, b.dia_chi, b.di_va_den
			FROM ben_xe_buyt_point AS b
			INNER JOIN duong_bo AS d ON b.id_duong = d.id_duong
SQL;
		
		$reportContent .= '<div class="rtContentTitle">Danh sách bến xe buýt</div>';
		$reportContent .= <<<TABLE
		<table border="1" cellpadding="5">
			<thead>
				<tr class="bold" align="center" bgcolor="#99CCFF">
					<th width="6%">STT</th>
					<th width="18%">Tên bến</th>
					<th width="18%">Thuộc đường</th>
					<th width="24%">Địa chỉ</th>
					<th width="34%">Tuyến đi và đến</th>
				</tr>
			</thead>
			<tbody>
TABLE;

		$db->connect();
		$dsDuong = $db->query($reportSQL);
		$stt = 1;
		while ($duong = pg_fetch_object($dsDuong)) {
			$reportContent .= '<tr>';
			$reportContent .= '<td width="6%" align="center">' . $stt . '</td>';
			$reportContent .= '<td width="18%" align="left">' . $duong->ten_ben . '</td>';
			$reportContent .= '<td width="18%" align="left">' . $duong->ten_duong . '</td>';
			$reportContent .= '<td width="24%" align="left">' . $duong->dia_chi . '</td>';
			$reportContent .= '<td width="34%" align="left">' . $duong->di_va_den . '</td>';
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

		$reportContent .= '<div class="rtContentTitle">Danh sách bến xe buýt</div>';
		$reportContent .= <<<TABLE
		<table border="1" cellpadding="5">
			<thead>
				<tr class="bold" align="center" bgcolor="#99CCFF">
					<th width="6%">STT</th>
					<th width="18%">Tên bến</th>
					<th width="18%">Thuộc đường</th>
					<th width="24%">Địa chỉ</th>
					<th width="34%">Tuyến đi và đến</th>
				</tr>
			</thead>
			<tbody>
TABLE;
		
		$db->connect();
		$dsDuong = $db->query($reportSQL);
		$stt = 1;
		while ($duong = pg_fetch_object($dsDuong)) {
			$reportContent .= '<tr>';
			$reportContent .= '<td width="6%" align="center">' . $stt . '</td>';
			$reportContent .= '<td width="18%" align="left">' . $duong->ten_ben . '</td>';
			$reportContent .= '<td width="18%" align="left">' . $duong->ten_duong . '</td>';
			$reportContent .= '<td width="24%" align="left">' . $duong->dia_chi . '</td>';
			$reportContent .= '<td width="34%" align="left">' . $duong->di_va_den . '</td>';
			$reportContent .= '</tr>';
			
			$stt++;
		}
		$reportContent .= "
			</tbody>
		</table>";

	}
	
	// Tạo HTML chuẩn bị xuất PDF
	$html = '<style>		
			/* In đậm, in nghiêng */
			.bold {
				font-weight: bold;
			}
			
			.italic {
				font-style: italic;
			}
			
			.rtContentTitle {
				font-weight: bold;
			}
			</style>
			
			<table class="bold">
				<tr>
					<td width="40%" align="left">
						BỘ GIAO THÔNG VẬN TẢI<br />
						Sở GTVT Tp. Cần Thơ
					</td>
					<td width="60%" align="center">
						CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM<br />
						Độc lập - Tự do - Hạnh phúc
					</td>
				</tr>
			</table>
			<p align="right" class="italic">Cần Thơ, ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y') . '</p>
			<div id="title" class="bold">
				<p align="center">BÁO CÁO HIỆN TRẠNG BẾN XE BUÝT<br />
				NĂM ' . date('Y') . '</p>';
				
	$subTitle = '';
	if ($_POST['reportOption'] != 'all') {
		$db->connect();
		$dsDuong = $db->query("SELECT ten FROM duong_bo WHERE id_duong = {$_POST['reportOption']}");
		$duong = pg_fetch_object($dsDuong);
		$subTitle = '<p align="center" class="italic">Trên tuyến đường ' . $duong->ten . '</p>';
	}
	$html .= $subTitle;
	
	$html .= '</div>';
	
	// Nối $html với nội dung báo cáo
	$html .= $reportContent;
	
	// Nối $html với các thẻ đóng
	$html .= <<<HTMLCLOSE
		<div align="right" id="sign">
			<span class="bold">Thủ trưởng đơn vị</span><br />
			<span class="italic">(Ký tên, đóng dấu)</span>
		<div>
HTMLCLOSE;
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	//$pdf->setPageOrientation('L');
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Hoàng Đức Nhã');
	$pdf->SetTitle('Báo cáo hiện trạng bến xe buýt');
	$pdf->SetSubject('Luận văn tốt nghiệp');
	$pdf->SetKeywords('GIS, Geo, OpenGIS, PostGIS, OpenLayers');
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetMargins(8, 10, 8);
	
	//set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetAutoPageBreak(TRUE, 10);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	//$pdf->SetFont('pdfatimes', '', 12, '', true);
	//$pdf->SetFont('aealarabiya', '', 12, '', true);
	//$pdf->SetFont('aefurat', '', 12, '', true);
	//$pdf->SetFont('dejavusanscondensed', '', 12, '', true);
	//$pdf->SetFont('dejavuserif', '', 12, '', true);
	//$pdf->SetFont('dejavuserifcondensed', '', 12, '', true);
	//$pdf->SetFont('dejavusans', '', 12, '', true);
	//$pdf->SetFont('freemono', '', 12, '', true);
	$pdf->SetFont('freeserif', '', 12, '', true);
	//$pdf->SetFont('freesans', '', 12, '', true);
	
	// add a page
	$pdf->AddPage();
	
	/* NOTE:
	 * *********************************************************
	 * You can load external XHTML using :
	 *
	 * $html = file_get_contents('/path/to/your/file.html');
	 *
	 * External CSS files will be automatically loaded.
	 * Sometimes you need to fix the path of the external CSS.
	 * *********************************************************
	 */
	
	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	// *******************************************************************
	// HTML TIPS & TRICKS
	// *******************************************************************
	
	// REMOVE CELL PADDING
	//
	// $pdf->SetCellPadding(0);
	// 
	// This is used to remove any additional vertical space inside a 
	// single cell of text.
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	// REMOVE TAG TOP AND BOTTOM MARGINS
	//
	// $tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
	// $pdf->setHtmlVSpace($tagvs);
	// 
	// Since the CSS margin command is not yet implemented on TCPDF, you
	// need to set the spacing of block tags using the following method.
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	// SET LINE HEIGHT
	//
	// $pdf->setCellHeightRatio(1.25);
	// 
	// You can use the following method to fine tune the line height
	// (the number is a percentage relative to font height).
	
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	
	// CHANGE THE PIXEL CONVERSION RATIO
	//
	// $pdf->setImageScale(0.47);
	// 
	// This is used to adjust the conversion ratio between pixels and 
	// document units. Increase the value to get smaller objects.
	// Since you are using pixel unit, this method is important to set the
	// right zoom factor.
	// 
	// Suppose that you want to print a web page larger 1024 pixels to 
	// fill all the available page width.
	// An A4 page is larger 210mm equivalent to 8.268 inches, if you 
	// subtract 13mm (0.512") of margins for each side, the remaining 
	// space is 184mm (7.244 inches).
	// The default resolution for a PDF document is 300 DPI (dots per 
	// inch), so you have 7.244 * 300 = 2173.2 dots (this is the maximum 
	// number of points you can print at 300 DPI for the given width).
	// The conversion ratio is approximatively 1024 / 2173.2 = 0.47 px/dots
	// If the web page is larger 1280 pixels, on the same A4 page the 
	// conversion ratio to use is 1280 / 2173.2 = 0.59 pixels/dots
	
	// *******************************************************************
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// ---------------------------------------------------------
	
	//Close and output PDF document
	$pdf->Output('bcht_ben_xe_buyt_' . date('dmYHis') . '.pdf', 'I');
}