<?php
require_once 'config.php';
if (admin_count() === 0) { header('Location: setup_admin.php'); exit; }
if (is_logged_in()) { header('Location: index.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $s=db()->prepare("SELECT id,password_hash FROM admin_users WHERE username=?");
    $s->execute([trim($_POST['username']??'')]); $user=$s->fetch();
    if ($user && password_verify($_POST['password']??'', $user['password_hash'])) {
        session_regenerate_id(true); $_SESSION['admin_id']=(int)$user['id'];
        header('Location: index.php'); exit;
    }
    $error='Incorrect username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin login | SmartInventory</title><link rel="stylesheet" href="style.css"></head><body class="auth-body"><main class="auth-card"><div class="brand auth-brand"><span class="brand-icon">▦</span><div>Smart<span>Inventory</span><small>SHOP MANAGEMENT</small></div></div><p class="eyebrow">ADMIN ACCESS</p><h1>Welcome back</h1><p class="muted">Sign in to manage your shop inventory.</p><?php if($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?><form method="post" class="stack-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Username<input name="username" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="primary full">Login</button></form></main></body></html>
