<?php
require_once 'config.php';

header('Content-Type: application/json');

// 验证参数
if (!isset($_GET['category'])) {
    echo json_encode(['success' => false, 'message' => '缺少必要参数']);
    exit;
}

$category = $_GET['category'];
$subCategory = isset($_GET['subCategory']) ? $_GET['subCategory'] : '';

// 构建上传目录（用于实际文件存储）
$uploadDir = '../';
if ($category === 'products' && !empty($subCategory)) {
    // 根据子分类确定目标文件夹
    $folderMap = [
        'aluhoney' => '001',
        'shimiaofh' => '002',
        'fangshi' => '003',
        'huagangyan' => '004',
        'xian' => '005',
        'shuidao' => '006',
        'beijingqiang' => '007',
        'daban' => '008',
        'video' => 'test_video'
    ];
    $targetFolder = isset($folderMap[$subCategory]) ? $folderMap[$subCategory] : $subCategory;
    $uploadDir .= $targetFolder . '/';
} else if ($category === 'cases' && !empty($subCategory)) {
    // 工程案例的文件夹映射
    $folderMap = [
        'hospital' => '101',
        'station' => '102',
        'hotel' => '103',
        'other' => '104'
    ];
    $targetFolder = isset($folderMap[$subCategory]) ? $folderMap[$subCategory] : 'cases_images';
    $uploadDir .= $targetFolder . '/';
} else {
    $uploadDir .= $category . '_images/';
}

// 确保目录存在
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        error_log("创建目录失败: " . $uploadDir);
        echo json_encode(['success' => false, 'message' => '创建目录失败']);
        exit;
    }
    error_log("创建目录成功: " . $uploadDir);
}

// 记录调试信息
error_log("读取目录: " . $uploadDir);
error_log("分类: " . $category);
error_log("子分类: " . $subCategory);

// 扫描目录
$images = [];
if ($handle = opendir($uploadDir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'mp4'])) {
                // 修改路径处理逻辑
                $relativePath = '';
                if ($category === 'products' && !empty($subCategory)) {
                    $folderMap = [
                        'aluhoney' => '001',
                        'shimiaofh' => '002',
                        'fangshi' => '003',
                        'huagangyan' => '004',
                        'xian' => '005',
                        'shuidao' => '006',
                        'beijingqiang' => '007',
                        'daban' => '008',
                        'video' => 'test_video'
                    ];
                    $targetFolder = isset($folderMap[$subCategory]) ? $folderMap[$subCategory] : $subCategory;
                    $relativePath = $targetFolder . '/' . $file;
                } else if ($category === 'cases' && !empty($subCategory)) {
                    // 工程案例的路径处理
                    $folderMap = [
                        'hospital' => '101',
                        'station' => '102',
                        'hotel' => '103',
                        'other' => '104'
                    ];
                    $targetFolder = isset($folderMap[$subCategory]) ? $folderMap[$subCategory] : 'cases_images';
                    $relativePath = $targetFolder . '/' . $file;
                } else {
                    $relativePath = $category . '_images/' . $file;
                }
                
                error_log("找到文件: " . $file);
                error_log("相对路径: " . $relativePath);
                
                // 检查文件是否真实存在
                if (file_exists($uploadDir . $file)) {
                    error_log("文件确实存在: " . $uploadDir . $file);
                    $images[] = [
                        'path' => $relativePath,
                        'name' => $file
                    ];
                } else {
                    error_log("文件不存在: " . $uploadDir . $file);
                }
            }
        }
    }
    closedir($handle);
}

// 返回结果
echo json_encode([
    'success' => true,
    'images' => $images,
    'debug' => [
        'category' => $category,
        'subCategory' => $subCategory,
        'uploadDir' => $uploadDir,
        'fileCount' => count($images)
    ]
]);
?> 