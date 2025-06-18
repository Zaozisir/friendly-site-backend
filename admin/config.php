<?php
$db_host = 'localhost';
$db_user = 'minchen';
$db_pass = '123456';
$db_name = 'minchen_admin';

// mysqli连接
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// PDO连接
try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch (PDOException $e) {
    die("PDO连接失败: " . $e->getMessage());
}
?> 