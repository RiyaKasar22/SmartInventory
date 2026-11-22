<?php
require_once 'config.php'; require_login(); verify_csrf();
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location:index.php');exit;}
$id=(int)($_POST['product_id']??0); $qty=(int)($_POST['quantity']??0); $pdo=db();
try{
 $pdo->beginTransaction(); $s=$pdo->prepare("SELECT quantity,price FROM products WHERE id=? FOR UPDATE"); $s->execute([$id]); $p=$s->fetch();
 if(!$p||$qty<1||$qty>(int)$p['quantity']) throw new Exception('Not enough stock or invalid product.');
 $pdo->prepare("INSERT INTO sales(product_id,quantity,unit_price) VALUES(?,?,?)")->execute([$id,$qty,$p['price']]);
 $pdo->prepare("UPDATE products SET quantity=quantity-? WHERE id=?")->execute([$qty,$id]);
 $pdo->commit(); notice_redirect('Sale recorded and stock updated');
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();notice_redirect($e->getMessage());}
