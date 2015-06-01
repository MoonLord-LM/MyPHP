<?php
//示例（创建数据库表）
include('../SaeMySQL.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameter("username")===false){
	MyException("缺少username参数");
}
if (MySetParameter("password")===false){
	MyException("缺少password参数");
}

$register_stamp = time();
$register_time = "'" . date("Y-m-d H:i:s",$register_stamp) . "'";

if (SaeMySQLConnect()===false){
	MyError("数据库连接失败");
}
$SQL="INSERT INTO `user_info` (`name`, `password`, `register_time`, `last_login_time`, `last_login_stamp`)
VALUES ($username , $password, $register_time, $register_time, $register_stamp)
";
$Result = SaeMySQLInsert($SQL);
if($Result===false){
	MySuccess("0","账号已存在");
}
SaeMySQLDisconnect();
MySuccess($Result,"注册成功");
?>