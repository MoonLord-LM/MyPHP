<?php
//MySQL基本操作（基于 PHP 的 MySQL 扩展）
//Author：MoonLord
//Version：2015.12.05

//注意：MySQL扩展自PHP 5.5.0起已废弃，并在将来会被移除。应使用 MySQLi 或 PDO_MySQL 扩展来替换之。

$MySQL_Server = 'localhost';
$MySQL_Port = '3306';
$MySQL_Username = 'root';
$MySQL_Password = '132357';
$MySQL_Database = 'MoonLord';

//已处理的MySQL错误：
//2006 MySQL server has gone away（可能原因：MySQL服务已异常关闭、连接超时、SQL语句过长、获取的结果集过长等，解决方案：尝试一次重新连接和重新查询）
//2013 Lost connection to MySQL server during query（可能原因：MySQL连接超时、SQL语句过长、获取的结果集过长等，解决方案：尝试一次重新连接和重新查询）
//结果集的额外处理：
//单元格的值如果为NULL，在返回结果中，将被转换为空字符串""
//单元格的值如果为JSON字符串，在返回结果中，将被转换为JSON数组

//die("【数据库连接错误】<br/>错误代码：".mysql_errno()."<br/>错误原因: ".mysql_error());

//自定义的MySQL新建连接的函数（成功返回数据库连接标识，失败返回false）
function MySQLConnect(){
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
//自定义的MySQL断开连接的函数（成功返回true，失败返回false）
function MySQLDisconnect(){
	return mysql_close();
}
//自定义的MySQL错误信息函数
function MySQLErrorInfo(){
	return '错误代码：'.mysql_errno().' 错误原因：'.mysql_error();
}
function MySQLErrorNumber(){
	return mysql_errno();
}
//自定义的MySQL插入数据库的函数（成功返回上一步INSERT操作产生的ID，失败返回false）
function MySQLInsert($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	if(mysql_insert_id()===0){
		return false;
	}
	return mysql_insert_id();
}
//自定义的MySQL尝试更新/删除数据库的函数（成功或【实际影响的行数为0】返回true，失败返回false）
function MySQLTryUpdate($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	return $result;
}
function MySQLTryDelete($SQL){
	return MySQLTryUpdate($SQL);
}
function MySQLCreateTable($SQL){
	return MySQLTryUpdate($SQL);
}
function MySQLDropTable($SQL){
	return MySQLTryUpdate($SQL);
}
//自定义的MySQL强制更新数据库的函数（成功返回true，失败或【实际影响的行数为0】返回false）
function MySQLMustUpdate($SQL){
	//mysql_query函数会自动对记录集进行读取和缓存
	//mysql_query(query,connection)的参数connection如果未规定，则使用上一个打开的连接
	//mysql_query返回非false的值，不说明任何有关影响到的或返回的行数，很有可能一条查询执行成功了但并未影响到或并未返回任何行
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	if(mysql_affected_rows()===0){
		return false;
	}
	return $result;
}
function MySQLMustDelete($SQL){
	return MySQLMustUpdate($SQL);
}
//自定义的MySQL读取数据库的函数（成功返回数据的关联和默认下标数组，失败返回false）
function MySQLSelectArray($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_array($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_array($result);
	}
	return $data;
}
//自定义的MySQL读取数据库的函数（成功只返回数据的默认下标数组，失败返回false）
function MySQLSelectDefaultArray($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_row($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_row($result);
	}
	return $data;
}
//自定义的MySQL读取数据库的函数（成功只返回数据的关联数组，失败返回false）
function MySQLSelectAssociativeArray($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$data  = array();
	$row = mysql_fetch_assoc($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_assoc($result);
	}
	return $data;
}
//自定义的MySQL读取数据库（一行）的函数（成功返回数据的关联和默认下标数组，失败或【查询到的行数为0】返回false）
function MySQLSelectRow($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$row = mysql_fetch_array($result);
	if (!$row){ return false; }
	ResultTransform($row);
	return $row;
}
//自定义的MySQL读取数据库（一行）的函数（成功只返回数据的默认下标数组，失败或【查询到的行数为0】返回false）
function MySQLSelectDefaultRow($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$row = mysql_fetch_row($result);
	if (!$row){ return false; }
	ResultTransform($row);
	return $row;
}
//自定义的MySQL读取数据库（一行）的函数（成功只返回数据的关联数组，失败或【查询到的行数为0】返回false）
function MySQLSelectAssociativeRow($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$row = mysql_fetch_assoc($result);
	if (!$row){ return false; }
	ResultTransform($row);
	return $row;
}
//自定义的MySQL读取数据库（一格）的函数（成功返回数据，失败或【查询到的行数为0】返回false）
function MySQLSelectCell($SQL){
	$result = mysql_query($SQL);
	//2006和2013错误则重试一次
	if(!$result && in_array(mysql_errno(), array(2006, 2013))){
		MySQLDisconnect();
		MySQLConnect();
		$result = mysql_query($SQL);
	}
	if(!$result){
		return false;
	}
	$row = mysql_fetch_row($result);	
	if (!$row){ return false; }
	ResultTransform($row);
	$row = $row[0];
	return $row;
}
function MySQLSelectDefaultCell($SQL){
	return MySQLSelectCell($SQL);
}
function MySQLSelectAssociativeCell($SQL){
	return MySQLSelectCell($SQL);
}
//SQL结果集解析的函数（成功返回数据的关联和默认下标数组，失败返回false）
function MySQLFetchAllArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_array($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_array($result);
	}
	return $data;
}
//SQL结果集解析的函数（成功只返回数据的默认下标数组，失败返回false）
function MySQLFetchDefaultArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_row($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_row($result);
	}
	return $data;
}
//SQL结果集解析的函数（成功只返回数据的关联数组，失败返回false）
function MySQLFetchAssociativeArray($result){
	$data  = array();
	if($result===false){return $data;}
	$row = mysql_fetch_assoc($result);
	while ($row){
		ResultTransform($row);
		$data[] = $row;
		$row = mysql_fetch_assoc($result);
	}
	return $data;
}
//获取MySQL版本号的函数
function MySQLVersion(){
	return mysql_get_server_info();
}
//获取MySQL当前运行的线程信息的函数
function MySQLProcesses(){
	return MySQLFetchAssociativeArray(mysql_list_processes());
}
//自定义的结果集处理函数，将NULL值和JSON字符串做转换
function ResultTransform(&$Array){
	foreach ($Array as &$Item){
		$Item = ($Item!==null) ? $Item : '';
		$temp = json_decode($Item,true);
		//PHP语言，数组变量的=号赋值，是进行的值的Copy，而不是指针的Copy
		$Item = ($temp===null) ? $Item : $temp;
	}
	//因为有这个函数的存在，所以MySQLSelectCell可能返回的是一个数组
}
//自定义的SQL防止注入检查的函数
function MySQLCheck(&$value)
{
	//去除斜杠(服务器配置给予的转义斜杠)
	if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	//如果不是数字则加引号（专业的转义函数）
	if (!is_numeric($value))
	{
		$value = "'" . mysql_real_escape_string($value) . "'";
	}
	return $value;
	//示例用法：
	//$user = SQLCheck($_POST['user']);
	//$pwd = SQLCheck($_POST['pwd']);
	//$sql = "SELECT * FROM users WHERE user = $user AND password = $pwd";
}
//自定义的生成Select内连接查询的SQL语句的函数
function MySQLCreateSelect($Table,$Column,$Condition){
	$num_args=func_num_args();
	if($num_args === 0 || $num_args % 3 !== 0){
		return false;
	}
	$get_args=func_get_args();
	$TableString = '';
	$ColumnString = '';
	$ConditionString = '';
	for($I = 0; $I < $num_args; $I = $I +3){
		$temp = $get_args[$I];
		$TableString .='`'.$temp.'` , ';
		foreach($get_args[$I+1] as $column){
			$ColumnString .= '`'.$temp.'`.`'.$column.'` , ';
		}
		foreach($get_args[$I+2] as $key => $value){
			if(is_array($value)===false){
				if(is_numeric($value)===false  && strpos($value,'.')!==false){//参数$value为"表名.字段"
					$value = '`' . str_replace('.','`.`',$value) . '`';//补充"`"符号
				}
				$ConditionString .=  '`'.$temp.'`.`' . $key . '` = ' . $value . ' and ';
			}
			else{
				$ConditionString .=  '( ';
				foreach($value as $v){
					if(is_numeric($v)===false  && strpos($v,'.')!==false){//参数$value包含"表名.字段"
						$v = '`' . str_replace('.','`.`',$v) . '`';//补充"`"符号
					}
					$ConditionString .=  '`'.$temp.'`.`' . $key . '` = ' . $v . ' or ';
				}
				$ConditionString = substr($ConditionString,0,strlen($ConditionString)-3);
				$ConditionString .=  ') and ';
			}
		}
	}
	$TableString= substr($TableString,0,strlen($TableString)-2);
	$ColumnString= substr($ColumnString,0,strlen($ColumnString)-2);
	$ConditionString= substr($ConditionString,0,strlen($ConditionString)-5);
	return 'select ' . $ColumnString . 'from ' . $TableString . 'where ' . $ConditionString. ';' ;
	//代码示例（单表查询）：
	//MySQLCreateSelect( "user_info" , array("id","name") , array("id"=>array("1","2")) );
	//var_dump(MySQLCreateSelect("user_info",array("id","name"),array("id"=>array("1","2"))));
	//返回值：select `user_info`.`id` , `user_info`.`name` from `user_info` where ( `user_info`.`id` = 1 or `user_info`.`id` = 2 );
	//代码示例（多表内连接查询）：
	//MySQLCreateSelect("user_info",array("id","name"),array("id"=>array("1","2")) , "order_info",array("id","time"),array("user_id"=>"user_info.id") );
	//var_dump(MySQLCreateSelect("user_info",array("id","name"),array("id"=>array("1","2")) , "order_info",array("id","time"),array("user_id"=>"user_info.id") ));
	//返回值：select `user_info`.`id` , `user_info`.`name` , `order_info`.`id` , `order_info`.`time` from `user_info` , `order_info` where ( `user_info`.`id` = 1 or `user_info`.`id` = 2 ) and `order_info`.`user_id` = `user_info`.`id`;
}
//示例使用代码：
	/*
	MySQLConnect();
	var_dump(json_encode(MySQLSelectAssociativeRow('SELECT * FROM  `DDOS_Student_Class` LIMIT 0 , 1')));
	var_dump(json_encode(array("1"=>"\r\n")));
	var_dump(MySQLProcesses());
	var_dump(MySQLVersion());
	*/
?>