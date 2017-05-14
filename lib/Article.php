<?php 
require_once __DIR__.'/ErrorCode.php';
class Article
{
	private $_db;

	public function __construct($_db)
	{
		$this->_db=$_db;
	}

    /**
     * 创建文章
     * @param $title
     * @param $content
     * @param $userID
     * @return array
     * @throws Exception
     */
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
		$sql='INSERT INTO `article`(`title`,`content`,`user_id`,`createAt`) VALUES(:title,:content,:user_id,:createAt)';
		$createAt = time();
		$stmt=$this->_db->prepare($sql);
		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':content',$content);
		$stmt->bindParam(':user_id',$userID);
		$stmt->bindParam(':createAt',$createAt);
		
		if (!$stmt->execute()) {
			throw new Exception("文章发布失败", ErrorCode::ARTICLE_CREATE_FAIL);
			
		}
		return [
			'articleId'=>$this->_db->lastInsertId(),
			'title'=>$title,
			'content'=>$content,
			'user_id'=>$userID,
			'createAt'=>$createAt
		];

	}

    /**
     * 编辑文章预处理
     * @param $articleId
     * @return mixed
     * @throws Exception
     */
    public function view($articleId)
    {
        if (empty($articleId)){
            throw new Exception('articleId不能为空!',ErrorCode::ARTICLEID_CANNOT_EMPTY);
        }
        $sql ='SELECT * FROM `article` WHERE `article_id`=:id';
        $stmt =$this->_db->prepare($sql);
        $stmt->bindParam(':id',$articleId);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($article)){
        	throw new Exception("文章不存在", ErrorCode::ARTICLE_NOT_FOUND);
        	
        }
        return $article;
    }
    /**
     * 编辑文章
     * @param $articleId
     * @param $title
     * @param $content
     * @param $userID
     */
	public function edit($articleId,$title,$content,$userID)
	{

        $article =$this->view($articleId);
        //var_dump($article['user_id'],$userID);exit;
        if ($article['user_id']!==$userID){
            throw new Exception('您无权编辑该文章',ErrorCode::PERMISSION_DENIED);

        }
        $title =empty($title)?$article['title']:$title;
        $content = empty($content)?$article['content']:$content;
        if ($title===$article['title']&&$content===$article['content']){
            return $article;
        }
        $sql ='UPDATE `article` SET `title`=:title,`content`=:content WHERE `article_id`=:id';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':content',$content);
        $stmt->bindParam(':id',$articleId);
        if (false===$stmt->execute()){
            throw new Exception('文章编辑失败',ErrorCode::ARTICLE_EDIT_FAIL);
        }
        return[
            'articleId'=>$articleId,
            'title'=>$title,
            'content'=>$content,
            'createAt'=>$article['createAt'],
        ];

	}

    /**删除文章
     * @param $articleId
     * @param $userID
     */
	public function del($articleId,$userID)
	{
	    $article =$this->view($articleId);
	    if ($article['user_id']!==$userID){
	        throw new Exception('您无权操作！',ErrorCode::PERMISSION_DENIED);
        }
        $sql ='DELETE FROM `article` WHERE `article_id`=:articleId AND `user_id`=:userId';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':articleId',$articleId);
        $stmt->bindParam(':userId',$userID);
        if (!$stmt->execute()){
            throw new Exception('文章删除失败',ErrorCode::ARTICLE_DELETE_FAIL);
        }
        return true;
	}

    /**
     * 获取文章列表
     * @param $userId
     * @param int $page
     * @param int $size
     * @return mixed
     * @throws Exception
     */
	public function getList($userId,$page =1,$size =10)
	{
        if ($size>100){
            throw new Exception('分页大小最大为100',ErrorCode::PAGE_MAX_SIZEIS_100);
        }
        $sql ='SELECT * FROM `article` WHERE `user_id`=:userId LIMIT :limit,:offset';
        $stmt=$this->_db->prepare($sql);
        $limit = ($page-1)*$size;
        $limit =$limit<0?0:$limit;
        $stmt->bindParam(':userId',$userId);
        $stmt->bindParam(':limit',$limit);
        $stmt->bindParam(':offset',$size);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

	}
}