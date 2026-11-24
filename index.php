<?php
require 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkToken();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (isset($_POST['register'])) {
        if (strlen($username)<3 || strlen($password)<8 || strlen($username)>60) $error='Username 3–60 karakter dan password minimal 8 karakter.';
        else {
            try {
                $db->prepare('INSERT INTO users(username,password) VALUES (?,?)')->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);
                $error='Akun dibuat. Silakan login.';
            }
            catch (PDOException $ex) {
                $error='Username sudah digunakan atau pendaftaran gagal.';
            }
        }
    }
    else {
        $q=$db->prepare('SELECT * FROM users WHERE username=?');
        $q->execute([$username]);
        $user=$q->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password,$user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user']=$user['username'];
            setcookie('last_user',isset($_POST['remember'])?$username:'', ['expires'=>isset($_POST['remember'])?time()+2592000:time()-3600,'path'=>'','httponly'=>true,'samesite'=>'Lax']);
            go('dashboard.php');
        }
        $error='Username atau password salah.';
    }
}
if(isset($_SESSION['user'])) go('dashboard.php');
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>
            Stokku | Login
        </title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="login">
        <main class="panel loginbox">
            <span class="brand">
                ◈ STOKKU
            </span>
            <h1>
                Kelola stok lebih mudah.
            </h1>
            <p>
                Masuk atau buat akun untuk mengelola inventaris bersama.
            </p>
            <?php if($error): ?>
            <p class="notice">
                <?=e($error)?>
            </p>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="csrf" value="<?=token()?>">
                <label>
                    Username
                    <input name="username" required maxlength="60" value="<?=e($_COOKIE['last_user']??'')?>">
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <label class="check">
                    <input type="checkbox" name="remember">
                    Ingat username
                </label>
                <button>
                    Masuk
                </button>
                <button class="secondary" name="register" value="1">
                    Daftar akun
                </button>
            </form>
        </main>
    </body>
</html>
