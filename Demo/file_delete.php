<?php
//用户删除文件
//By：MoonLord
//2015.11.14
header('Content-Type:text/html;charset=utf-8');
include('MyPHP/My.php');
include('MyPHP/MySQLi.php');

//参数
$user_id = MySessionGet('user_id');
if ($user_id===false)
{
	MySuccess(1,'用户未登录');
}
if (MySetParameterInteger("group_id")===false){
	MyException("缺少group_id参数或group_id不是整数");
}
if (MySetParameter("path")===false){
	$path = '';
	//MyException("缺少path参数");
}
if (MySetParameter("file")===false){
	MyException("缺少file参数");
}

//权限验证
if (MySQLConnect()===false)
{
	MyError('数据库连接错误');
}
$authority = MySQLSelectCell("SELECT `authority` FROM `user_group_info` WHERE `user_id` = $user_id and `group_id` = $group_id");
MySQLDisconnect();
if((int)$authority<2048){
	MySuccess(2,'用户权限不足');
}

//文件删除
if(@unlink(".../CloudDisk/$group_id/" . $path . $file)===false){
	MyError('文件删除失败');
}

MySuccess(0,'文件删除成功');
?>