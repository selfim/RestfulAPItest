<?php 
require_once __DIR__.'/ErrorCode.php';
class Article
{
	private $_db;

	public function __construct($_db)
	{
		$this->_db=$_db;
	}
	public function create($title,$content,$userID)
	{
		//数据检测
		if (empty($title)) {
			throw new Exception("文章标题不能为空", ErrorCode::ARTICLE_TITLE_CANNOT_EMPTY);
			
		}
		if (empty($content)) {
			throw new Exception("文章内容不能为空", ErrorCode::ARTICLE_CONTENT_CANNOT_EMPTY);
			
		}
		//写入数据库
		$sql='INSERT INTO `artitle`(`title`,`content`,`user_id`,`createAt`) VALUES(:title,:content,:user_id,:createAt)';
		$createAt = time();
		$stmt=$this->_db->prepare($sql);
		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':content',$content);
		$stmt->bindParam(':user_id',$userID);
		$stmt->bindParam(':createAt',$createAt);
		
		if (!$stmt->execute()) {
			throw new Exception("Error Processing Request", ErrorCode::ARTICLE_CREATE_FAIL);
			
		}
		return [
			'articleId'=>$this->_db->lastInsertId(),
			'title'=>$title,
			'content'=>$content,
			'user_id'=>$userID,
			'createAt'=>$createAt
		];

	}

	public function edit($articleId,$title,$content,$userID)
	{

	}

	public function del($articleId,$userID)
	{

	}

	public function getList()
	{

	}
}