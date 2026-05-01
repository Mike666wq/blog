<?php
// 引入数据库连接
include_once("db.php");

// === 页面变量 ===
$pageTitle = '评论列表';
$pageGreeting = 'COMMENT MANAGEMENT';
$pageHeading = '评论<span>列表</span>';
$activeNav = 'comments';
$pageTitleWrap = true; // 开启右侧按钮布局

include_once("header.php");

// 删除评论
if (isset($_REQUEST["del"])) {
    $id = $_REQUEST["id"];
    $sql = "delete from comments where id = $id";
    if (mysqli_query($conn, $sql) == TRUE) {
        echo "<script>location.href='comments_list.php';</script>";
    } else {
        echo "error:" . mysqli_error($conn);
    }
}
?>

<a href="article_add.php" class="add-btn" style="display:inline-block; margin-bottom:24px;">+ 添加评论</a>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>序号</th>
                <th>文章</th>
                <th>昵称</th>
                <th>发布时间</th>
                <th>内容</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "select * from comments order by id desc";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $sql = "select * from articles where id = '". $row['article_id']. "'";
                $article_result = mysqli_query($conn, $sql);
                $article_row = mysqli_fetch_assoc($article_result);
                $article_title = $article_row["title"];
            ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $article_title; ?></td>
                <td><?php echo $row["nick"]; ?></td>
                <td><?php echo $row["time"] ?></td>
                <td><?php echo $row["content"]?></td>
                <td>
                    <a href="?del&id=<?php echo $row['id']; ?>" class="delete-btn"
                    onclick="return confirm('确定删除此评论吗？')">删除</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    </div>
</body>
</html>
