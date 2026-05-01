<?php


// 连接mysql数据库
$host = "127.0.0.1";
$user = "root";
$userpassword = "usbw";
$database = "blog";
$port = "3307";
$conn = mysqli_connect($host, $user, $userpassword, $database, $port);
if (!$conn) {
    die("连接失败: " . mysqli_connect_error());
    exit;
}

// 设置连接的字符集
mysqli_set_charset($conn, "utf8");

?>