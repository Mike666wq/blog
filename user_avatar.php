<?php
// 引入数据库连接
include_once("db.php");

// === 页面变量 ===
$pageTitle = '修改头像';
$pageGreeting = 'AVATAR SETTINGS';
$pageHeading = '修改<span>头像</span>';
$activeNav = 'users';

include_once("header.php");

// 如果没有传入id，使用当前登录用户id
$userId = isset($_GET['id']) ? intval($_GET['id']) : intval($_SESSION['userid']);

// 获取用户信息
$sql = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('用户不存在');location.href='users_list.php';</script>";
    exit;
}

// 处理头像上传
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
    $uploadDir = 'images/avatar/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileExt = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $newFileName = 'avatar_' . $userId . '_' . time() . '.' . $fileExt;
    $uploadPath = $uploadDir . $newFileName;
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
            // 更新数据库
            $sql = "UPDATE users SET avatar = '$uploadPath' WHERE id = $userId";
            if (mysqli_query($conn, $sql)) {
                // 上传成功后刷新页面
                echo "<script>alert('头像上传成功！');location.href='user_avatar.php?id=$userId';</script>";
                exit;
            } else {
                $uploadError = '数据库更新失败';
            }
        } else {
            $uploadError = '文件上传失败';
        }
    } else {
        $uploadError = '只支持 JPG、PNG、GIF、WebP 格式图片';
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadError = '请选择图片文件';
}

// 刷新用户最新信息
$sql = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<style>
.avatar-page {
    max-width: 600px;
    margin: 0 auto;
}

.avatar-card {
    background: #fff;
    border-radius: 16px;
    padding: 48px 40px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
}

.avatar-preview-section {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid #e0ddd8;
}

.avatar-preview-ring {
    width: 160px;
    height: 160px;
    margin: 0 auto 20px;
    border-radius: 50%;
    padding: 6px;
    background: linear-gradient(135deg, #c8553d, #e8a87c);
    box-shadow: 0 8px 24px rgba(200, 85, 61, 0.25);
    transition: transform 0.3s ease;
}

.avatar-preview-ring:hover {
    transform: scale(1.05);
}

.avatar-preview-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: #f5f5f0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-preview-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-preview-inner .initial {
    font-size: 56px;
    color: #c8553d;
    font-weight: bold;
    font-family: 'Georgia', serif;
}

.username-label {
    font-size: 22px;
    color: #333;
    margin-bottom: 4px;
}

.change-hint {
    font-size: 14px;
    color: #999;
}

.upload-section h3 {
    font-size: 18px;
    color: #333;
    margin-bottom: 20px;
    font-weight: 500;
}

.upload-zone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px dashed #e0ddd8;
    border-radius: 12px;
    padding: 48px 24px;
    text-align: center;
    background: #fafaf8;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 24px;
    min-height: 200px;
}

.upload-zone:hover {
    border-color: #c8553d;
    background: #fff5f3;
}

.upload-zone.dragover {
    border-color: #c8553d;
    background: #fff5f3;
    transform: scale(1.02);
}

.upload-icon {
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    border-radius: 50%;
    background: linear-gradient(135deg, #c8553d, #e8a87c);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.upload-icon svg {
    width: 28px;
    height: 28px;
    fill: #fff;
}

.upload-zone p {
    color: #666;
    margin-bottom: 8px;
}

.upload-zone span {
    font-size: 13px;
    color: #999;
}

.btn-upload {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #c8553d, #d4694f);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(200, 85, 61, 0.3);
}

.btn-upload:hover {
    background: linear-gradient(135deg, #b04a35, #c8553d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(200, 85, 61, 0.4);
}

.btn-upload:active {
    transform: translateY(0);
}

.btn-upload:disabled {
    background: #ccc;
    cursor: not-allowed;
    box-shadow: none;
}

.back-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 24px;
    padding: 14px;
    color: #666;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.back-link:hover {
    background: #f5f5f0;
    color: #c8553d;
}

.back-link svg {
    width: 18px;
    height: 18px;
    fill: currentColor;
}

.alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
}

.alert-error {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ffcdd2;
}

.preview-area {
    margin-top: 16px;
    padding: 16px;
    background: #f5f5f0;
    border-radius: 10px;
    display: none;
}

.preview-area.show {
    display: flex;
    align-items: center;
    gap: 16px;
}

