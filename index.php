<?php
// 设置字符编码
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
include_once("db.php");

// 获取文章列表
$sql = "SELECT * FROM articles ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bbben的小站</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #faf9f7;
            --card: #ffffff;
            --fg: #2c2c2c;
            --muted: #8a8a8a;
            --accent: #c8553d;
            --border: #e8e6e1;
            --ink: #1a1a1a;
        }

        body {
            font-family: 'Noto Serif SC', serif;
            background: var(--bg);
            color: var(--fg);
            line-height: 1.8;
            min-height: 100vh;
        }

        /* 顶部导航 */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(250, 249, 247, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 60px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            color: var(--ink);
            text-decoration: none;
            letter-spacing: 1px;
        }

        .nav-brand span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 40px;
        }

        .nav-links a {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 2px;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--accent);
            transition: width 0.3s;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* 主内容 */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* 滚动动画 */
        html {
            scroll-behavior: smooth;
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-delay-1 { transition-delay: 0.1s; }
        .animate-delay-2 { transition-delay: 0.2s; }
        .animate-delay-3 { transition-delay: 0.3s; }
        .animate-delay-4 { transition-delay: 0.4s; }
        .animate-delay-5 { transition-delay: 0.5s; }

        /* 英雄区域 */
        .hero {
            padding: 180px 0 120px;
            text-align: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(200, 85, 61, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-tag {
            font-size: 11px;
            letter-spacing: 6px;
            color: var(--accent);
            margin-bottom: 28px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 24px;
            line-height: 1.2;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero-desc {
            font-size: 16px;
            color: var(--muted);
            max-width: 520px;
            margin: 0 auto 48px;
            line-height: 1.9;
        }

        .hero-line {
            width: 60px;
            height: 2px;
            background: var(--accent);
            margin: 0 auto;
        }

        /* 文章列表 */
        .articles-section {
            padding: 80px 0 120px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 60px;
        }

        .section-title {
            font-size: 13px;
            letter-spacing: 4px;
            color: var(--muted);
            text-transform: uppercase;
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .articles-grid {
            display: grid;
            gap: 40px;
        }

        .article-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 48px 52px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .article-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--accent);
            transform: scaleY(0);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .article-card:hover {
            border-color: transparent;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .article-card:hover::before {
            transform: scaleY(1);
        }

        .article-card .article-read {
            transition: all 0.3s ease;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 16px;
        }

        .article-time {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 1px;
        }

        .article-author {
            font-size: 12px;
            color: var(--accent);
            letter-spacing: 1px;
        }

        .article-title {
            font-size: 26px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 12px;
            line-height: 1.4;
            transition: color 0.3s;
        }

        .article-card:hover .article-title {
            color: var(--accent);
        }

        .article-excerpt {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.8;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .article-read {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            font-size: 13px;
            color: var(--accent);
            letter-spacing: 1px;
            transition: gap 0.3s;
        }

        .article-card:hover .article-read {
            gap: 12px;
        }

        /* 页脚 */
        .footer {
            background: var(--ink);
            color: rgba(255, 255, 255, 0.5);
            padding: 80px 0 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }

        .footer-brand span {
            color: var(--accent);
        }

        .footer-desc {
            font-size: 14px;
            line-height: 1.8;
            max-width: 300px;
        }

        .footer-title {
            font-size: 12px;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 24px;
            text-transform: uppercase;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .footer-bottom a {
            color: var(--accent);
            text-decoration: none;
        }

        /* 空状态 */
        .empty-state {
            text-align: center;
            padding: 120px 0;
        }

        .empty-state h3 {
            font-size: 20px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            color: var(--muted);
        }

        /* 响应式 */
        @media (max-width: 768px) {
            .nav {
                padding: 0 24px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero {
                padding: 140px 0 80px;
            }

            .article-card {
                padding: 32px 28px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- 导航 -->
    <nav class="nav">
        <a href="index.php" class="nav-brand">bbben's <span>Blog</span></a>
        <div class="nav-links">
            <a href="index.php">首页</a>
            <a href="about.php">关于</a>
            <a href="login.php">管理</a>
        </div>
    </nav>

    <!-- 英雄区域 -->
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-tag animate-on-scroll">Welcome to my blog</div>
            <h1 class="animate-on-scroll animate-delay-1">记录<span>思考</span>，分享<span>成长</span></h1>
            <p class="hero-desc animate-on-scroll animate-delay-2">在这里，每一篇文章都是一次探索。希望能带给你一点启发，或者仅仅是一些阅读的乐趣。</p>
            <div class="hero-line animate-on-scroll animate-delay-3"></div>
        </div>
    </section>

    <!-- 文章列表 -->
    <section class="articles-section" id="articles">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-title">Latest Articles</span>
                <div class="section-line"></div>
            </div>

            <?php if (mysqli_num_rows($result) > 0) { ?>
            <div class="articles-grid">
                <?php $cardIndex = 0; while ($row = mysqli_fetch_assoc($result)) { 
                    // 去除HTML标签生成摘要
                    $excerpt = strip_tags($row['content']);
                    if (mb_strlen($excerpt) > 120) {
                        $excerpt = mb_substr($excerpt, 0, 120) . '...';
                    }
                    $delayClass = 'animate-delay-' . (($cardIndex % 5) + 1);
                    $cardIndex++;
                ?>
                <article class="article-card animate-on-scroll <?php echo $delayClass; ?>" onclick="location.href='article.php?id=<?php echo $row['id']; ?>'">
                    <div class="article-meta">
                        <span class="article-time"><?php echo date('Y.m.d', strtotime($row['time'])); ?></span>
                        <span class="article-author"><?php echo htmlspecialchars($row['author']); ?></span>
                    </div>
                    <h2 class="article-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p class="article-excerpt"><?php echo htmlspecialchars($excerpt); ?></p>
                    <div class="article-read">继续阅读 →</div>
                </article>
                <?php } ?>
            </div>
            <?php } else { ?>
            <div class="empty-state">
                <h3>暂无文章</h3>
                <p>看起来这里还没有内容，期待您的第一篇文章。</p>
            </div>
            <?php } ?>
        </div>
    </section>

    <!-- 页脚 -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-brand">bbben's <span>Blog</span></div>
                    <p class="footer-desc">以简驭繁，恰到好处。不堆砌，不迎合，好的界面应该安静地待在那里。</p>
                </div>
                <div>
                    <div class="footer-title">Navigation</div>
                    <div class="footer-links">
                        <a href="index.php">首页</a>
                        <a href="about.php">关于</a>
                        <a href="login.php">管理后台</a>
                    </div>
                </div>
                <div>
                    <div class="footer-title">Connect</div>
                    <div class="footer-links">
                        <a href="#">GitHub</a>
                        <a href="#">Weibo</a>
                        <a href="#">Email</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2026 bbben's Blog · 京ICP备xxxxx号</span>
                <a href="login.php">Admin →</a>
            </div>
        </div>
    </footer>

    <script>
        // 滚动动画 - Intersection Observer
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // 观察所有需要动画的元素
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // 页面加载时触发动画
        window.addEventListener('load', () => {
            document.querySelectorAll('.hero .animate-on-scroll').forEach(el => {
                el.classList.add('visible');
            });
        });
    </script>
</body>
</html>