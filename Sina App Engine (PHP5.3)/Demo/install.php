<?php
//示例（创建数据库表）
include('../SAE/SaeMySQL.php');
header('Content-Type:text/html;charset=utf-8');

if (SaeMySQLConnect()) {
	echo '数据库连接成功……<br />';
}
else{
	echo '数据库连接失败，错误代码：'.mysql_errno().' 错误原因：'.mysql_error().'<br />';
}

$tableName = "user_info";
$SQL="create table ". $tableName ."(
id int unsigned not null auto_increment primary key,
name char(60) not null unique,
password char(60) not null,
register_time datetime not null,
last_login_time datetime not null,
last_login_stamp int unsigned not null,
index (password),
index (register_time),
index (last_login_time),
index (last_login_stamp)
)";
$tableName .="【用户信息】";
if(SaeMySQLCreateTable($SQL))
{
	echo "数据表".$tableName."创建成功……<br />";
}
else{
	if(mysql_errno()=="1050"){
		echo"数据表".$tableName."已经存在……<br />";
	}
	else{ 
		echo"创建数据表".$tableName."失败，错误代码：".mysql_errno()." 错误原因：".mysql_error()."<br />";
	}
}

SaeMySQLDisconnect();
?>