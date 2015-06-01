<?php
//示例（手机获取消息）
include('../SaeMemcache.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameter("user_id")===false){
	MyException("缺少user_id参数");
}

$stamp = time();
$time = "'" . date("Y-m-d H:i:s",$stamp) . "'";
$key = 'pc_to_phone【' . $user_id . '】';

$value = SaeMemcacheRead($key);
if($value===false){
	MySuccess($value,'没有任何信息');
}
else{
	if(SaeMemcacheDelete($key)===false){
		MyError("Memcache删除失败");
	}
	MySuccess(MyJsonEncode($value),'信息收取完成');
}
?>