<?php
require_once 'admin/config.php';

// 获取数据库连接
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("数据库连接失败");
}
$conn->set_charset("utf8mb4");

// 获取所有新闻
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
$news = [];
while ($row = $result->fetch_assoc()) {
    $news[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新闻资讯 - 闽晨石材有限公司</title>
    <link href="./css/tailwind.min.css" rel="stylesheet"/>
    <link href="test_images/logo.png" rel="icon" type="image/png"/>
    <style>
        html { scroll-behavior: smooth; }
        .main-title {
            white-space: nowrap;
            font-size: 2.2rem; 
            font-weight: 900; 
            letter-spacing: 0.08em;
            background: linear-gradient(90deg, #4f8cff 0%, #a084ee 100%);
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent;
            text-shadow: 0 4px 16px rgba(80, 120, 255, 0.12);
            border-radius: 10px; 
            padding: 4px 16px;
            transition: box-shadow 0.3s; 
            box-shadow: 0 2px 12px rgba(80, 120, 255, 0.08);
        }
        .main-title:hover { 
            box-shadow: 0 4px 32px rgba(80, 120, 255, 0.18); 
        }
        .section-title {
            font-size: 2rem; 
            font-weight: 800; 
            letter-spacing: 0.06em; 
            color: #4f8cff;
            text-shadow: 0 2px 8px rgba(80, 120, 255, 0.10); 
            margin-bottom: 0.5em;
        }
        .nav-btn {
            display: inline-block; 
            padding: 0.5rem 1.2rem; 
            margin: 0 0.2rem;
            font-weight: 600; 
            font-size: 1rem; 
            color: #4f8cff; 
            background: #fff;
            border: 2px solid; 
            border-image: linear-gradient(90deg, #4f8cff 0%, #a084ee 100%);
            border-image-slice: 1; 
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(80, 120, 255, 0.06);
            transition: all 0.25s; 
            position: relative; 
            overflow: hidden;
        }
        .nav-btn:hover, .nav-btn.active {
            color: #fff; 
            background: linear-gradient(90deg, #4f8cff 0%, #a084ee 100%);
            box-shadow: 0 6px 20px rgba(80, 120, 255, 0.25); 
            border-color: transparent;
        }
        .article-content {
            display: none;
            padding: 1.5rem;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        .article-content.active {
            display: block;
        }
        .nav-item {
            display: block;
            padding: 0.75rem 1.25rem;
            margin-bottom: 0.5rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: all 0.2s;
        }
        .nav-item:hover, .nav-item.active {
            background: linear-gradient(90deg, #4f8cff 0%, #a084ee 100%);
            color: white;
            transform: translateX(5px);
        }
        @media (max-width: 900px) {
            .nav-btn { 
                margin: 0.2rem 0.1rem; 
                font-size: 0.98rem; 
                padding: 0.45rem 1rem; 
            }
        }
        @media (max-width: 600px) {
            .main-title {
                font-size: 1.4rem; 
                padding: 2px 8px; 
            }
            .section-title { 
                font-size: 1.1rem; 
            }
            .nav-btn { 
                font-size: 0.92rem; 
                padding: 0.38rem 0.7rem; 
            }
            .container.mx-auto.px-4.py-4.flex {
                flex-direction: column;
                align-items: center;
            }
            nav.flex-wrap {
                width: 100%;
                margin-top: 0.5rem;
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="site_images/logo.jpg" alt="闽晨石材logo" class="h-10 w-auto">
                <h1 class="main-title">新闻资讯</h1>
            </div>
            <nav class="flex flex-wrap gap-2">
                <a href="index.html" class="nav-btn">首页</a>
                <a href="products.html" class="nav-btn">产品中心</a>
                <a href="finished.html" class="nav-btn">成品展示</a>
                <a href="cases.html" class="nav-btn">工程案例</a>
                <a href="news.php" class="nav-btn active">新闻资讯</a>
                <a href="about.html" class="nav-btn">关于我们</a>
                <a href="contact.html" class="nav-btn">联系我们</a>
            </nav>
        </div>
    </header>

    <div class="py-12 px-4 max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- 左侧导航栏 -->
            <aside class="w-full md:w-64">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h3 class="text-xl font-bold mb-4 text-blue-600">文章列表</h3>
                    <div class="space-y-2">
                        <?php foreach ($news as $index => $item): ?>
                        <div class="nav-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="showArticle('art<?php echo $item['id']; ?>')">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

            <!-- 右侧内容区 -->
            <main class="flex-1">
                <?php foreach ($news as $index => $item): ?>
                <div id="art<?php echo $item['id']; ?>" class="article-content <?php echo $index === 0 ? 'active' : ''; ?>">
                    <h2 class="text-2xl font-bold mb-4 text-blue-600"><?php echo htmlspecialchars($item['title']); ?></h2>
                    <div class="prose max-w-none">
                        <?php echo nl2br(htmlspecialchars($item['content'])); ?>
                    </div>
                    <div class="text-sm text-gray-500 mt-4">
                        发布时间：<?php echo date('Y-m-d H:i', strtotime($item['created_at'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </main>
        </div>
    </div>

    <footer class="bg-white py-6 border-t mt-8">
        <div class="text-center text-sm text-gray-500">
            &copy; 2025 福建泉州市闽晨石材有限公司 版权所有
        </div>
    </footer>

    <script>
        function showArticle(articleId) {
            // 隐藏所有文章内容
            document.querySelectorAll('.article-content').forEach(el => {
                el.classList.remove('active');
            });
            
            // 显示选中的文章
            document.getElementById(articleId).classList.add('active');
            
            // 更新导航项状态
            document.querySelectorAll('.nav-item').forEach(el => {
                el.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>
</body>
</html> 