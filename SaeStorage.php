<?php
//SAE的SaeStorage操作
define('Sae_Storage_Domain_Name' , 'MoonLord');//Domain名称

//自定义的文件写入函数（参数为文件保存的相对路径和文件内容，成功返回文件下载路径，失败返回false）
function SaeStorageWrite($FilePath,$FileContent){
    $storage = new SaeStorage();
    $domain = Sae_Storage_Domain_Name;
    $size = -1;//长度限制
	$attr = array('encoding'=>'gzip');//文件属性（gzip压缩）
	$compress = true;//文件是否gzip压缩
    $result = $storage->write($domain,$FilePath,$FileContent,$size,$attr,$compress);
    return $result;
}
//自定义的文件上传的函数（参数为文件保存的相对路径和临时文件路径$_FILES['tmp_name']，成功返回文件下载路径，失败返回false）
function SaeStorageUpload($FilePath,$srcFileName){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;//domain名称
	//$srcFileName = $_FILE['tmp_name'];//参数类似这样
	$size = -1;//长度限制
	$attr = array('encoding'=>'gzip');//文件属性（gzip压缩）
	$compress = true;//文件是否gzip压缩
	$result = $storage->upload($domain,$FilePath,$srcFileName,$size,$attr,$compress);
	return $result;
}
//自定义的文件读取函数（参数为文件保存的相对路径，成功返回文件内容，失败返回false）
function SaeStorageRead($FilePath){
    $storage = new SaeStorage();
    $domain = Sae_Storage_Domain_Name;
    $result = $storage->read($domain,$FilePath);
    return $result;
}
//自定义的文件删除函数（参数为文件保存的相对路径，成功返回true，失败返回false）
function SaeStorageDelete($FilePath){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->delete($domain,$FilePath);
	return $result;
}
//自定义的判断文件存在函数（参数为文件保存的相对路径，存在返回true，不存在返回false）
function SaeStorageExist($FilePath){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->fileExists($domain,$FilePath);
	return $result;
}
//自定义的获取文件属性函数（参数为文件保存的相对路径，成功返回文件属性的关联数组，失败返回false）
function SaeStorageGetFileAttr($FilePath){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getAttr($domain,$FilePath);
	return $result;
}
//自定义的修改文件属性的函数（参数为文件保存的相对路径和文件属性数组，成功返回true，失败返回false）
function SaeStorageSetFileAttr($FilePath,$Attr){
	//目前支持的文件属性：
	//expires: 浏览器缓存超时，设置规则和domain expires的规则一致
	//encoding: 设置通过Web直接访问文件时，Header中的Content-Encoding
	//type: 设置通过Web直接访问文件时，Header中的Content-Type
	//private: 设置文件为私有，则文件不可被下载。
	//设置文件缓存时间为1年：$Attr = array('expires' => '1 y');
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->setFileAttr($domain,$FilePath,$Attr);
	return $result;
}
//自定义的获取Domain属性函数（成功返回Domain属性的关联数组，失败返回false）
function SaeStorageGetDomainAttr(){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getDomainAttr($domain);
	return $result;
}
//自定义的修改Domain属性的函数（参数为Domain属性数组，成功返回true，失败返回false）
function SaeStorageSetDomainAttr($Attr){
	//目前支持的Domain属性：
	//expires: 浏览器缓存超时 说明：expires 格式：[modified] TIME_DELTA，例如modified 1y或者1y，modified关键字用于指定expire时间相对于文件的修改时间。
	//默认expire时间是相对于access time。如果TIME_DELTA为负， Cache-Control header会被设置为no-cache。
	//TIME_DELTA，TIME_DELTA是一个表示时间的字符串，例如： 1y3M 48d 5s，目前支持s/m/h/d/w/M/y
	//expires_type 格式:TYPE [modified] TIME_DELTA,TYPE为文件的mimetype，例如text/html, text/plain, image/gif。多条expires-type规则之间以 , 隔开，例如：text/html 48h,image/png modified 1y
	//allowReferer: 根据Referer防盗链
	//private: 是否私有Domain
	//404Redirect: 404跳转页面，只能是本应用页面，或本应用Storage中文件。例如http://appname.sinaapp.com/404.html或http://appname-domain.stor.sinaapp.com/404.png
	//tag: Domain简介。格式：array('tag1', 'tag2')
	//示例代码：
	//$expires = '1 d';// 缓存过期设置
	//$allowReferer = array();// 防盗链设置
	//$allowReferer['hosts'][] = '*.elmerzhang.com';       // 允许访问的来源域名，千万不要带 http://。支持通配符*和?
	//$allowReferer['hosts'][] = 'elmer.sinaapp.com';
	//$allowReferer['hosts'][] = '?.elmer.sinaapp.com';
	//$allowReferer['redirect'] = 'http://elmer.sinaapp.com/'; // 盗链时跳转到的地址，仅允许跳转到本APP的页面，且不可使用独立域名。如果不设置或者设置错误，则直接拒绝访问。
	//$allowReferer = false;  // 如果要关闭一个Domain的防盗链功能，直接将allowReferer设置为false即可
	//$stor = new SaeStorage();
	//$attr = array('expires'=>$expires, 'allowReferer'=>$allowReferer);
	//$ret = $stor->setDomainAttr("test", $attr);
	//if ($ret === false) {var_dump($stor->errno(), $stor->errmsg());}
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->setDomainAttr($domain,$Attr);
	return $result;
}
//自定义的获取文件CDN地址的函数（返回文件的CDN地址）
function SaeStorageCDN($FilePath){
	//类似：http://moonlord-moonlord.stor.sinaapp.com/1.txt
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getCDNUrl($domain,$FilePath);
	return $result;
}
//自定义的获取文件URL地址的函数（返回文件的URL地址）
function SaeStorageURL($FilePath){
	//类似：http://moonlord-moonlord.stor.sinaapp.com/1.txt
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getUrl($domain,$FilePath);
	return $result;
}
//自定义的获取已用存储容量的函数（返回已使用的存储Byte大小）
function SaeStorageCapacity(){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getDomainCapacity($domain);
	return $result;
}
//自定义的获取文件数量的函数（返回当前domain下的文件数量）
function SaeStorageNum(){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = $storage->getFilesNum($domain);
	if($result===false){$result=0;}
	return $result;
}
//自定义的获取文件列表的函数（返回指定路径前缀的文件的文件名数组）
function SaeStorageList($Prefix){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = array();//默认空数组
	$limit = 100;//列表数量
	$offset = 0;//偏移量
	while($temp = $storage->getList($domain,$Prefix, $limit,$offset)){
		foreach($temp as $file) {
			$result[] = $file;
			$num ++;
		}
	}
	return $result;
	//注意：最大只能列出10000条数据。
}
//自定义的获取文件列表的函数（返回指定目录下的文件的文件名数组）
function SaeStorageListByPath($Path){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = array();//默认空数组
	$limit = 1000;//列表数量
	$offset = 0;//偏移量
	$fold = true;//折叠目录
	while($temp = $storage->getListByPath($domain,$Path, $limit,$offset,$fold)){
		foreach($temp as $file) {
			$result[] = $file;
			$num ++;
		}
	}
	return $result;
}
//自定义的获取文件列表的函数（返回指定目录下的文件的文件名数组，不折叠目录）
function SaeStorageListByNoFoldPath($Path){
	$storage = new SaeStorage();
	$domain = Sae_Storage_Domain_Name;
	$result = array();//默认空数组
	$limit = 1000;//列表数量
	$offset = 0;//偏移量
	$fold = false;//不折叠目录
	while($temp = $storage->getListByPath($domain,$Path, $limit,$offset,$fold)){
		foreach($temp as $file) {
			$result[] = $file;
			$num ++;
		}
	}
	return $result;
}
//自定义的获取Domains列表的函数（返回Domains的数组）
function SaeStorageDomains(){
	$storage = new SaeStorage();
	$result = $storage->listDomains();
	if($result===false){$result=array();}
	return $result;
}
//自定义的获取错误信息的函数（返回错误信息）
function SaeStorageErrorMessage(){
	//无错误则为'Success'
	$storage = new SaeStorage();
	$result = $storage->errmsg();
	return $result;
}
//自定义的获取错误码的函数（返回错误码）
function SaeStorageErrorNumber(){
	//无错误则为0
	$storage = new SaeStorage();
	$result = $storage->errno();
	return $result;
}
?>