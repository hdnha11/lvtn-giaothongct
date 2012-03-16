<?php
/**
 * Hoàng Đức Nhã
 * Sinh viên lớp Hệ thống thông tin K34
 * Khoa CNTT & Truyền thông
 * Trường Đại học Cần Thơ
 * Email: hdnha11@gmail.com
 * File Proxy dùng cho việc cross-side server khi gọi AJAX
 * Acknowledgement
 * Md. Abul Khayer
 * http://khayer.wordpress.com/2010/07/04/solution-of-cross-domain-ajax-call-problem
 */

$url = $_GET["url"];
$res = file_get_contents($url);
echo $res;
