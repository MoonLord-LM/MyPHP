<?php
//示例（手机获取消息）
include('../SaeMemcache.php');
include('../My.php');
header('Content-Type:text/html;charset=utf-8');

if (MySetParameterInteger("user_id")===false){
	MyException("缺少user_id参数");
}

$key = 'pc_to_phone【' . $user_id . '】';

$wait = 100*1000;
for($i = 0 ; $i < 300 ; $i ++ ){
	$value = SaeMemcacheRead($key);
	if($value!==false){break;}
	usleep($wait);
}

if($value===false){
	MySuccess($value,'没有任何信息');
}
else{
	if(SaeMemcacheDelete($key)===false){
		MyError("Memcache删除失败");
	}
	$checked_value = array();
	$login_stamp = time() - 60*5;
	foreach($value as $v){
		if($v['stamp'] > $login_stamp){
			$checked_value[] = $v;
		}
	}
	MySuccess($checked_value,'信息收取完成');
}
?>