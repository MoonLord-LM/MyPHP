<?php
//获取文件列表
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

//文件列表
$handle = @opendir($path);
if ($handle===false)
{
	MyError('文件夹打开错误');
}
$list = array();
while(($file = readdir($handle)) !==false ) {
	if ($file !== '.' && $file !== '..') {
		$stat = @stat($file);
		if ($stat===false)
		{
			MyError('文件信息获取失败');
		}
		//var_dump($stat);
		$list[] = array('name'=>$file,'size'=>$stat['size'],'time'=>date("Y-m-d H:i:s",$stat['mtime']));
		if (strpos($file,'.') !==false )
		{
			$list['type'] = 'file';
		}
		else
		{
			$list['type'] = 'path';
		}
	}
}
closedir($handle);
return $list;

MySuccess($list,'获取文件列表成功');
?>