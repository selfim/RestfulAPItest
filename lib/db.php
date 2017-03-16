<?php 
/**
 * 数据库连接
 * @var PDO
 */
$pdo =new PDO('mysql:host=localhost;dbname=apitest','root','123456');
return $pdo;