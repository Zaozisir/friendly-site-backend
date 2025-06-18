<?php
// 关闭错误显示
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 错误处理函数
function handleError($message, $code = 500) {
    error_log("Error in get_news.php: " . $message);
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
    // 获取所有新闻
    $stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 返回新闻列表
    echo json_encode([
        'success' => true,
        'data' => $news
    ]);
} catch (PDOException $e) {
    handleError('获取新闻列表失败: ' . $e->getMessage());
}
?> 