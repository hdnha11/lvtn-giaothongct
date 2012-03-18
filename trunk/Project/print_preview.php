<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * Xuất bản đồ
 */
require_once('lib/tcpdf/config/lang/eng.php');
require_once('lib/tcpdf/tcpdf.php');

if (isset($_GET['imgUrl'])) {
	$imgUrl = $_GET['imgUrl'];
}

// Nếu người dùng chọn xuất PDF
if (isset($_POST['exportpdf'])) {

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Hoàng Đức Nhã');
	$pdf->SetTitle('Xuất bản đồ');
	$pdf->SetSubject('Luận văn tốt nghiệp');
	$pdf->SetKeywords('GIS, Geo, OpenGIS, PostGIS, OpenLayers');
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	// ---------------------------------------------------------
	
	// set font
	$pdf->SetFont('dejavusans', '', 14, '', true);
	
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
	
	// define some HTML content with style
	$html = <<<EOF
	<!-- EXAMPLE OF CSS STYLE -->
	<style>
		
		/* Links */
		a:link, a:visited {
			background: transparent;
			color: #333;
			text-decoration: none;
		}
		
		a:link[href^="http://"]:after, a[href^="http://"]:visited:after {
			content: " (" attr(href) ") ";
			font-size: 11px;
		}
		
		a[href^="http://"] {
			color: #000;
		}
		
		/*Display Element*/
		div#title {
			display: block;
			width: 100%;
			margin: 10px auto;
			text-align: center;
		}
		
		img#mapImage {
			border: 2px solid #000;
		}
		
		div#right {
			width: 100%;
			height: auto;
		}
	</style>
	
	<div id="wrapper">
		<div id="right">
			<img id="mapImage" src="$imgUrl" />
			<div id="title">Bản đồ: {$_POST['titlemap']}</div>
		</div>
	</div>
EOF;
	
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
	$pdf->Output('bando.pdf', 'I');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GIS quản lý giao thông bộ - Xuất bản đồ</title>
<link type="text/css" rel="stylesheet" href="css/print-preview.css" />
<link type="text/css" rel="stylesheet" href="css/map-print.css" media="print" />
<script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		
		// In ban do
        $('button#print').click(function() {
			var title = document.getElementById('titlemap').value;
			$('div#title').html('Bản đồ: ' + title);
			window.print();
			return false;
		});
    });
</script>
</head>

<body>
<div id="wrapper">
    <div id="left">
        <form name="option" id="option" method="post" action="">
            <fieldset class="tool">
                <legend>Chọn dạng xuất</legend>
                <div>
                    <button name="print" id="print">Máy in</button>
                </div>
                <div>
                    <button name="exportpdf" id="exportpdf">Xuất dạng PDF</button>
                </div>
            </fieldset>
            <fieldset class="info">
                <legend>Tên bản đồ</legend>
                <div>
                    <textarea name="titlemap" id="titlemap" rows="4">Giao thông Tp. Cần Thơ</textarea>
                </div>
            </fieldset>
        </form>
    </div>
    <div id="right">
    	<img id="mapImage" src="<?php echo @$imgUrl; ?>" />
        <div id="title"></div>
    </div>
</div>
</body>
</html>