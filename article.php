<?php
// 设置字符编码
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
include_once("db.php");

// 获取文章ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 获取文章
$sql = "SELECT * FROM articles WHERE id = $id";
$result = mysqli_query($conn, $sql);
$article = mysqli_fetch_assoc($result);

// 如果文章不存在
if (!$article) {
    header('Location: index.php');
    exit;
}

// 获取更多文章（用于推荐）
$moreSql = "SELECT * FROM articles WHERE id != $id ORDER BY id DESC LIMIT 3";
$moreResult = mysqli_query($conn, $moreSql);

// 评论功能
$article_id = $_GET['id'];
if (count($_POST) > 0) {
    $nick = $_POST['nick'];
    $email = $_POST['email'];
    $content = $_POST['content'];
    $time = date('Y-m-d H:i:s');
    $sql = "INSERT INTO comments (article_id, nick, email, content, time) VALUES ($article_id, '$nick', '$email', '$content', '$time')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('评论成功'); location.href='article.php?id=$article_id';</script>";
    } else {
        echo "<script>alert('评论失败'); location.href='article.php?id=$article_id';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - bbben's Blog</title>
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
            line-height: 1.9;
            min-height: 100vh;
        }

        /* 导航 */
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

        .nav-back {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 1px;
            transition: color 0.3s;
        }

        .nav-back:hover {
            color: var(--accent);
        }

        /* 文章容器 */
        .article-container {
            max-width: 780px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* 文章头部 */
        .article-header {
            padding: 160px 0 80px;
            text-align: center;
        }

        .article-category {
            display: inline-block;
            font-size: 11px;
            letter-spacing: 3px;
            color: var(--accent);
            text-transform: uppercase;
            margin-bottom: 24px;
            padding: 6px 16px;
            border: 1px solid var(--accent);
            border-radius: 20px;
        }

        .article-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
            margin-bottom: 32px;
        }

        .article-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 32px;
            color: var(--muted);
            font-size: 14px;
        }

        .article-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .article-author {
            color: var(--accent);
            font-weight: 600;
        }

        /* 分隔装饰 */
        .article-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin: 48px 0;
        }

        .article-divider span {
            width: 60px;
            height: 1px;
            background: var(--border);
        }

        .article-divider .diamond {
            width: 8px;
            height: 8px;
            background: var(--accent);
            transform: rotate(45deg);
        }

        /* 文章内容 */
        .article-content {
            font-size: 17px;
            color: var(--fg);
            line-height: 2;
        }

        .article-content p {
            margin-bottom: 28px;
        }

        .article-content h2,
        .article-content h3 {
            font-family: 'Playfair Display', serif;
            color: var(--ink);
            margin: 48px 0 24px;
            line-height: 1.4;
        }

        .article-content h2 {
            font-size: 28px;
        }

        .article-content h3 {
            font-size: 22px;
        }

        .article-content blockquote {
            margin: 40px 0;
            padding: 32px 40px;
            background: rgba(200, 85, 61, 0.04);
            border-left: 4px solid var(--accent);
            border-radius: 0 8px 8px 0;
            font-style: italic;
            color: var(--muted);
        }

        .article-content code {
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            background: rgba(0, 0, 0, 0.05);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .article-content pre {
            background: var(--ink);
            color: #f8f8f2;
            padding: 24px 28px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 32px 0;
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 14px;
            line-height: 1.7;
        }

        .article-content pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 8px;
            margin: 32px 0;
        }

        .article-content a {
            color: var(--accent);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color 0.3s;
        }

        .article-content a:hover {
            border-bottom-color: var(--accent);
        }

        /* 文章底部 */
        .article-footer {
            margin-top: 80px;
            padding-top: 48px;
            border-top: 1px solid var(--border);
        }

        .article-tags {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        .article-tag {
            font-size: 12px;
            color: var(--muted);
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .article-tag:hover {
            color: var(--accent);
            border-color: var(--accent);
        }

        .article-share {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* ====== 评论区域 ====== */
        .comments-section {
            margin-top: 100px;
            padding-top: 80px;
            border-top: 1px solid var(--border);
        }

        .comments-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .comments-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .comments-subtitle {
            font-size: 13px;
            color: var(--muted);
            letter-spacing: 2px;
        }

        .comment-form {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 40px 48px;
            margin-bottom: 60px;
        }

        .comment-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .comment-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .comment-form-group label {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
        }

        .comment-form-group input,
        .comment-form-group textarea {
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            color: var(--fg);
            background: var(--bg);
            transition: border-color 0.3s;
        }

        .comment-form-group input:focus,
        .comment-form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        .comment-form-group input::placeholder,
        .comment-form-group textarea::placeholder {
            color: #bbb;
        }

        .comment-form-group textarea {
            min-height: 140px;
            resize: vertical;
            line-height: 1.7;
        }

        .comment-form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-hint {
            font-size: 12px;
            color: var(--muted);
        }

        .comment-submit {
            padding: 12px 32px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .comment-submit:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }

        /* 评论列表 */
        .comments-list {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .comment-item {
            display: flex;
            gap: 20px;
            padding: 28px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: all 0.3s;
        }

        .comment-item:hover {
            border-color: rgba(200, 85, 61, 0.3);
        }

        .comment-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .comment-body {
            flex: 1;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
        }

        .comment-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
        }

        .comment-time {
            font-size: 12px;
            color: var(--muted);
        }

        .comment-content {
            font-size: 15px;
            color: var(--fg);
            line-height: 1.8;
        }

        .comment-reply {
            margin-top: 16px;
            font-size: 13px;
            color: var(--accent);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.3s;
        }

        .comment-reply:hover {
            gap: 10px;
        }

        /* 嵌套回复 */
        .comment-replies {
            margin-top: 24px;
            padding-left: 24px;
            border-left: 2px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .comment-replies .comment-item {
            background: rgba(0, 0, 0, 0.02);
        }

        .comments-empty {
            text-align: center;
            padding: 60px 0;
            color: var(--muted);
        }

        .comments-empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .comments-empty p {
            font-size: 15px;
        }

        .share-label {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .share-btn:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* 推荐阅读 */
        .more-section {
            margin-top: 100px;
            padding: 80px 0;
            background: linear-gradient(180deg, transparent 0%, rgba(200, 85, 61, 0.03) 100%);
        }

        .more-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .more-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .more-subtitle {
            font-size: 13px;
            color: var(--muted);
            letter-spacing: 2px;
        }

        .more-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .more-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 28px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .more-card:hover {
            border-color: var(--accent);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }

        .more-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 12px;
            line-height: 1.5;
            transition: color 0.3s;
        }

        .more-card:hover .more-card-title {
            color: var(--accent);
        }

        .more-card-meta {
            font-size: 12px;
            color: var(--muted);
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

        /* 响应式 */
        @media (max-width: 768px) {
            .nav {
                padding: 0 24px;
            }

            .nav-links {
                display: none;
            }

            .article-title {
                font-size: 32px;
            }

            .article-container {
                padding: 0 24px;
            }

            .more-grid {
                grid-template-columns: 1fr;
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
        <a href="index.php" class="nav-back">← 返回首页</a>
        <div class="nav-links">
            <a href="index.php">首页</a>
            <a href="about.php">关于</a>
            <a href="login.php">管理</a>
        </div>
    </nav>

    <!-- 文章主体 -->
    <article>
        <header class="article-header">
            <div class="article-container">
                <span class="article-category">Article</span>
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span class="article-author"><?php echo htmlspecialchars($article['author']); ?></span>
                    <span><?php echo date('Y年m月d日', strtotime($article['time'])); ?></span>
                </div>
            </div>
        </header>

        <div class="article-divider">
            <span></span>
            <div class="diamond"></div>
            <span></span>
        </div>

        <div class="article-container">
            <div class="article-content">
                <?php echo $article['content']; ?>
            </div>

            <footer class="article-footer">
                <div class="article-tags">
                    <a href="#" class="article-tag">随笔</a>
                    <a href="#" class="article-tag">技术</a>
                    <a href="#" class="article-tag">生活</a>
                </div>
                <div class="article-share">
                    <span class="share-label">Share</span>
                    <a href="#" class="share-btn" title="分享到微博">微</a>
                    <a href="#" class="share-btn" title="分享到微信">微</a>
                    <a href="#" class="share-btn" title="复制链接">链</a>
                </div>
            </footer>
        </div>

        <!-- 评论区域 -->
        <section class="comments-section" id="comments">
            <div class="comments-header">
                <h2 class="comments-title">留下你的想法</h2>
                <p class="comments-subtitle">Comments · 期待你的声音</p>
            </div>

            <!-- 评论表单 -->
            <form class="comment-form" action="" method="post">
                <div class="comment-form-row">
                    <div class="comment-form-group">
                        <label>昵称</label>
                        <input type="text" name="nick" placeholder="你怎么称呼？" required>
                    </div>
                    <div class="comment-form-group">
                        <label>邮箱</label>
                        <input type="email" name="email" placeholder="邮箱不会被公开">
                    </div>
                </div>
                <div class="comment-form-group">
                    <label>评论内容</label>
                    <textarea name="content" placeholder="有什么想法？在这里写下你的评论..." required></textarea>
                </div>
                <div class="comment-form-footer">
                    <span class="comment-hint">文明评论，友善交流</span>
                    <button type="submit" class="comment-submit">发 表</button>
                </div>
            </form>

            <!-- 评论列表 -->
            <div class="comments-list">

                <?php
                $sql = "select * from comments where article_id = {$article_id} order by id desc";
                $result = mysqli_query($conn, $sql);
                // 如果查询失败，则输出暂无评论
                if (!$result) {
                    echo "<div class='comment-item'><p>暂无评论</p></div";
                    return;

                }
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <!-- 示例评论1 -->
                <div class="comment-item">
                    <div class="comment-avatar"><?php echo mb_substr($row["nick"], 0, 1, 'utf-8');?></div>
                    <div class="comment-body">
                        <div class="comment-header">
                            <span class="comment-name"><?php echo $row["nick"]; ?></span>
                            <span class="comment-time"><?php echo date('Y年m月d日', strtotime($row['time'])); ?></span>
                        </div>
                        <div class="comment-content">
                            <p class="comment-content"><?php echo $row["content"]; ?></p>
                        </div>
                        <div class="comment-reply">↩ 回复</div>
                    </div>
                </div>
                <?php
                }
                ?>
                <!-- 示例评论2 -->
                <div class="comment-item">
                    <div class="comment-avatar">王</div>
                    <div class="comment-body">
                        <div class="comment-header">
                            <span class="comment-name">王小明</span>
                            <span class="comment-time">2026年4月18日</span>
                        </div>
                        <div class="comment-content">
                            非常有用的分享，对于我这种初学者来说帮助很大，期待更多内容！
                        </div>
                        <div class="comment-reply">↩ 回复</div>

                        <!-- 嵌套回复 -->
                        <div class="comment-replies">
                            <div class="comment-item">
                                <div class="comment-avatar">作</div>
                                <div class="comment-body">
                                    <div class="comment-header">
                                        <span class="comment-name">作者</span>
                                        <span class="comment-time">2026年4月19日</span>
                                    </div>
                                    <div class="comment-content">
                                        感谢支持！会继续努力产出更多优质内容的 😊
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>

    <!-- 推荐阅读 -->
    <?php if (mysqli_num_rows($moreResult) > 0) { ?>
    <section class="more-section">
        <div class="article-container">
            <div class="more-header">
                <h2 class="more-title">继续阅读</h2>
                <p class="more-subtitle">More Articles</p>
            </div>
            <div class="more-grid">
                <?php while ($more = mysqli_fetch_assoc($moreResult)) { ?>
                <a href="article.php?id=<?php echo $more['id']; ?>" class="more-card">
                    <h3 class="more-card-title"><?php echo htmlspecialchars($more['title']); ?></h3>
                    <div class="more-card-meta"><?php echo date('Y.m.d', strtotime($more['time'])); ?></div>
                </a>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php } ?>

    <!-- 页脚 -->
    <footer class="footer">
        <div class="article-container">
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
</body>
</html>