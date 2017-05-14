<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/14
 * Time: 16:02
 */
//echo'<pre>';print_r($_SERVER);
require __DIR__.'/../lib/User.php';
require __DIR__.'/../lib/Article.php';
$pdo =require __DIR__.'/../lib/db.php';
class Restful{
    /**
     * @var User
     */
    private $_user;

    /**
     * @var Article
     */
    private $_article;

    /**
     * 请求方法
     * @var string
     */
    private $_requestMethod;

    /**
     * 请求资源名称
     * @var string
     */
    private $_resourceName;

    /**
     * 请求的资源ID
     * @var string
     */
    private $_id;

    /**
     * 允许请求的资源列表
     * @var array
     */
    private $_allowResources =['users','articles'];

    /**
     * 允许请求的方法列表
     * @var array
     */
    private $_allowRequestMethod =['POST','GET','PUT','DELETE','OPTIONS'];

    /**
     * 状态码列表
     * @var array
     */
    private $_statusCodes = [
        200=>'OK',
        204=>'No Content',
        400=>'Bad Request',
        401=>'Unauthorized',
        403=>'Forbidden',
        404=>'Not Found',
        405=>'Method Not Allowed',
        500=>'Server Internal Error'
    ];
    /**
     * 构造方法
     * Restful constructor.
     * @param User $_user
     * @param Article $_article
     */
    public function __construct(User $_user,Article $_article)
    {
        $this->_user=$_user;
        $this->_article =$_article;
    }
    public  function run()
    {
        try{
            $this->_setupRequestMethod();
            $this->_setupResource();
            //$this->_setupId();
            if ($this->_resourceName=='users'){
                return $this->_json($this->_handleUser());
            }else{
                return $this->_handleArticle();
            }
        }catch (Exception $e){
           $this->_json(['error'=>$e->getMessage()],$e->getCode());
        }

    }


    /**
     * 初始化请求方法
     */
    private function _setupRequestMethod()
    {
        $this->_requestMethod =$_SERVER['REQUEST_METHOD'];
        if (!in_array($this->_requestMethod,$this->_allowRequestMethod)){
            throw new Exception('请求方法不被允许',405);
        }
    }

    /**
     * 初始化请求资源
     */
    private function _setupResource()
    {
        $path =$_SERVER['PATH_INFO'];
        //echo $path;
        $params =explode('/',$path);
        //print_r($params);
        $this->_resourceName =$params[1];
        //判断请求的资源是否在允许的列表里
        if (!in_array($this->_resourceName,$this->_allowResources)){
            throw new Exception('请求的资源不被允许',400);
        }
        if (!empty($params[2])){
            $this->_id =$params[2];
        }
    }

    /**
     * 初始化资源标识符
     */
    private function _setupId()
    {

    }

    /**
     * 输出JSON
     * @param $array
     */
    private function _json($array,$code =0)
    {
        if ($code>0&&$code!=200&&$code!=204){
            header("HTTP/1.1 ".$code." ".$this->_statusCodes[$code]);
        }
        header('Content-Type:application/json;charset=utf-8');
        echo json_encode($array,JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 请求用户资源
     * @return array
     * @throws Exception
     */
    private function _handleUser()
    {
        if ($this->_requestMethod !='POST'){
            throw new Exception('请求的方法不被允许',405);
        }
        $body = $this->_getBodyParams();
        if (empty($body['username'])){
            throw new Exception('用户名不能为空',400);
        }
        if (empty($body['password'])){
            throw new Exception('密码不能为空',400);
        }
        //$data =$this->_user->register($body['username'],$body['password']);
        return $this->_user->register($body['username'],$body['password']);
        //var_dump($data);exit();
    }

    /**
     * 请求文章资源
     */
    private function _handleArticle()
    {

    }

    /**
     * 获取请求参数
     * @return mixed
     * @throws Exception
     */
    private function _getBodyParams()
    {
        $raw = file_get_contents('php://input');
        if (empty($raw)){
            throw new Exception('请求参数有误',400);
        }
        return json_decode($raw,true);
    }

}
$user = new User($pdo);
$article = new Article($pdo);
$restful = new Restful($user,$article);
$restful ->run();