<?php
// 引入数据库连接
include_once("db.php");

// === 页面变量 ===
$pageTitle = '文章列表';
$pageGreeting = 'ARTICLE MANAGEMENT';
$pageHeading = '文章<span>列表</span>';
$activeNav = 'articles';
$pageTitleWrap = true; // 开启右侧按钮布局

include_once("header.php");

// 删除文章
if (isset($_REQUEST["del"])) {
    $id = $_REQUEST["id"];
    $sql = "delete from articles where id = $id";
    if (mysqli_query($conn, $sql) == TRUE) {
        echo "<script>alert('文章删除成功');location.href='articles_list.php'</script>";
    } else {
        echo "error:" . mysqli_error($conn);
    }
}
?>

<a href="article_add.php" class="add-btn" style="display:inline-block; margin-bottom:24px;">+ 添加文章</a>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>序号</th>
                <th>标题</th>
                <th>作者</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "select * from articles";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $title = mb_strlen($row["title"], 'UTF-8') > 15 ? mb_substr($row["title"], 0, 15, 'UTF-8') . "..." : $row["title"];
            ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($title); ?></td>
                <td><?php echo htmlspecialchars($row["author"]); ?></td>
                <td><?php echo htmlspecialchars($row["time"]); ?></td>
                <td>
                    <a href="article_edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">编辑</a>
                    <a href="?del&id=<?php echo $row['id']; ?>" class="delete-btn"
                    onclick="return confirm('确定删除此文章吗？')">删除</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    </div>
</body>
</html>
