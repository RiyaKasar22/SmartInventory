<?php
require_once 'config.php'; require_login();
$admin=current_admin(); $message=''; $error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $name=trim($_POST['full_name']??''); $username=trim($_POST['username']??'');
    if ($name==='' || $username==='') $error='Full name and username are required.';
    else {
        try {
            $s=db()->prepare("UPDATE admin_users SET full_name=?,username=? WHERE id=?");
            $s->execute([$name,$username,(int)$_SESSION['admin_id']]);
            $newpass=$_POST['new_password']??'';
            if ($newpass!=='') {
                if (strlen($newpass)<8) throw new RuntimeException('New password must be at least 8 characters.');
                db()->prepare("UPDATE admin_users SET password_hash=? WHERE id=?")->execute([password_hash($newpass,PASSWORD_DEFAULT),(int)$_SESSION['admin_id']]);
            }
            $admin=current_admin(); $message='Profile updated successfully.';
        } catch (Throwable $ex) { $error=$ex instanceof RuntimeException ? $ex->getMessage() : 'That username may already be in use.'; }
    }
    $key=trim($_POST['gemini_key']??'');
    if ($key!=='') {
        if (!is_dir(__DIR__.'/private')) mkdir(__DIR__.'/private',0700,true);
        file_put_contents(__DIR__.'/private/gemini_key.php', "<?php\nreturn ".var_export($key,true).";\n", LOCK_EX);
        @file_put_contents(__DIR__.'/private/.htaccess', "Require all denied\n");
        $message='Profile saved. Gemini API key saved locally.';
    }
}
$keyExists=is_file(__DIR__.'/private/gemini_key.php');
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin profile | SmartInventory</title><link rel="stylesheet" href="style.css"></head><body><aside class="sidebar"><div class="brand"><span class="brand-icon">▦</span><div>Smart<span>Inventory</span><small>SHOP MANAGEMENT</small></div></div><nav><a href="index.php">▦ &nbsp; Overview</a><a href="index.php#products">▤ &nbsp; Products</a><a href="index.php#sales">↗ &nbsp; Sales</a><a href="index.php#insights">✦ &nbsp; AI Insights</a></nav><div class="side-bottom"><div class="avatar"><?=e(mb_substr($admin['full_name'],0,1))?></div><div><b><?=e($admin['full_name'])?></b><small><a href="logout.php" style="color:#b7c8c1">Logout</a></small></div></div></aside><main class="main"><header class="topbar"><div><p class="eyebrow">ACCOUNT / PROFILE</p><h1>Admin profile</h1><p class="muted">Manage the single administrator account and optional Gemini connection.</p></div><a class="secondary" href="index.php">← Dashboard</a></header><?php if($message): ?><div class="notice"><?=e($message)?></div><?php endif; ?><?php if($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?><section class="panel"><h2>Account details</h2><form method="post" class="stack-form profile-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Full name<input name="full_name" value="<?=e($admin['full_name'])?>" required></label><label>Username<input name="username" value="<?=e($admin['username'])?>" required></label><label>New password <small>(leave blank to keep current password)</small><input type="password" name="new_password" minlength="8" autocomplete="new-password"></label><hr><h2>Gemini AI settings</h2><p class="muted">Paste your Gemini API key here. It is saved locally in this project and is not sent to ChatGPT.</p><label>Gemini API key<input type="password" name="gemini_key" placeholder="<?=$keyExists?'Key already configured — enter a new key to replace it':'Paste API key here'?>" autocomplete="off"></label><button class="primary">Save changes</button></form></section><footer>SmartInventory · Admin account</footer></main></body></html>
