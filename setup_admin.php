<?php
require_once 'config.php';
if (admin_count() > 0) { header('Location: login.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($name === '' || $username === '' || strlen($password) < 8) {
        $error = 'Enter your full name, username, and a password of at least 8 characters.';
    } else {
        $s = db()->prepare("INSERT INTO admin_users(full_name,username,password_hash) VALUES(?,?,?)");
        $s->execute([$name,$username,password_hash($password,PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)db()->lastInsertId();
        header('Location: index.php?notice=Admin account created'); exit;
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Set up admin | SmartInventory</title><link rel="stylesheet" href="style.css"></head><body class="auth-body"><main class="auth-card"><div class="brand auth-brand"><span class="brand-icon">▦</span><div>Smart<span>Inventory</span><small>SHOP MANAGEMENT</small></div></div><p class="eyebrow">ONE-TIME SETUP</p><h1>Create admin account</h1><p class="muted">This creates the only administrator account for this local shop app.</p><?php if($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?><form method="post" class="stack-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Full name<input name="full_name" required maxlength="120"></label><label>Username<input name="username" required maxlength="60" autocomplete="username"></label><label>Password<input type="password" name="password" required minlength="8" autocomplete="new-password"></label><button class="primary full">Create admin account</button></form><p class="tiny-note">Remember these credentials. You will use the same username and password each time you log in.</p></main></body></html>
