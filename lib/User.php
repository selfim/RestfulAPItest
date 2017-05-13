<?php 
//namespace lib;
require_once __DIR__. '/ErrorCode.php';
class User
{	
	private $_db;
	/**
	 * 构造方法
	 * @param PDO $_db PDO连接句柄
	 */
	public function __construct($_db)
	{
		$this->_db=$_db;
	}

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @return mixed
     * @throws Exception
     */
	public function login($username,$password)
	{
		if (empty($username)) {
			throw new Exception("用户名不能为空", ErrorCode::USERNAME_CANNOT_EMPTY);
			
		}
		if (empty($password)) {
			throw new Exception("密码不能为空", ErrorCode::PASSWORD_CANNOT_EMPTY);
		}
		$sql ='SELECT * FROM `user`WHERE `username`=:username and `password`=:password';
		$password=$this->CheckMd5($password);
		$stmt=$this->_db->prepare($sql);
		$stmt->bindParam(':username',$username);
		$stmt->bindParam(':password',$password);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($res)) {
			throw new Exception("用户名或密码错误", ErrorCode::USERNAME_OR_PASSWORD_INVALID);
			
		}
		unset($res['password']);
		return $res;
	}

    /**
     * 用户注册
     * @param $username
     * @param $password
     * @return array
     * @throws Exception
     */
	public function register($username,$password)
	{
	    //return $this->CheckUser($username);

		if (empty($username)) {
			throw new Exception("用户名不能为空", ErrorCode::USERNAME_CANNOT_EMPTY);
			
		}
		if (empty($password)) {
			throw new Exception("密码不能为空", ErrorCode::PASSWORD_CANNOT_EMPTY);
		}
		if ($this->CheckUser($username)) {
			throw new Exception("用户名已存在", ErrorCode::USERNAME_EXISTS);
			
		}
		//写入数据库
		$sql='INSERT INTO `user`(`username`,`password`,`createAt`) VALUES(:username,:password,:createAt)';
		$createAt=time();
		$password=$this->CheckMd5($password);
		$stmt=$this->_db->prepare($sql);
		$stmt->bindParam(':username',$username);
		$stmt->bindParam(':password',$password);
		$stmt->bindParam(':createAt',$createAt);
		if (!$stmt->execute()) {
			throw new Exception("注册失败", ErrorCode::REGISTER_FAIL);
			
		}
		return [
			'userID'=>$this->_db->lastInsertId(),
			'username'=>$username,
			'createAt'=>$createAt
		];


	}

    /**
     * 增强md5加密
     * @param $string
     * @param string $key
     * @return string
     */
	private function CheckMd5($string,$key='apitest')
	{
		return md5($string.$key);
	}

    /**
     * 检测用户名是否存在
     * @param $username
     * @return bool
     */
	private function CheckUser($username)
	{
		$sql ='SELECT * FROM `user`WHERE `username`=:username';
		$stmt =$this->_db->prepare($sql);
		$stmt->bindParam(':username',$username);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		return !empty($res);

	}
}


 ?>