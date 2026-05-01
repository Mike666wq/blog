<?php
// === 页面变量 ===
$pageTitle = '添加用户';
$pageGreeting = 'USER ADD';
$pageHeading = '添加<span>用户</span>';
$activeNav = 'users';

include_once("header.php");
?>

<div class="form-card">
    <form action="users.php" method="post">
        <div class="field">
            <label>用户名</label>
            <input type="text" name="username" placeholder="输入用户名" required>
        </div>
        <div class="field">
            <label>密码</label>
            <input type="password" name="password" placeholder="输入密码" required>
        </div>
        <div class="field">
            <label>确认密码</label>
            <input type="password" name="confirm_password" placeholder="再次输入密码" required>
        </div>
        <div class="field">
            <label>邮箱</label>
            <input type="email" name="email" placeholder="输入邮箱" required>
        </div>
        <div class="btn-group">
            <button type="submit" name="add" class="submit">添 加</button>
            <a href="users_list.php" class="submit">取 消</a>
        </div>
    </form>
</div>

    </div>
</body>
</html>
