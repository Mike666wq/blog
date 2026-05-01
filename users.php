<?php
// 处理用户登录、注册、修改、退出、删除相关的代码
// 设置页面编码为utf-8
header("Content-Type: text/html; charset=utf-8");

// 连接数据库
include_once("db.php");
// 开启会话保存
session_start();

// 先判断$_REQUEST中是否存在'login'或者'register'参数，如果存在，则执行对应的操作，否则返回错误信息
if (isset($_REQUEST['login'])) {
    // 从前端获取用户名和密码
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查询数据库中的user表，并且将$username和$passwrod作为条件查询
    $sql = "select * from users where username='$username' and password='$password'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "查询失败: " . mysqli_error($conn);
        exit;
    }

    // 如果查询结果为空，提示用户名或密码错误
    if (mysqli_num_rows($result) == 0) {
        echo "用户名或密码错误 <a href='login.html'>返回登录页面</a>";
        exit;
    } else {
        // 登录成功，设置会话变量
        $user = mysqli_fetch_assoc($result);
        $_SESSION['userid'] = $user['id'];
        $_SESSION['username'] = $username;
        echo "<script>alert('登录成功');window.location.href='main.php';</script>";
    }
} elseif (isset($_REQUEST['register']) or isset($_REQUEST['add'])) {
    // 先从前端获取用户名，以及两次密码输入，邮箱或手机号
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // 判断两次密码输入是否一致
    if ($password != $confirm_password) {
        echo "<script>alert('两次密码输入不一致');window.location.href='login.html';</script>";
        exit;
    }

    // 判断用户名或邮箱是否存在
    $sql = "select * from users where username='$username' or email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('用户名或邮箱已存在');window.location.href='login.php';</script>";
        exit;
    }

    // 然后写sql语句，插入一条新的用户记录
    $sql = "insert into users (username, password, email) values ('$username', '$password', '$email')";
    $result = mysqli_query($conn, $sql);

    // 插入成功后，返回注册成功信息
    if ($result) {
        if (isset($_REQUEST['add'])) {
            echo "<script>alert('添加成功');window.location.href='users_list.php';</script>";
        } else {
            echo  "<script>alert('注册成功');window.location.href='login.php';</script>";
        }
    } else {
        if (isset($_REQUEST['add'])) {
            echo "<script>alert('添加失败');window.location.href='users_list.php';</script>";
        } else {
            echo  "<script>alert('注册失败');window.location.href='login.php';</script>";
        }
    }
} elseif (isset($_REQUEST['update'])) {
    // 处理用户更新操作
    $userid = $_REQUEST['id'];
    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];

    // 判断是否需要修改密码，判断密码的长度
    if (strlen($_REQUEST['password']) > 0) {
        $userid = $_REQUEST['id'];
        $password = $_REQUEST['password'];
        $confirm_password = $_REQUEST['confirm_password'];
        if ($password != $confirm_password) {
            echo "<script>alert('两次密码输入不一致');window.location.href='users_list.php';</script>";
            exit;
        }
        // 更新用户名，密码，邮箱
        // 存在逻辑漏洞，没有判断用户名是否存在，可以修改别人的用户名
        $sql = "update users set username='$username', password='$password', email='$email' where id='$userid'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('更新成功');window.location.href='users_list.php';</script>";
        } else {
            echo "<script>alert('更新失败: " . mysqli_error($conn) . "');window.location.href='users_list.php';</script>";
        }
    } else {
        // 更新用户名和邮箱，不修改密码
        $sql = "update users set username='$username', email='$email' where id='$userid'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('更新成功');window.location.href='users_list.php';</script>";
        } else {
            echo "<script>alert('更新失败: " . mysqli_error($conn) . "');window.location.href='users_list.php';</script>";
        }
    }
} elseif (isset($_REQUEST['del'])) {
    // 删除用户
    $userid = $_REQUEST['id'];
    $sql = "delete from users where id='$userid'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('删除成功');window.location.href='users_list.php';</script>";
    } else {
        echo "<script>alert('删除失败: " . mysqli_error($conn) . "');window.location.href='users_list.php';</script>";
    }
}

else {
    echo "错误：未知操作";
    exit;
}
