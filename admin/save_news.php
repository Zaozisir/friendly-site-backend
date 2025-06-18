<?php
// 关闭错误显示
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// 错误处理函数
function handleError($message, $code = 500) {
    error_log("Error in save_news.php: " . $message);
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

// 检查数据库连接
try {
    require_once 'config.php';
    if (!isset($pdo)) {
        handleError('数据库连接未初始化');
    }
} catch (Exception $e) {
    handleError('数据库连接失败: ' . $e->getMessage());
}

// 检查news表是否存在
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'news'");
    if ($stmt->rowCount() == 0) {
        handleError('news表不存在');
    }
} catch (PDOException $e) {
    handleError('检查news表失败: ' . $e->getMessage());
}

try {
    // 获取POST数据
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        handleError('无效的请求数据');
    }

    // 验证必填字段
    if (empty($data['title']) || empty($data['content'])) {
        handleError('标题和内容不能为空');
    }

    // 处理新闻保存
    if (isset($data['id'])) {
        // 更新现有新闻
        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$data['title'], $data['content'], $data['id']]);
        $message = '新闻更新成功';
    } else {
        // 创建新新闻
        $stmt = $pdo->prepare("INSERT INTO news (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$data['title'], $data['content']]);
        $message = '新闻发布成功';
    }

    echo json_encode([
        'success' => true,
        'message' => $message
    ]);
} catch (PDOException $e) {
    handleError('保存新闻失败: ' . $e->getMessage());
}
?> 