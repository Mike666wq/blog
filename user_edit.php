<?php
// === 页面变量 ===
$pageTitle = '编辑用户';
$pageGreeting = 'USER EDIT';
$pageHeading = '编辑<span>用户</span>';
$activeNav = 'users';

// 引入公共头部
include_once("header.php");

// 获取用户数据
$userid = intval($_REQUEST['id']);
$sql = "SELECT * FROM users WHERE id = $userid";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<div class="form-card">
    <form action="users.php" method="post">
        <div class="field">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        </div>
        <div class="field">
            <label>用户名</label>
            <input type="text" name="username" placeholder="用户名" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="field">
            <label>密码</label>
            <input type="password" name="password" placeholder="留空不修改">
            <div style="font-size:12px; color:#bbb; margin-top:6px; letter-spacing:1px;">密码留空则不修改</div>
        </div>
        <div class="field">
            <label>确认密码</label>
            <input type="password" name="confirm_password" placeholder="再次输入密码">
        </div>
        <div class="field">
            <label>邮箱</label>
            <input type="email" name="email" placeholder="邮箱" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="btn-group">
            <button type="submit" name="update" class="submit">保 存</button>
            <a href="users_list.php" class="submit">取 消</a>
        </div>
    </form>
</div>

    </div>
</body>
</html>
