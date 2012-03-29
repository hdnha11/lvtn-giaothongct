<div id="indexContent">
	<h1 class="contentTitle">Chào mừng đến với trang quản trị</h1>
    <div id="content">
    	<h2><?php echo $ac->getUsername($ac->userID); ?> có quyền:</h2>
        <?php 
            $aPerms = $ac->getAllPerms('full');
            foreach ($aPerms as $k => $v) {
                echo '<strong>' . $v['Name'] . ': </strong>';
                echo '<img src="images/';
                if ($ac->hasPermission($v['Key']) === true) {
                    echo 'allow.png';
                    $pVal = 'Cho phép';
                } else {
                    echo 'deny.png';
                    $pVal = 'Từ chối';
                }
                echo '" width="16" height="16" alt="' . $pVal . '" /><br />';
            }
        ?>
        <hr />
    	<p>Sử dụng các liên kết phía bên trái để truy cập các chức năng quản trị người dùng, lập báo cáo thống kê 
        cũng như cập nhật dữ liệu thuộc tính.</p>
        <p>Nếu có thắc mắc hay đóng góp ý kiến xin vui lòng xem mục Thông tin.</p>
        <p>Xin cám ơn!</p>
    </div>
</div><!--End indexContent-->