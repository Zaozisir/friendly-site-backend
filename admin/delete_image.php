<?php
require_once 'config.php';

header('Content-Type: application/json');

// 获取POST数据
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['path'])) {
    echo json_encode(['success' => false, 'message' => '缺少图片路径']);
    exit;
}

$imagePath = $data['path'];

// 构建完整的文件路径
$fullPath = '../' . $imagePath;

// 检查文件是否存在
if (!file_exists($fullPath)) {
    echo json_encode(['success' => false, 'message' => '文件不存在']);
    exit;
}

// 尝试删除文件
if (unlink($fullPath)) {
    echo json_encode(['success' => true, 'message' => '删除成功']);
} else {
    echo json_encode(['success' => false, 'message' => '删除失败']);
}
?> 