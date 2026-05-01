<?php
// === 页面变量 ===
$pageTitle = '修改文章';
$pageGreeting = 'ARTICLE EDIT';
$pageHeading = '修改<span>文章</span>';
$activeNav = 'articles';

// 引入公共头部
include_once("db.php");
include_once("header.php");

// 获取文章数据
$id = intval($_REQUEST["id"]);
$sql = "SELECT * FROM articles WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// 处理更新
if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $content = $_POST["content"];
    $time = date("Y-m-d H:i:s");
    $sql = "UPDATE articles SET title = '$title', author = '$author', content = '$content', time = '$time' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('文章更新成功');location.href='articles_list.php'</script>";
    } else {
        echo "<script>alert('更新失败');history.back();</script>";
    }
    exit;
}
?>

<div class="form-card">
    <form action="" method="post">
        <div class="field">
            <label>标题</label>
            <input type="text" name="title" placeholder="输入文章标题" value="<?php echo htmlspecialchars($row["title"]); ?>" required>
        </div>
        <div class="field">
            <label>作者</label>
            <input type="text" name="author" placeholder="输入文章作者" value="<?php echo htmlspecialchars($row["author"]); ?>" required>
        </div>
        <div class="field">
            <label>内容</label>
            <textarea id="content" name="content" placeholder="输入文章内容" required style="width:100%;height:400px;"><?php echo htmlspecialchars($row["content"]); ?></textarea>
        </div>
        <div class="btn-group">
            <button type="submit" class="submit">更 新</button>
            <a href="articles_list.php" class="submit">返 回</a>
        </div>
    </form>
</div>

</div>
<!-- 引入 UEditor -->
<script src="ueditor/ueditor.config.js"></script>
<script src="ueditor/ueditor.all.min.js"></script>
<script src="ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    UE.getEditor('content', {
        initialFrameWidth: '100%',
        initialFrameHeight: 400,
        serverUrl: ''
    });
</script>
</body>

</html>