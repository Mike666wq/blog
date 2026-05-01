# 📝 AI-Assisted Personal Blog System

![GitHub last commit](https://img.shields.io/github/last-commit/Mike666wq/blog?color=green)
![GitHub repo size](https://img.shields.io/github/repo-size/Mike666wq/blog)
![GitHub language count](https://img.shields.io/github/languages/count/Mike666wq/blog)

## 📌 项目简介

这是一个由在校大学生主导开发的**全栈个人博客系统**。本项目不仅是用于记录技术成长的在线空间，更是我探索 **AI 驱动开发 (AI-Driven Development)** 模式的核心实验田。

在这个项目中，我利用大模型（如 Claude 3.5, GPT-4o）辅助完成了从数据库设计、后端 PHP 逻辑编写，到前端样式优化的全过程。项目旨在验证 AI 在降低 Web 开发门槛、提升初学者工程化能力方面的潜力。

## 🎯 项目背景与申请说明 (For Xiaomi Token Application)

作为一名计算机相关专业的在校大学生，我目前正处于 AI 编程学习的深水区。虽然我已借助 AI 完成了博客的基础骨架（CRUD 功能），但在复杂的业务逻辑和安全防护（如防 SQL 注入、XSS 过滤）上仍需更强大的模型支持。

*   **当前痛点**：在处理多表关联查询和用户鉴权逻辑时，普通模型生成的代码健壮性不足，常需反复 Debug。
*   **申请诉求**：希望通过获取小米 MiMo 或其他高 Token 额度服务，进一步提升代码生成的质量与上下文理解能力，将项目打磨成一个高完成度的毕业设计级作品。

## ✨ 核心功能特性

- **📝 文章管理系统**
    - 文章的发布、编辑、删除（支持富文本/UEditor）。
    - 文章分类与标签体系。
    - 前台文章详情页展示与侧边栏推荐阅读。
- **💬 评论互动模块**
    - 游客可针对文章发表评论。
    - 支持嵌套回复逻辑（楼中楼）。
    - 后台可对不当评论进行管理与删除。
- **👤 用户与权限系统**
    - 用户注册、登录、退出机制（Session/Cookie）。
    - 密码加密存储（MD5/Password Hash）。
    - 前后台分离：普通用户仅可浏览，管理员可进入后台管理面板。
- **🎨 现代化 UI 设计**
    - 基于 Noto Serif SC 与 Playfair Display 字体的优雅排版。
    - 响应式布局，完美适配 PC 端与移动端。
    - 毛玻璃导航栏、卡片悬停动效等现代化交互细节。

## 🛠️ 技术栈 (Tech Stack)

| 层级 | 技术选型 |
| :--- | :--- |
| **后端** | PHP (Native), MySQLi 扩展 |
| **数据库** | MySQL 5.7+ |
| **前端** | HTML5, CSS3, Vanilla JavaScript |
| **编辑器** | UEditor (百度富文本编辑器) |
| **开发模式** | AI Pair Programming (Cursor / Claude Code) |
| **运行环境** | Apache / Nginx, PHPStudy / XAMPP |

## 🗃️ 数据库设计 (Database Schema)

项目主要包含三张核心数据表，以下是简化的表结构说明：

1.  **`users` (用户表)**
    *   `id`: 用户ID (主键)
    *   `username`: 用户名
    *   `password`: 密码 (加密存储)
    *   `email`: 邮箱
    *   `avatar`: 头像路径

2.  **`articles` (文章表)**
    *   `id`: 文章ID (主键)
    *   `title`: 文章标题
    *   `author`: 作者
    *   `content`: 文章内容 (HTML)
    *   `time`: 发布时间

3.  **`comments` (评论表)**
    *   `id`: 评论ID (主键)
    *   `article_id`: 所属文章ID (外键)
    *   `nick`: 评论者昵称
    *   `content`: 评论内容
    *   `time`: 评论时间

## 📂 项目结构
```bash
.
├── admin/                  # 后台管理目录
│   ├── article_add.php     # 添加文章
│   ├── article_edit.php    # 编辑文章
│   ├── articles_list.php   # 文章列表
│   ├── comments_list.php   # 评论管理
│   ├── header.php          # 后台公共头部
│   ├── main.php            # 后台仪表盘
│   ├── user_add.php        # 添加用户
│   ├── user_edit.php       # 编辑用户
│   ├── user_avatar.php     # 头像上传
│   └── users_list.php      # 用户列表
├── css/                    # 样式文件
├── js/                     # JS 脚本
├── ueditor/                # 富文本编辑器
├── images/                 # 图片资源
├── article.php             # 前台文章详情页
├── index.php               # 前台首页
├── login.php               # 登录页
├── register.php            # 注册页
├── users.php               # 用户处理逻辑
└── db.php                  # 数据库连接配置
```

## 🚀 安装与运行指南 (Local Setup)

1.  **环境准备**
    *   安装 PHPStudy / XAMPP / WAMP。
    *   确保 PHP 版本 >= 7.4，MySQL >= 5.7。

2.  **部署代码**
    *   克隆本仓库到你的 Web 服务器根目录（如 `www` 或 `htdocs`）。

3.  **数据库配置**
*   导入 `sql/bak.sql` 文件到你的 MySQL 数据库。
*   修改根目录下的 `db.php` 文件，填入你的数据库账号和密码:
```php
$host = "127.0.0.1";
$user = "root";
$userpassword = "你的数据库密码";
$database = "blog";
php

4.  **访问项目**
*   前台访问：`http://localhost/blog/`
*   后台访问：`http://localhost/blog/admin/main.php`

## 📈 项目路线图 (Roadmap)

- [x] 基础 CRUD 功能实现
- [x] 用户登录与鉴权系统
- [x] 后台管理面板搭建
- [ ] **🔮 计划接入小米 MiMo API**：实现 AI 智能写作助手（自动生成文章摘要、润色文案）。
- [ ] **🔮 计划接入小米 MiMo API**：实现 AI 代码审查功能，自动检测 PHP 代码中的潜在漏洞。

## 📜 开源协议

本项目采用 **MIT License** 进行开源，仅供学习与个人研究使用。

---
> **Developer**: Mike666wq  
> **Status**: Actively Learning & Coding 🚀
