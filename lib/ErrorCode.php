<?php 
class ErrorCode
{
	const USERNAME_EXISTS=1;//用户名已存在
	const PASSWORD_CANNOT_EMPTY=2;//密码不能为空
	const USERNAME_CANNOT_EMPTY=3;//用户名不能为空
	const REGISTER_FAIL=4;//注册失败
	const USERNAME_OR_PASSWORD_INVALID=5;//用户名或密码不合法
	const ARTICLE_TITLE_CANNOT_EMPTY=6;//文章标题不能为空
	const ARTICLE_CONTENT_CANNOT_EMPTY=7;//文章内容不能为空
	const ARTICLE_CREATE_FAIL=8;//文章创建失败
}