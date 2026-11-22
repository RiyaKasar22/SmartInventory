<?php
require_once 'config.php'; require_login(); $pdo=db(); $id=(int)($_GET['id']??$_POST['id']??0);
$s=$pdo->prepare("SELECT * FROM products WHERE id=?"); $s->execute([$id]); $product=$s->fetch();
if(!$product){http_response_code(404);exit('Product not found.');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $name=trim($_POST['name']??'');
 if($name==='') $error='Product name is required.';
 else{
  $u=$pdo->prepare("UPDATE products SET name=?,category=?,quantity=?,min_stock=?,price=?,cost_price=?,expiry_date=? WHERE id=?");
  $u->execute([$name,trim($_POST['category']??'General'),max(0,(int)($_POST['quantity']??0)),max(0,(int)($_POST['min_stock']??5)),max(0,(float)($_POST['price']??0)),max(0,(float)($_POST['cost_price']??0)),($_POST['expiry_date']??'')?:null,$id]);
  notice_redirect('Product updated successfully');
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit product | SmartInventory</title><link rel="stylesheet" href="style.css"></head><body><aside class="sidebar"><div class="brand"><span class="brand-icon">▦</span><div>Smart<span>Inventory</span><small>SHOP MANAGEMENT</small></div></div><nav><a href="index.php">▦ &nbsp; Overview</a><a class="active" href="index.php#products">▤ &nbsp; Products</a><a href="index.php#sales">↗ &nbsp; Sales</a><a href="index.php#insights">✦ &nbsp; AI Insights</a></nav></aside><main class="main"><header class="topbar"><div><p class="eyebrow">PRODUCTS / EDIT</p><h1>Edit product</h1><p class="muted">Update product details and stock.</p></div><a class="secondary" href="index.php#products">← Back</a></header><?php if($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?><section class="panel"><form method="post" class="stack-form profile-form"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><input type="hidden" name="id" value="<?=$id?>"><label>Product name<input name="name" value="<?=e($product['name'])?>" required></label><label>Category<input name="category" value="<?=e($product['category'])?>"></label><label>Quantity<input type="number" name="quantity" min="0" value="<?=$product['quantity']?>" required></label><label>Minimum stock<input type="number" name="min_stock" min="0" value="<?=$product['min_stock']?>" required></label><label>Selling price<input type="number" step="0.01" min="0" name="price" value="<?=$product['price']?>" required></label><label>Cost price<input type="number" step="0.01" min="0" name="cost_price" value="<?=$product['cost_price']?>" required></label><label>Expiry date<input type="date" name="expiry_date" value="<?=e($product['expiry_date'])?>"></label><button class="primary">Save changes</button></form></section></main></body></html>
