<?php
// === 页面变量 ===
$pageTitle = '添加文章';
$pageGreeting = 'ARTICLE ADD';
$pageHeading = '添加<span>文章</span>';
$activeNav = 'articles';

include_once("db.php");
// 引入公共头部（包含 db 连接，必须在表单处理之前）
include_once("header.php");

// 表单提交处理
if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $content = $_POST["content"];
    $time = date("Y-m-d H:i:s");
    $sql = "INSERT INTO articles (title, author, content, time) VALUES ('$title', '$author', '$content', '$time')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('文章添加成功');location.href='articles_list.php'</script>";
    } else {
        echo "<script>alert('添加失败');history.back();</script>";
    }
    exit;
}
?>

<div class="form-card">
    <form action="" method="post">
        <div class="field">
            <label>标题</label>
            <input type="text" name="title" placeholder="输入文章标题" required>
        </div>
        <div class="field">
            <label>作者</label>
            <input type="text" name="author" placeholder="输入文章作者" required>
        </div>
        <div class="field">
            <label>内容</label>
            <textarea id="content" name="content" placeholder="输入文章内容" style="width:100%;height:400px;"></textarea>
        </div>
        <div class="btn-group">
            <button type="submit" class="submit">发 布</button>
            <a href="articles_list.php" class="submit">取 消</a>
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