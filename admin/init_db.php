<?php
require_once 'config.php';

// 设置错误处理
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// 创建数据库连接
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 创建数据库
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "数据库创建成功或已存在<br>";
} else {
    die("创建数据库失败: " . $conn->error);
}

// 选择数据库
$conn->select_db(DB_NAME);

// 创建新闻表
$sql = "CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "新闻表创建成功或已存在<br>";
} else {
    die("创建新闻表失败: " . $conn->error);
}

// 创建图片表
$sql = "CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    sub_category VARCHAR(50),
    path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "图片表创建成功或已存在<br>";
} else {
    die("创建图片表失败: " . $conn->error);
}

$conn->close();
echo "数据库初始化完成！";
?> 