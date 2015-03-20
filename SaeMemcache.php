<?php
//SAE的Memcache操作
//实测：SAE的Memcache的key长度限制为234Byte
//实测：SAE的Memcache的value长度限制为1048471Byte（接近1M=1048576Byte）

//自定义的Memcache添加键值对函数（成功返回true，失败或【key已经存在】返回false）
function SaeMemcacheAdd($key,$value){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
	$mmc=memcache_init();
	if($mmc==false){return false;}
	else{return memcache_add($mmc,$key,$value);}
}
//自定义的Memcache写入键值对函数（成功返回true，失败返回false）
function SaeMemcacheWrite($key,$value){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
    $mmc=memcache_init();
    if($mmc==false){return false;}
    else{return memcache_set($mmc,$key,$value);}
}
function SaeMemcacheSet($key,$value){
	//同义的函数
	return SaeMemcacheWrite($key,$value);
}
//自定义的Memcache替换键值对函数（成功返回true，失败或【key不存在】返回false）
function SaeMemcacheReplace($key,$value){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
    $mmc=memcache_init();
    if($mmc==false){return false;}
    else{return memcache_replace($mmc,$key,$value);}
}
//自定义的Memcache删除键值对函数（成功返回true，失败返回false）
function SaeMemcacheDelete($key){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
	$mmc=memcache_init();
	if($mmc==false){return false;}
	else{return memcache_delete($mmc,$key);}
}
function SaeMemcacheUnset($key){
	//同义的函数
	return SaeMemcacheDelete($key);
}
//自定义的Memcache值增加1函数（成功返回true，失败或【key不存在】返回false）
function SaeMemcacheIncrement ($key,$value){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
	$mmc=memcache_init();
	if($mmc==false){return false;}
	else{return memcache_increment($mmc,$key,$value);}
	//如果指定的key对应的元素不是数值类型并且不能被转换为数值， 会将此值修改为value
	//memcache_increment()不会在key对应元素不存在时创建元素
}
//自定义的Memcache值减少1函数（成功返回true，失败或【key不存在】返回false）
function SaeMemcacheDecrement($key,$value){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
	$mmc=memcache_init();
	if($mmc==false){return false;}
	else{return memcache_decrement($mmc,$key,$value);}
	//如果指定的key对应的元素不是数值类型并且不能被转换为数值， 会将此值修改为0
	//memcache_decrement()不会在key对应元素不存在时创建元素
}
//自定义的Memcache读取值函数（成功返回value值，失败或【key不存在】返回false）
function SaeMemcacheRead($key){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
    $mmc=memcache_init();
    if($mmc==false){return false;}
    else{return memcache_get($mmc,$key);}
}
//自定义的Memcache值末尾追加函数（成功返回true，失败或【key不存在】返回false）
function SaeMemcacheAppend($key,$appendvalue){
	//如果长度超出，则取末尾的234位
	if(strlen($key)>234){$key=substr($key,-234);}
	$mmc=memcache_init();
	if($mmc==false){return false;}
	else{$value = memcache_get($mmc,$key);}
	if($value===false){return false;}
	$value = $value . $appendvalue;
	return memcache_set($mmc,$key,$value);
}
//自定义的Memcache获取服务状态函数（成功返回Memcache服务状态信息数组，失败返回false）
function SaeMemcacheStatus(){
    $mmc=memcache_init();
    if($mmc==false){return false;}
    else{return $mmc->getStats();}
}
//自定义的Memcache获取已用百分比函数（成功返回Memcache已用百分比(小数点后保留2位)，失败返回false）
function SaeMemcachePercent(){
    $mmc=memcache_init();
    if($mmc==false){return false;}
	else{$Temp = $mmc->getStats();if($Temp==false){return false;}return round($Temp["bytes"]/$Temp["limit_maxbytes"]*100,2)."%";}
}
//自定义的Memcache清空全部数据函数（成功返回true，失败返回false）
function SaeMemcacheFlush(){
    $mmc=memcache_init();
    if($mmc==false){return false;}
    else{return $mmc->flush();}
}
function SaeMemcacheClear(){
	//同义的函数
	return SaeMemcacheFlush();
}
//检测value长度限制的代码：
/*
$i = 1048470;
var_dump(SaeMemcacheWrite($i,str_repeat('0',$i)));
var_dump(SaeMemcacheRead($i));
while(SaeMemcacheRead($i)===str_repeat('0',$i)){
	echo '<br>',$i;
	$i = $i + 1;
	var_dump(SaeMemcacheWrite($i,str_repeat('0',$i)));
	var_dump(SaeMemcacheRead($i));
}
*/
//示例使用代码：
/*
var_dump(SaeMemcacheUnSet("abc",""));
echo "abc:";var_dump(SaeMemcacheAppend("abc",""));
var_dump(SaeMemcacheSet("abc",""));
echo "abc:";var_dump(SaeMemcacheAppend("abc",""));
var_dump(SaeMemcacheRead("abc",""));
*/
?>