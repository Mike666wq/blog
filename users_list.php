<?php
// 引入数据库连接
include_once("db.php");

// === 页面变量 ===
$pageTitle = '用户列表';
$pageGreeting = 'USER MANAGEMENT';
$pageHeading = '用户<span>列表</span>';
$activeNav = 'users';
$pageTitleWrap = true; // 开启右侧按钮布局

include_once("header.php");
?>

<a href="user_add.php" class="add-btn" style="display:inline-block; margin-bottom:24px;">+ 添加用户</a>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>头像</th>
                <th>用户名</th>
                <th>密码</th>
                <th>邮箱</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM users";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td>
                    <?php if (!empty($row['avatar'])) { ?>
                        <img src="<?php echo htmlspecialchars($row['avatar']); ?>?t=<?php echo time(); ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                    <?php } else { ?>
                        <span style="display:inline-block;width:40px;height:40px;border-radius:50%;background:#c8553d;color:#fff;line-height:40px;text-align:center;font-size:18px;">
                            <?php echo mb_substr($row['username'], 0, 1, 'UTF-8'); ?>
                        </span>
                    <?php } ?>
                </td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['password']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <a href="user_avatar.php?id=<?php echo $row['id']; ?>" class="edit-btn">头像</a>
                    <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">编辑</a>
                    <a href="users.php?del&id=<?php echo $row['id']; ?>" class="delete-btn"
                    onclick="return confirm('确定删除此用户吗？')">删除</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    </div>
</body>
</html>
