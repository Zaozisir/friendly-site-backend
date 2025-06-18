<?php
require_once 'config.php';

// 开启错误显示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 记录错误日志
ini_set('log_errors', 1);
ini_set('error_log', 'upload_errors.log');

header('Content-Type: application/json');

// 记录上传信息
error_log("开始处理上传请求");
error_log("POST数据: " . print_r($_POST, true));
error_log("FILES数据: " . print_r($_FILES, true));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("请求方法错误: " . $_SERVER['REQUEST_METHOD']);
    die(json_encode(['success' => false, 'message' => '无效的请求方法']));
}

// 验证参数
if (!isset($_POST['category'])) {
    echo json_encode(['success' => false, 'message' => '缺少必要参数']);
    exit;
}

$category = $_POST['category'];
$subCategory = isset($_POST['subCategory']) ? $_POST['subCategory'] : '';

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
error_log("上传目录: " . $uploadDir);
error_log("分类: " . $category);
error_log("子分类: " . $subCategory);

$uploadedFiles = [];
$errors = [];

// 处理上传的文件
if (isset($_FILES['images'])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file = $_FILES['images']['name'][$key];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        // 检查文件类型
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'mp4'])) {
            $errors[] = "不支持的文件类型: $file";
            continue;
        }
        
        // 检查文件大小（20MB限制）
        if ($_FILES['images']['size'][$key] > 20 * 1024 * 1024) {
            $errors[] = "文件太大: $file";
            continue;
        }
        
        // 生成唯一文件名
        $newFileName = uniqid() . '.' . $ext;
        $targetPath = $uploadDir . $newFileName;
        
        // 移动文件
        if (move_uploaded_file($tmp_name, $targetPath)) {
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
                $relativePath = $targetFolder . '/' . $newFileName;
            } else if ($category === 'cases' && !empty($subCategory)) {
                // 工程案例的路径处理
                $folderMap = [
                    'hospital' => '101',
                    'station' => '102',
                    'hotel' => '103',
                    'other' => '104'
                ];
                $targetFolder = isset($folderMap[$subCategory]) ? $folderMap[$subCategory] : 'cases_images';
                $relativePath = $targetFolder . '/' . $newFileName;
            } else {
                $relativePath = $category . '_images/' . $newFileName;
            }
            
            $uploadedFiles[] = [
                'path' => $relativePath,
                'name' => $newFileName,
                'category' => $category,
                'subCategory' => $subCategory
            ];
        } else {
            $errors[] = "上传失败: $file";
        }
    }
}

// 返回结果
echo json_encode([
    'success' => count($errors) === 0,
    'message' => count($errors) > 0 ? implode(', ', $errors) : '上传成功',
    'files' => $uploadedFiles,
    'errors' => $errors,
    'debug' => [
        'category' => $category,
        'subCategory' => $subCategory,
        'uploadDir' => $uploadDir,
        'uploadedFiles' => $uploadedFiles
    ]
]);
?> 