<?php
// 关闭错误显示
ini_set('display_errors', 0);
error_reporting(E_ALL);

// 设置错误处理函数
function handleError($errno, $errstr, $errfile, $errline) {
    $error = [
        'type' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];
    error_log("Error: " . json_encode($error));
    echo json_encode(['success' => false, 'message' => '系统错误，请查看错误日志', 'debug' => $error]);
    exit;
}
set_error_handler('handleError');

try {
    session_start();
    
    // 检查session状态
    if (session_status() !== PHP_SESSION_ACTIVE) {
        throw new Exception('Session未启动');
    }
    
    // 检查是否已登录
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        echo json_encode(['success' => false, 'message' => '未登录']);
        exit;
    }
    
    require_once 'config.php';

    // 设置响应头为JSON
    header('Content-Type: application/json');

    // 检查数据库连接
    if (!isset($pdo)) {
        throw new Exception('数据库连接未初始化');
    }

    // 获取POST数据
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('没有接收到POST数据');
    }

    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON解析错误：' . json_last_error_msg());
    }

    if (!isset($data['id'])) {
        echo json_encode(['success' => false, 'message' => '缺少新闻ID']);
        exit;
    }

    $id = intval($data['id']);

    // 检查表是否存在
    $checkTable = $pdo->query("SHOW TABLES LIKE 'news'");
    if ($checkTable->rowCount() == 0) {
        echo json_encode(['success' => false, 'message' => '新闻表不存在']);
        exit;
    }

    // 删除新闻
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => '新闻删除成功']);
    } else {
        echo json_encode(['success' => false, 'message' => '新闻删除失败']);
    }
} catch (PDOException $e) {
    $error = [
        'type' => 'PDOException',
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
    error_log("Database error: " . json_encode($error));
    echo json_encode(['success' => false, 'message' => '数据库错误，请查看错误日志', 'debug' => $error]);
} catch (Exception $e) {
    $error = [
        'type' => 'Exception',
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ];
    error_log("General error: " . json_encode($error));
    echo json_encode(['success' => false, 'message' => '系统错误，请查看错误日志', 'debug' => $error]);
}
?> 