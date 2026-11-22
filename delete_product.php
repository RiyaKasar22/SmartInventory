<?php
require_once 'config.php'; require_login(); verify_csrf();
if($_SERVER['REQUEST_METHOD']==='POST'){ $s=db()->prepare("DELETE FROM products WHERE id=?"); $s->execute([(int)($_POST['id']??0)]); }
notice_redirect('Product deleted');
