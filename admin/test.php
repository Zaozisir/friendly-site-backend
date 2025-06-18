<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 测试数据库连接
require_once 'config.php';
echo "数据库连接测试：<br>";
if ($conn->ping()) {
    echo "数据库连接成功！<br>";
} else {
    echo "数据库连接失败：" . $conn->error . "<br>";
}

// 测试目录权限
$testDirs = [
    '../products_images',
    '../finished_images',
    '../cases_images'
];

echo "<br>目录权限测试：<br>";
foreach ($testDirs as $dir) {
    if (!file_exists($dir)) {
        echo "目录 {$dir} 不存在，尝试创建...<br>";
        if (mkdir($dir, 0777, true)) {
            echo "目录 {$dir} 创建成功！<br>";
        } else {
            echo "目录 {$dir} 创建失败！<br>";
        }
    } else {
        echo "目录 {$dir} 已存在<br>";
    }
    
    if (is_writable($dir)) {
        echo "目录 {$dir} 可写<br>";
    } else {
        echo "目录 {$dir} 不可写！<br>";
    }
}

// 测试PHP配置
echo "<br>PHP配置测试：<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_execution_time: " . ini_get('max_execution_time') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

// 测试数据库表
echo "<br>数据库表测试：<br>";
$result = $conn->query("SHOW TABLES LIKE 'images'");
if ($result->num_rows > 0) {
    echo "images表存在<br>";
    
    // 测试表结构
    $result = $conn->query("DESCRIBE images");
    echo "表结构：<br>";
    while ($row = $result->fetch_assoc()) {
        echo "字段: {$row['Field']}, 类型: {$row['Type']}<br>";
    }
} else {
    echo "images表不存在！<br>";
}
?> 