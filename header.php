<?php
// 统一设置时区
date_default_timezone_set('Asia/Shanghai');

// 设置字符编码头（必须在任何输出之前）
header('Content-Type: text/html; charset=utf-8');

// 引入数据库连接和会话初始化
include_once("db.php");
session_start();

// 检查用户是否已登录
if (!isset($_SESSION['username'])) {
    echo "<script>alert('请先登录'); location.href = 'login.php';</script>";
    exit();
}

// 获取当前用户头像信息
$currentUserAvatar = '';
$currentUserInitial = isset($_SESSION['username']) ? mb_substr($_SESSION['username'], 0, 1, 'UTF-8') : '?';
if (isset($_SESSION['userid'])) {
    $userId = intval($_SESSION['userid']);
    $userQuery = mysqli_query($conn, "SELECT avatar FROM users WHERE id = $userId");
    if ($userQuery) {
        $userRow = mysqli_fetch_assoc($userQuery);
        // 直接使用数据库中的头像路径
        if ($userRow && !empty($userRow['avatar'])) {
            $avatarPath = $userRow['avatar'];
            // 检查文件是否存在
            if (file_exists($avatarPath)) {
                $currentUserAvatar = $avatarPath;
            }
        }
    }
}

// 页面标题（默认空，会被各页面覆盖）
$pageTitle = isset($pageTitle) ? $pageTitle : '后台管理';
$activeNav  = isset($activeNav)  ? $activeNav  : '';
$pageGreeting = isset($pageGreeting) ? $pageGreeting : '';
$pageHeading  = isset($pageHeading)  ? $pageHeading  : '';
$pageTitleWrap = !empty($pageTitleWrap);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网站后台管理 - <?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #f5f5f0;
            --card: #ffffff;
            --fg: #333333;
            --muted: #999999;
            --accent: #c8553d;
            --border: #e0ddd8;
        }

        body {
            font-family: 'Noto Serif SC', serif;
            min-height: 100vh;
            background: var(--bg);
            color: var(--fg);
            display: flex;
        }

        /* ========== 左侧导航栏 ========== */
        .sidebar {
            width: 240px;
            background: var(--card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 10;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar-brand {
            padding: 36px 32px 28px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand .line {
            width: 32px;
            height: 2px;
            background: var(--accent);
            margin-bottom: 16px;
        }

        .sidebar-brand h2 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .sidebar-brand h2 span {
            color: var(--accent);
        }

        .sidebar-nav {
            padding: 24px 0;
            flex: 1;
        }

        .nav-label {
            font-size: 11px;
            color: #bbb;
            letter-spacing: 4px;
            text-transform: uppercase;
            padding: 0 32px;
            margin-bottom: 16px;
        }

        .nav-item {
            display: block;
            padding: 12px 32px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            letter-spacing: 1px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            color: var(--fg);
            background: rgba(200, 85, 61, 0.05);
        }

        .nav-item.active {
            color: var(--accent);
            border-left-color: var(--accent);
            background: rgba(200, 85, 61, 0.08);
        }

        .sidebar-footer {
            padding: 24px 32px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: #bbb;
            letter-spacing: 2px;
        }

        /* ========== 右侧主内容区 ========== */
        .main {
            margin-left: 240px;
            flex: 1;
            padding: 60px;
            position: relative;
        }

        .main::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-conic-gradient(#f0ede8 0% 25%, transparent 0% 50%) 0 0 / 3px 3px;
            opacity: 0.3;
            pointer-events: none;
        }

        .page-header {
            margin-bottom: 48px;
            position: relative;
        }

        .page-header .greeting {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 6px;
            margin-bottom: 12px;
        }

        .page-header h1 {
            font-size: 30px;
            font-weight: 700;
        }

        .page-header .title-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 span {
            color: var(--accent);
        }

        /* ========== 表格 ========== */
        .table-wrap {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            padding: 16px 28px;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
            background: rgba(200, 85, 61, 0.03);
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 14px 28px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
            color: var(--fg);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: rgba(200, 85, 61, 0.03);
        }

        /* ========== 表单卡片 ========== */
        .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 48px;
            max-width: 600px;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .field {
            margin-bottom: 32px;
        }

        .field label {
            display: block;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 12px 0;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--border);
            color: var(--fg);
            font-size: 16px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.3s;
        }

        .field textarea {
            min-height: 160px;
            resize: vertical;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 12px;
            background: var(--bg);
        }

        .field input:focus,
        .field textarea:focus {
            border-color: var(--accent);
        }

        .field input::placeholder,
        .field textarea::placeholder {
            color: #bbb;
        }

        /* ========== 提交按钮 ========== */
        .submit {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            border: none;
            color: #fff;
            font-size: 15px;
            font-family: inherit;
            letter-spacing: 4px;
            cursor: pointer;
            transition: opacity 0.3s;
            border-radius: 4px;
            margin-top: 12px;
        }

        .submit:hover {
            opacity: 0.85;
        }

        /* ========== 按钮组 ========== */
        .btn-group {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }

        .btn-group .submit {
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        /* ========== 操作按钮 ========== */
        .edit-btn,
        .delete-btn,
        .add-btn {
            display: inline-block;
            padding: 5px 16px;
            font-size: 12px;
            font-family: inherit;
            letter-spacing: 1px;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .add-btn {
            padding: 10px 20px;
            background: var(--accent);
            color: #fff;
        }

        .add-btn:hover {
            opacity: 0.85;
        }

        .edit-btn {
            color: var(--accent);
            border: 1px solid var(--accent);
            margin-right: 10px;
        }

        .edit-btn:hover {
            background: var(--accent);
            color: #fff;
        }

        .delete-btn {
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .delete-btn:hover {
            background: #c8553d;
            color: #fff;
            border-color: #c8553d;
        }

        /* ========== 装饰性角标 ========== */
        .corner {
            position: fixed;
            font-size: 11px;
            color: #ccc;
            letter-spacing: 2px;
            z-index: 20;
        }
        .corner.br { bottom: 24px; right: 28px; }

        /* ========== 退出登录 ========== */
        .logout {
            display: block;
            padding: 12px 32px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            letter-spacing: 1px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .logout:hover {
            color: #c53030;
            background: rgba(197, 48, 48, 0.05);
        }

        /* ========== 右上角用户状态组件 ========== */
        .user-widget {
            position: fixed;
            top: 20px;
            right: 28px;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px 8px 8px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .user-widget:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        }

        .user-widget .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-widget .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-widget .info {
            display: flex;
            flex-direction: column;
        }

        .user-widget .username {
            font-size: 13px;
            font-weight: 600;
            color: var(--fg);
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .user-widget .status {
            font-size: 11px;
            color: #4ade80;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .user-widget .status::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #4ade80;
            border-radius: 50%;
        }

        .user-widget .arrow {
            font-size: 10px;
            color: var(--muted);
            transition: transform 0.3s;
            margin-left: 4px;
        }

        .user-widget.open .arrow {
            transform: rotate(180deg);
        }

        /* 下拉菜单 */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 180px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .user-widget.open .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--fg);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }

        .user-dropdown-item:hover {
            background: rgba(200, 85, 61, 0.08);
            color: var(--accent);
        }

        .user-dropdown-item:first-child {
            border-radius: 7px 7px 0 0;
        }

        .user-dropdown-item:last-child {
            border-radius: 0 0 7px 7px;
        }

        .user-dropdown-item.danger {
            color: #c53030;
        }

        .user-dropdown-item.danger:hover {
            background: rgba(197, 48, 48, 0.08);
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 4px 0;
        }

        .user-dropdown-icon {
            font-size: 14px;
            width: 18px;
            text-align: center;
        }

        /* ========== 响应式 ========== */
        @media (max-width: 600px) {
            .user-widget .info {
                display: none;
            }
            .user-widget {
                padding: 8px;
            }
        }

        @media (max-width: 860px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
        }
    </style>
</head>
<body>
    <span class="corner br">z.ai</span>

    <!-- 左侧导航栏 -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="line"></div>
            <h2>bbben的<span>小站</span></h2>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">管理面板</div>
            <a href="main.php" class="nav-item<?php echo $activeNav === 'main' ? ' active' : ''; ?>">网站信息</a>
            <a href="articles_list.php" class="nav-item<?php echo $activeNav === 'articles' ? ' active' : ''; ?>">文章管理</a>
            <a href="users_list.php" class="nav-item<?php echo $activeNav === 'users' ? ' active' : ''; ?>">用户管理</a>
            <a href="comments_list.php" class="nav-item<?php echo $activeNav === 'comments' ? ' active' : ''; ?>">评论管理</a>

        </nav>

        <a href="login.php?action=logout" class="logout">退出登录</a>

        <div class="sidebar-footer">v2.0 · DASHBOARD</div>
    </aside>

    <!-- 右侧主内容 -->
    <div class="main">
        <!-- 右上角用户状态组件 -->
        <div class="user-widget" id="userWidget" onclick="toggleUserMenu()">
            <div class="avatar">
                <?php if (!empty($currentUserAvatar) && file_exists($currentUserAvatar)) { ?>
                <img src="<?php echo htmlspecialchars($currentUserAvatar); ?>?t=<?php echo time(); ?>" alt="头像">
                <?php } else { ?>
                <span><?php echo htmlspecialchars($currentUserInitial); ?></span>
                <?php } ?>
            </div>
            <div class="info">
                <div class="username"><?php echo htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : '未知用户'); ?></div>
                <div class="status">在线</div>
            </div>
            <div class="arrow">▼</div>
            <div class="user-dropdown" id="userDropdown">
                <a href="login.php?action=logout" class="user-dropdown-item danger">
                    <span class="user-dropdown-icon">⏻</span>
                    退出登录
                </a>
            </div>
        </div>

        <div class="page-header">
            <?php if ($pageTitleWrap) { ?>
            <div class="title-wrap">
            <?php } ?>
            <div class="greeting"><?php echo htmlspecialchars($pageGreeting); ?></div>
            <h1><?php echo $pageHeading; ?></h1>
            <?php if ($pageTitleWrap) { ?>
            </div>
            <?php } ?>
        </div>

        <script>
        // 用户菜单展开/收起
        function toggleUserMenu() {
            var widget = document.getElementById('userWidget');
            widget.classList.toggle('open');
        }

        // 点击其他地方关闭菜单
        document.addEventListener('click', function(e) {
            var widget = document.getElementById('userWidget');
            var dropdown = document.getElementById('userDropdown');
            if (widget && dropdown && !widget.contains(e.target)) {
                widget.classList.remove('open');
            }
        });
        </script>
