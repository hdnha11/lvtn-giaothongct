<?php
session_start();
require_once dirname(__FILE__) . '/../lib/Login.php';

Login::logout();

// Chuyễn đến trang login
header('Location: login.php');