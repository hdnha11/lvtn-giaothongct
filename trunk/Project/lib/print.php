<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * Lấy ảnh của các lớp bản đồ từ server và merge lại lưu trong thư mục tmp
 * Trả về đường dẫn đến file ảnh
 */

$TEMP_DIR = dirname(__FILE__) . '/../tmp/';
$TEMP_URL = 'tmp';

/*
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity) {
	$w = imagesx($src_im);
	$h = imagesy($src_im);
	$cut = imagecreatetruecolor($src_w, $src_h);
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
}
*/

function imagecopymerge_alpha($dest, $src) {
	// Set the brush
	imagesetbrush($dest, $src);
	// Draw a couple of brushes, each overlaying each
	imageline($dest, imagesx($dest) / 2, imagesy($dest) / 2, imagesx($dest) / 2, imagesy($dest) / 2, IMG_COLOR_BRUSHED);
}

// fetch the request params, and generate the name of the tempfile and its URL
if (isset($_REQUEST['width'])) {
	$width = $_REQUEST['width'];
} else {
	$width = 1024;
}

if (isset($_REQUEST['height'])) {
	$height = $_REQUEST['height'];
} else {
	$height = 768;
}

$tiles    = json_decode(@$_REQUEST['tiles']);
//$tiles    = json_decode(stripslashes(@$_REQUEST['tiles'])); // use this if you use magic_quotes_gpc
$random   = md5(microtime().mt_rand());
$file     = sprintf("%s/%s.jpg", $TEMP_DIR, $random);
$url      = sprintf("%s/%s.jpg", $TEMP_URL, $random);

// lay down an image canvas
// Notice: in MapServer if you have set a background color
// (eg. IMAGECOLOR 60 100 145) that color is your transparent value
// $transparent = imagecolorallocatealpha($image,60,100,145,127);
$image = imagecreatetruecolor($width, $height);
imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255)); // fill with white

// loop through the tiles, blitting each one onto the canvas
foreach ($tiles as $tile) {
	// try to convert relative URLs into full URLs
	// this could probably use some improvement
	$tile->url = urldecode($tile->url);
	if (substr($tile->url, 0, 4)!== 'http') {
		$tile->url = preg_replace('/^\.\//', dirname($_SERVER['REQUEST_URI']) . '/', $tile->url);
		$tile->url = preg_replace('/^\.\.\//', dirname($_SERVER['REQUEST_URI']) . '/../', $tile->url);
		$tile->url = sprintf("%s://%s:%d/%s", isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['SERVER_ADDR']
					 , $_SERVER['SERVER_PORT'], $tile->url);
	}
	$tile->url = str_replace(' ', '+', $tile->url);
	
	// fetch the tile into a temp file, and analyze its type; bail if it's invalid
	$tempfile = sprintf("%s/%s.img", $TEMP_DIR, md5(microtime().mt_rand()));
	file_put_contents($tempfile, file_get_contents($tile->url));
	list($tilewidth, $tileheight, $tileformat) = @getimagesize($tempfile);
	if (!$tileformat) {
		continue;
	}
	
	// load the tempfile's image, and blit it onto the canvas
	switch ($tileformat) {
		case IMAGETYPE_GIF:
			$tileimage = imagecreatefromgif($tempfile);
			break;
		case IMAGETYPE_JPEG:
			$tileimage = imagecreatefromjpeg($tempfile);
			break;
		case IMAGETYPE_PNG:
			$tileimage = imagecreatefrompng($tempfile);
			break;
	}
	//imagecopymerge_alpha($image, $tileimage, $tile->x, $tile->y, 0, 0, $tilewidth, $tileheight, $tile->opacity);
	imagecopymerge_alpha($image, $tileimage);
}

// save to disk and tell the client where they can pick it up
imagejpeg($image, $file, 100);
print $url;