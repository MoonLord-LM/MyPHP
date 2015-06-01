<?php
//示例（PC获取消息）
include('../SaeMySQL.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameterInteger("user_id")===false){
	MyException("缺少user_id参数");
}

$login_stamp = time() - 60*5;

if (SaeMySQLConnect()===false){
	MyError("数据库连接失败");
}
$SQL="SELECT `id` FROM `user_info`
WHERE `user_info`.`id` = $user_id AND `user_info`.`last_login_stamp` > $login_stamp
";
if(SaeMySQLSelectCell($SQL)===false)
{
	MySuccess('0','用户不在线，继续休眠状态');
}
SaeMySQLDisconnect();
MySuccess('1','用户在线中，进入激活状态');
?>