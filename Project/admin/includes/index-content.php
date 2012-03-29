<div id="indexContent">
	<h1 class="contentTitle">Chào mừng đến với trang quản trị</h1>
    <div id="content">
    	<h2>Người dùng <span class="username"><?php echo $ac->getUsername($ac->userID); ?></span> có quyền:</h2>
        <?php 
            $aPerms = $ac->getAllPerms('full');
            foreach ($aPerms as $k => $v) {
				echo '<div class="perm">';
                echo '<p>' . $v['Name'] . ': ';
                echo '<img src="images/';
                if ($ac->hasPermission($v['Key']) === true) {
                    echo 'allow.png';
                    $pVal = 'Cho phép';
                } else {
                    echo 'deny.png';
                    $pVal = 'Từ chối';
                }
                echo '" width="16" height="16" alt="' . $pVal . '" /></p>';
				echo '</div>';
            }
        ?>
        <hr />
    	<p>Sử dụng các liên kết phía bên trái để truy cập vào các chức năng quản trị người dùng, lập báo cáo thống kê 
        cũng như cập nhật dữ liệu thuộc tính mà người dùng có quyền.</p>
        <p>Mọi thắc mắc hay đóng góp ý kiến xin vui lòng liên lạc với tác giả. Thông tin liên lạc xem mục Thông tin
        hoặc dùng liên kết Liên hệ.</p>
        <p>Xin cám ơn!</p>
    </div>
</div><!--End indexContent-->