<?php
//示例（PC发送消息）
include('../SaeMemcache.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameterInteger("order_id")===false){
	MyException("缺少order_id参数");
}
if (MySetParameter("information")===false){
	MyException("缺少information参数");
}
if (MySetParameterInteger("user_id")===false){
	MyException("缺少user_id参数");
}

$stamp = time();
$time = date("Y-m-d H:i:s",$stamp);
$key = 'pc_to_phone【' . $user_id . '】';

//写法1：
/*
$value = SaeMemcacheRead($key);
if($value===false){
	$value = array(array("order_id"=>$order_id,"information"=>$information,"stamp"=>$stamp,"time"=>$time));
	if(SaeMemcacheSet($key,$value)===false){
		MyError("Memcache写入失败");
	}
}
else{
	$value[] = array("order_id"=>$order_id,"information"=>$information,"stamp"=>$stamp,"time"=>$time);
	if(SaeMemcacheSet($key,$value)===false){
		MyError("Memcache写入失败");
	}
}
*/

//写法2：
$value = array(array("order_id"=>$order_id,"information"=>$information,"stamp"=>$stamp,"time"=>$time));
if(SaeMemcacheAdd($key,$value)===false){
	$value = array("order_id"=>$order_id,"information"=>$information,"stamp"=>$stamp,"time"=>$time);
	if(SaeMemcacheAppendArray($key,$value)===false){
		MyError("Memcache写入失败");
	}
}

MySuccess("信息发送完成");
?>