<?php
header('Content-Type: application/json');

// 获取POST数据
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// 验证用户名和密码
if ($username === 'admin' && $password === 'minchen123') {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => '用户名或密码错误'
    ]);
}
?> 