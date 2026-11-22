<?php
require_once 'config.php'; require_login(); verify_csrf();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:index.php');exit;}
$name=trim($_POST['name']??''); if($name==='') notice_redirect('Product name is required.');
$s=db()->prepare("INSERT INTO products(name,category,quantity,min_stock,price,cost_price,expiry_date) VALUES(?,?,?,?,?,?,?)");
$s->execute([$name,trim($_POST['category']??'General'),max(0,(int)($_POST['quantity']??0)),max(0,(int)($_POST['min_stock']??5)),max(0,(float)($_POST['price']??0)),max(0,(float)($_POST['cost_price']??0)),($_POST['expiry_date']??'')?:null]);
notice_redirect('Product added successfully');
