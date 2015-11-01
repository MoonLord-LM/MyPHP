<?php
//示例（用户登录）
include('../SAE/SaeMySQL.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameter("username")===false){
	MyException("缺少username参数");
}
if (MySetParameter("password")===false){
	MyException("缺少password参数");
}

$login_stamp = time();
$login_time = "'" . date("Y-m-d H:i:s",$login_stamp) . "'";

if (SaeMySQLConnect()===false){
	MyError("数据库连接失败");
}
$username = SaeMySQLCheck($username);
$password = SaeMySQLCheck($password);

$SQL="UPDATE `user_info`
SET `user_info`.`last_login_time` = $login_time, `user_info`.`last_login_stamp` = $login_stamp
WHERE `user_info`.`name` = $username AND `user_info`.`password` = $password
";
if(SaeMySQLTryUpdate($SQL)===false)
{
	MyError("数据库更新失败");
}

$SQL="SELECT `id` FROM `user_info`
WHERE `user_info`.`name` = $username AND `password` = $password
";
$Result = SaeMySQLSelectCell($SQL);
if($Result===false){
	MySuccess("0","账号或密码错误");
}
SaeMySQLDisconnect();
MySuccess($Result,"登录成功");
?>