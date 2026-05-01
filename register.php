<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册</title>
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
            overflow: hidden;
        }

        /* 左侧品牌区 */
        .brand {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
        }

        .brand::after {
            content: '';
            position: absolute;
            right: 0;
            top: 15%;
            height: 70%;
            width: 1px;
            background: var(--border);
        }

        .brand .line {
            width: 48px;
            height: 2px;
            background: var(--accent);
            margin-bottom: 32px;
        }

        .brand h2 {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.3;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .brand h2 span {
            color: var(--accent);
        }

        .brand p {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
            max-width: 360px;
        }

        .brand .hint {
            margin-top: 60px;
            color: #bbb;
            font-size: 12px;
            letter-spacing: 4px;
            text-transform: uppercase;
        }

        /* 右侧注册区 */
        .register {
            width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
        }

        .register .greeting {
            font-size: 13px;
            color: var(--muted);
            letter-spacing: 6px;
            margin-bottom: 12px;
        }

        .register h1 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 48px;
        }

        .field {
            margin-bottom: 28px;
            position: relative;
        }

        .field label {
            display: block;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .field input {
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

        .field input:focus {
            border-bottom-color: var(--accent);
        }

        .field input::placeholder {
            color: #bbb;
        }

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
            margin-top: 12px;
        }

        .submit:hover {
            opacity: 0.85;
        }

        .bottom {
            margin-top: 40px;
            font-size: 13px;
            color: var(--muted);
        }

        .bottom a {
            color: var(--fg);
            text-decoration: none;
        }

        .bottom a:hover {
            color: var(--accent);
        }

        /* 装饰性角标 */
        .corner {
            position: fixed;
            font-size: 11px;
            color: #ccc;
            letter-spacing: 2px;
        }
        .corner.tl { top: 24px; left: 28px; }
        .corner.br { bottom: 24px; right: 28px; }

        /* 右侧微妙的噪点纹理 */
        .register::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-conic-gradient(#f0ede8 0% 25%, transparent 0% 50%) 0 0 / 3px 3px;
            opacity: 0.3;
            pointer-events: none;
        }

        @media (max-width: 860px) {
            .brand { display: none; }
            .register { width: 100%; }
            .brand::after { display: none; }
        }
    </style>
</head>
<body>
    <span class="corner tl">v2.0</span>
    <span class="corner br">z.ai</span>

    <div class="brand">
        <div class="line"></div>
        <h2>以简驭繁，<br><span>恰到好处。</span></h2>
        <p>不堆砌，不迎合。<br>好的界面应该安静地待在那里，<br>等你需要的时候，一触即达。</p>
        <div class="hint">DESIGNED WITH INTENTION</div>
    </div>

    <div class="register">
        <div class="greeting">CREATE ACCOUNT</div>
        <h1>创建新账号</h1>

        <form action="users.php" method="POST">
            <div class="field">
                <label>账号</label>
                <input type="text" name="username" placeholder="用户名" required>
            </div>

            <div class="field">
                <label>密码</label>
                <input type="password" name="password" placeholder="设置密码" required>
            </div>

            <div class="field">
                <label>确认密码</label>
                <input type="password" name="confirm_password" placeholder="再次输入密码" required>
            </div>

            <div class="field">
                <label>邮箱</label>
                <input type="text" name="email" placeholder="输入邮箱" required>
            </div>

            <input type="submit" class="submit" name="register" value="注 册">
        </form>

        <div class="bottom">
            已有账号？ <a href="login.php">返回登录</a>
        </div>
    </div>
</body>
</html>
