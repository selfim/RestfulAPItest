<?php 
//echo 11111;
header('content-type:text/html;charset=utf-8');
require __DIR__.'/lib/User.php';
require __DIR__.'/lib/Article.php';
$pdo =require __DIR__.'/lib/db.php';

$user =new User($pdo);
//print_r($user->register('admin2','admin2'));
$artitle = new Article($pdo);
//print_r($artitle->create('文章标题7','文章内容7',2));
//print_r($artitle->view(1));
//print_r($artitle->edit(1,'hello','restful api',2));
//var_dump($artitle->del(8,2));
print_r($artitle->getList(2));

