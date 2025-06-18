<?php
// 定义需要创建的目录
$directories = [
    '../uploads/finished',
    '../uploads/products/花岗岩',
    '../uploads/products/大理石',
    '../uploads/products/石英石',
    '../uploads/cases/商业空间',
    '../uploads/cases/住宅空间',
    '../uploads/cases/公共空间'
];

// 创建目录
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "创建目录成功: {$dir}\n";
        } else {
            echo "创建目录失败: {$dir}\n";
        }
    } else {
        echo "目录已存在: {$dir}\n";
    }
}

echo "目录初始化完成！";
?> 