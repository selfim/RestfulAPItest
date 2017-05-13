<?php 
//echo 11111;
require __DIR__.'/lib/User.php';
//require __DIR__.'/lib/Article.php';
$pdo =require __DIR__.'/lib/db.php';

$user =new User($pdo);
print_r($user->register('admin2','admin2'));
//$artitle = new Article($pdo);
//print_r($artitle->create('文章标题','文章内容',3));