.preview-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-info {
    flex: 1;
}

.preview-name {
    font-size: 14px;
    color: #333;
    margin-bottom: 4px;
}

.preview-size {
    font-size: 12px;
    color: #999;
}

.preview-hint {
    font-size: 12px;
    color: #999;
    margin-top: 8px;
}

.tips-box {
    background: #f0f7ff;
    border: 1px solid #cce5ff;
    border-radius: 10px;
    padding: 16px 20px;
    margin-top: 24px;
}

.tips-box h4 {
    font-size: 14px;
    color: #004085;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tips-box ul {
    margin: 0;
    padding-left: 20px;
    color: #004085;
    font-size: 13px;
    line-height: 1.8;
}
</style>

<div class="avatar-page">
    <div class="avatar-card">
        <!-- 当前头像预览 -->
        <div class="avatar-preview-section">
            <div class="avatar-preview-ring">
                <div class="avatar-preview-inner">
                    <?php if (!empty($user['avatar']) && file_exists($user['avatar'])) { ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>?t=<?php echo time(); ?>" alt="当前头像">
                    <?php } else { ?>
                        <span class="initial"><?php echo mb_substr($user['username'], 0, 1, 'UTF-8'); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="username-label"><?php echo htmlspecialchars($user['username']); ?></div>
            <div class="change-hint">点击下方区域选择新头像</div>
        </div>

        <!-- 上传表单 -->
        <div class="upload-section">
            <?php if (!empty($uploadError)) { ?>
                <div class="alert alert-error">
                    <svg style="width:16px;height:16px;vertical-align:middle;margin-right:6px;fill:#c62828;" viewBox="0 0 20 20"><path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 0 0-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 1 0 1.414 1.414L10 11.414l1.293 1.293a1 1 0 0 0 1.414-1.414L11.414 10l1.293-1.293a1 1 0 0 0-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                    <?php echo $uploadError; ?>
                </div>
            <?php } ?>

            <h3>上传新头像</h3>
            
            <form method="post" enctype="multipart/form-data" id="avatarForm">
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" required style="display:none;">
                    <div class="upload-icon">
                        <svg viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                    </div>
                    <p id="uploadText">点击或拖拽图片到这里</p>
                    <span>支持 JPG、PNG、GIF、WebP 格式，建议尺寸 200x200</span>
                </div>
                
                <div class="preview-area" id="previewArea">
                    <img id="previewImg" class="preview-img" src="" alt="预览">
                    <div class="preview-info">
                        <div class="preview-name" id="previewName"></div>
                        <div class="preview-size" id="previewSize"></div>
                        <div class="preview-hint">点击下方按钮上传头像</div>
                    </div>
                </div>

                <button type="submit" class="btn-upload" id="submitBtn">
                    <svg style="width:18px;height:18px;vertical-align:middle;margin-right:8px;fill:currentColor;" viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/></svg>
                    保存头像
                </button>
            </form>

            <!-- 提示信息 -->
            <div class="tips-box">
                <h4>
                    <svg style="width:16px;height:16px;fill:#004085;" viewBox="0 0 20 20"><path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM9 9a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2H9z"/></svg>
                    上传提示
                </h4>
                <ul>
                    <li>支持 JPG、PNG、GIF、WebP 格式</li>
                    <li>建议图片尺寸不小于 100x100 像素</li>
                    <li>头像会自动保存到 images/avatar/ 目录</li>
                </ul>
            </div>

            <a href="main.php" class="back-link">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                返回主页
            </a>
        </div>
    </div>
</div>

<script>
// 点击上传区域触发文件选择
document.getElementById('uploadZone').addEventListener('click', function() {
    document.getElementById('avatarInput').click();
});

// 文件选择预览
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('previewImg').src = event.target.result;
            document.getElementById('previewName').textContent = file.name;
            document.getElementById('previewSize').textContent = formatSize(file.size);
            document.getElementById('previewArea').classList.add('show');
            document.getElementById('uploadText').textContent = '已选择图片';
        };
        reader.readAsDataURL(file);
    }
});

// 格式化文件大小
function formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// 拖拽上传
const uploadZone = document.getElementById('uploadZone');

uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('avatarInput').files = files;
        document.getElementById('avatarInput').dispatchEvent(new Event('change'));
    }
});
</script>

    </div>
</body>
</html>
