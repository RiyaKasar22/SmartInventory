<?php
require_once 'config.php'; require_login();
header('Content-Type: application/json; charset=utf-8');
$keyFile=__DIR__.'/private/gemini_key.php';
$apiKey=is_file($keyFile)?require $keyFile:'';
if(!$apiKey){http_response_code(503);echo json_encode(['error'=>'Gemini API key not configured. Open Admin Profile and save your key.']);exit;}
$rows=db()->query("SELECT name,category,quantity,min_stock,price,cost_price,expiry_date FROM products ORDER BY name")->fetchAll();
$prompt="You are a practical advisor for a small local shop. Review this inventory data and give 4-6 short, actionable suggestions. Prioritize products close to expiry and low stock. Do not invent sales data. State this is guidance, not a guarantee. Inventory: ".json_encode($rows);
$payload=json_encode(['contents'=>[['parts'=>[['text'=>$prompt]]]]]);
$url='https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key='.urlencode($apiKey);
$ch=curl_init($url); curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$payload,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>30]);
$response=curl_exec($ch);$err=curl_error($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
if($response===false){http_response_code(502);echo json_encode(['error'=>'Gemini request failed: '.$err]);exit;}
$data=json_decode($response,true);
if($code>=400){http_response_code(502);echo json_encode(['error'=>$data['error']['message']??'Gemini API request failed']);exit;}
echo json_encode(['text'=>$data['candidates'][0]['content']['parts'][0]['text']??'No suggestions returned.']);
