<?php
//用户上传文件
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

//文件保存
if(!isset($_FILES) || !isset($_FILES["file"])){
	MyException("缺少file文件参数");
}
if(move_uploaded_file($_FILES["file"]["tmp_name"],".../CloudDisk/$group_id/"  . $path . $_FILES["file"]["name"])===false){
	MyError('文件移动失败');
}

MySuccess("http://115.159.106.238/CloudDisk/$group_id/" . $_FILES["file"]["name"],'文件上传成功');
?>