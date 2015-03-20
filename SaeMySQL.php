<?php
//SAE的SaeMySQL操作
$MySQL_Server = SAE_MYSQL_HOST_M;
$MySQL_Port = SAE_MYSQL_PORT;
$MySQL_Username = SAE_MYSQL_USER;
$MySQL_Password = SAE_MYSQL_PASS;
$MySQL_Database = SAE_MYSQL_DB;

//die("【数据库连接错误】<br/>错误代码：".mysql_errno()."<br/>错误原因: ".mysql_error());

//自定义的MySQL新建连接的函数（成功返回数据库连接标识，失败返回false）
function SaeMySQLConnect(){
	global $MySQL_Server;
	global $MySQL_Port;
	global $MySQL_Username;
	global $MySQL_Password;
	global $MySQL_Database;
	$Connect=mysql_connect($MySQL_Server.':'.$MySQL_Port,$MySQL_Username,$MySQL_Password);
	if(!$Connect) {
		return false;
	}
	if(!mysql_select_db($MySQL_Database,$Connect)){
		return false;
	}
	return $Connect;
}
//自定义的MySQL尝试更新数据库的函数（成功或【实际影响的行数为0】返回true，失败返回false）
function SaeMySQLTryUpdate($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	return $result;
}
//自定义的MySQL强制更新数据库的函数（成功返回true，失败或【实际影响的行数为0】返回false）
function SaeMySQLMustUpdate($SQL){
	//mysql_query函数会自动对记录集进行读取和缓存
	//mysql_query(query,connection)的参数connection如果未规定，则使用上一个打开的连接
	//mysql_query返回非false的值，不说明任何有关影响到的或返回的行数，很有可能一条查询执行成功了但并未影响到或并未返回任何行
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	if(mysql_affected_rows()===0){
		return false;
	}
	return $result;
}
//自定义的MySQL尝试删除数据库的函数（等同于SaeMySQLTryUpdate，成功或【实际影响的行数为0】返回true，失败返回false）
function SaeMySQLTryDelete($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	return $result;
}
//自定义的MySQL强制删除数据库的函数（等同于SaeMySQLMustUpdate，成功返回true，失败或【实际影响的行数为0】返回false）
function SaeMySQLMustDelete($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	if(mysql_affected_rows()===0){
		return false;
	}
	return $result;
}
//自定义的MySQL插入数据库的函数（成功返回上一步INSERT操作产生的ID，失败返回false）
function SaeMySQLInsert($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	if(mysql_insert_id()===0){
		return false;
	}
	return mysql_insert_id();
}
//自定义的MySQL读取数据库的函数（成功返回数据的关联和默认下标数组，失败返回false）
function SaeMySQLSelectArray($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_array($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_array($result);
	}
	return $data;
}
//自定义的MySQL读取数据库的函数（成功只返回数据的默认下标数组，失败返回false）
function SaeMySQLSelectDefaultArray($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_row($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_row($result);
	}
	return $data;
}
//自定义的MySQL读取数据库的函数（成功只返回数据的关联数组，失败返回false）
function SaeMySQLSelectAssociativeArray($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_assoc($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_assoc($result);
	}
	return $data;
}
//自定义的MySQL读取数据库（一行）的函数（成功返回数据的关联和默认下标数组，失败或【查询到的行数为0】返回false）
function SaeMySQLSelectRow($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$row = mysql_fetch_array($result);
	if (!$row){ return false; }
	return $row;
}
//自定义的MySQL读取数据库（一行）的函数（成功只返回数据的默认下标数组，失败或【查询到的行数为0】返回false）
function SaeMySQLSelectDefaultRow($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$row = mysql_fetch_row($result);
	if (!$row){ return false; }
	return $row;
}
//自定义的MySQL读取数据库（一行）的函数（成功只返回数据的关联数组，失败或【查询到的行数为0】返回false）
function SaeMySQLSelectAssociativeRow($SQL){
	$result = mysql_query($SQL);
	if(!$result){
		return false;
	}
	$row = mysql_fetch_assoc($result);
	if (!$row){ return false; }
	return $row;
}
//自定义的MySQL断开连接的函数（成功返回true，失败返回false）
function SaeMySQLDisconnect(){
	mysql_close();
}
//SQL结果集解析的函数（成功返回数据的关联和默认下标数组，失败返回false）
function SaeMySQLFetchAllArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_array($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_array($result);
	}
	return $data;
}
//SQL结果集解析的函数（成功只返回数据的默认下标数组，失败返回false）
function SaeMySQLFetchDefaultArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_row($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_row($result);
	}
	return $data;
}
//SQL结果集解析的函数（成功只返回数据的关联数组，失败返回false）
function SaeMySQLFetchAssociativeArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_assoc($result);
	while ($row){
		$data[] = $row;
		$row = mysql_fetch_assoc($result);
	}
	return $data;
}
//获取MySQL版本号的函数
function SaeMySQLVersion(){
	return mysql_get_server_info();
}
//获取MySQL当前运行的线程信息的函数
function SaeMySQLProcesses(){
	return SaeMySQLFetchAssociativeArray(mysql_list_processes());
}
//示例使用代码：
/*
SaeMySQLConnect();
var_dump(json_encode(SaeMySQLSelectAssociativeRow('SELECT * FROM  `DDOS_Student_Class` LIMIT 0 , 1')));
var_dump(json_encode(array("1"=>"\r\n")));
var_dump(SaeMySQLProcesses());
var_dump(SaeMySQLVersion());
*/
?>