<?php
//MyPHP 后台API开发的开源函数库
//作者：MoonLord
//2015.11.14

//基本设置：
error_reporting(E_ALL);//显示所有警告和提示
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);//抑制警告和提示
date_default_timezone_set('PRC');//中国时区
mb_internal_encoding("UTF-8");//UTF-8编码
ignore_user_abort(true);//完整执行
ob_start();//默认开启缓冲区

//存取操作Session变量
function MySessionStart(){
	//注意：调用这些函数前不能有任何输出
	//内部已保证其它Session操作在session_start函数之后
	if(!isset($_SESSION)){
		session_start();
	}
}
function MySessionSet($key,$value){
	MySessionStart();
	$_SESSION[$key]=$value;
	return true;
}
function MySessionGet($key){
	MySessionStart();
	if(!isset($_SESSION[$key])){
		return false;
	}
	return $_SESSION[$key];
}
function MySessionHave($key){
	MySessionStart();
	return isset($_SESSION[$key]);
}
function MySessionUnset($key){
	MySessionStart();
	unset($_SESSION[$key]);
	return true;
}
function MySessionClear(){
	//销毁所有的Session
	//这之后即使对$_SESSION变量存取，也不会影响其它PHP请求
	MySessionStart();
	session_unset();
	session_destroy();
	return true;
}

//网页跳转
function MyRedirect($URL, $DelaySecond=0){
	//注意：调用这些函数前不能有任何输出
	//使用示例：MyRedirect("index.php");
	//Chrome浏览器实测：302 Moved Temporarily
	if ($DelaySecond===0)
	{
		header('Location: '.$URL);
	}
	else
	{
		header('Refresh:'.$DelaySecond.';url='.$URL);
	}	
}
function MyRedirect301($URL, $DelaySecond=0){
	header('HTTP/1.1 301 Moved Permanently');
	MyRedirect($URL, $DelaySecond);
}
function MyRedirect302($URL, $DelaySecond=0){
	header('HTTP/1.1 302 Found');
	MyRedirect($URL, $DelaySecond);
}
function MyRedirect303($URL, $DelaySecond=0){
	header('HTTP/1.1 303 See Other');
	MyRedirect($URL, $DelaySecond);
}
function MyRedirect307($URL, $DelaySecond=0){
	header('HTTP/1.1 307 Temporary Redirect');
	MyRedirect($URL, $DelaySecond);
}

//Base64（用于URL的改进）编码
function MyBase64Encode($data) { 
	return strtr(base64_encode($data), '+/', '-_'); 
}
function MyBase64EncodeWithoutEqual($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}
//Base64（用于URL的改进）解码
function MyBase64Decode($data) {
	return base64_decode(strtr($data, '-_', '+/')); 
}
function MyBase64DecodeWithoutEqual($data) {
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}
//数组转换保留为中文的JSON字符串
function MyJsonEncode($data){
	return urldecode(json_encode(MyUrlEncode($data)));
	//需要PHP版本5.4以上：
	//return json_encode($data,JSON_UNESCAPED_UNICODE);
}
//保留中文的JSON字符串转换为数组
function MyJsonDecode($data){
	$data = urlencode($data);
	$data = str_replace("%7B",'{',$data);
	$data = str_replace("%7D",'}',$data);
	$data = str_replace("%5B",'[',$data);
	$data = str_replace("%5D",']',$data);
	$data = str_replace("%3A",':',$data);
	$data = str_replace("%2C",',',$data);
	$data = str_replace("%22",'"',$data);
	return MyUrlDecode(json_decode($data,true));
}
//自定义的URL编码
function MyUrlEncode($data) {
	//可对关联数组进行URL编码，并处理换行符	
	//内部递归调用
	//用于MyJsonEncode函数调用
	if(!is_array($data)){
		$data = str_replace("\r",'\r',$data);
		$data = str_replace("\n",'\n',$data);
		$data = urlencode($data);
	}
	else {
		foreach($data as $key=>$value) {
			$data[MyUrlEncode($key)] = MyUrlEncode($value);
			if((string)MyUrlEncode($key)!==(string)$key){
				unset($data[$key]);
			}
		}
	}
	return $data;
}
//自定义的URL解码
function MyUrlDecode($data) {
	//可对关联数组进行URL解码，并处理换行符	
	//内部递归调用
	//用于MyJsonDecode函数调用
	if(!is_array($data)){
		$data = urldecode($data);
		$data = str_replace('\r',"\r",$data);
		$data = str_replace('\n',"\n",$data);
	}
	else {
		foreach($data as $key=>$value) {
			$data[MyUrlDecode($key)] = MyUrlDecode($value);
			if((string)MyUrlDecode($key)!==(string)$key){
				unset($data[$key]);
			}
		}
	}
	return $data;
}

//当前被访问的PHP的完整URL（包含URL中的参数）
function MyCompleteURL()
{
	return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
}
//当前被访问的PHP的文件URL（不包含URL中的参数）
function MyPhpURL()
{
	//strpos()函数返回字符串在另一个字符串中第一次出现的位置，如果没有找到该字符串，则返回false。
	if(strpos($_SERVER['REQUEST_URI'],"?")!=false){
		return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"?"));
	}
	return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
}
//当前被访问的PHP的文件名称（含后缀）
function MyPhpFullName()
{
	//return basename( __FILE__ );//返回My.php
	return basename( $_SERVER['REQUEST_URI'] );
}
//当前被访问的PHP的文件名称（不含后缀）
function MyPhpName()
{
	//return basename( __FILE__ , '.php');//返回My
	return basename( $_SERVER['REQUEST_URI'] , '.php' );
}

//将指定的值赋值为全局变量
function MySetValue($Name,$Value)
{
	global ${$Name};
	${$Name} = $Value;
	return true;
	//等效于在全局非函数的区域写了一句：
	//${$Name} = $Value;
}
//将HTTP（POST）参数赋值为全局变量（参数不存在或格式错误则返回Flase）
function MySetPost($ParameterName, $Length=0)
{
	if($Length==0){
		if (isset($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) == $Length) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
function MySetPostString($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
function MySetPostEnglish($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength && strlen($_POST[$ParameterName]) === mb_strlen($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
function MySetPostChinese($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== ''  && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength && (float)strlen($_POST[$ParameterName])/3 === (float)mb_strlen($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
function MySetPostNotEmpty($ParameterName)
{
	if (isset($_POST[$ParameterName]) && !empty($_POST[$ParameterName])) {return MySetValue($ParameterName,$_POST[$ParameterName]);}
	return false;
}
function MySetPostFloat($ParameterName)
{
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName])) {return MySetValue($ParameterName,(float)$_POST[$ParameterName]);}
	return false;
}
function MySetPostInteger($ParameterName)
{
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName]) && (int) $_POST[$ParameterName] == (float) $_POST[$ParameterName]) {return MySetValue($ParameterName,(int)$_POST[$ParameterName]);}
	return false;
}
function MySetPostBoolean($ParameterName)
{
	if (isset($_POST[$ParameterName]) && (strtolower($_POST[$ParameterName]) === 'true' || strtolower($_POST[$ParameterName]) === 'false')) {
		if (strtolower($_POST[$ParameterName]) === 'true') {
			$_POST[$ParameterName] = true;
		} else {
			$_POST[$ParameterName] = false;
		}
		return MySetValue($ParameterName,$_POST[$ParameterName]);
	}
	return false;
}
function MySetPostTinyInt($ParameterName)
{
	if (isset($_POST[$ParameterName]) && ($_POST[$ParameterName] === '0' || $_POST[$ParameterName] === '1')) {
		if ($_POST[$ParameterName] === '1') {
			$_POST[$ParameterName] = 1;
		} else {
			$_POST[$ParameterName] = 0;
		}
		return MySetValue($ParameterName,$_POST[$ParameterName]);
	}
	return false;
}
function MySetPostDigit($ParameterName, $Length=0)
{
	if($Length==0){
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName]))
		{return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) == $Length)
		{return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
function MySetPostDigitJsonArray($ParameterName)
{
	if (isset($_POST[$ParameterName]) && json_decode($_POST[$ParameterName],true) !== null && json_decode($_POST[$ParameterName],true) !==false) {
		$temp = json_decode($_POST[$ParameterName],true);
		foreach($temp as $item){
			if($item === '' || !ctype_digit((string)$item)){
				return false;
			}
		}
		return MySetValue($ParameterName,$temp);
	} 
	return false;
}
function MySetPostTime($ParameterName, $MinTime='', $MaxTime='')
{
	if($MinTime==='' && $MinTime===''){
		if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1)
		{return MySetValue($ParameterName,date('Y-m-d H:i:s', strtotime($_POST[$ParameterName])));}
	}
	else{
		if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1 && strtotime($_POST[$ParameterName]) >= strtotime($MinTime) && strtotime($_POST[$ParameterName]) <= strtotime($MaxTime))
		{return MySetValue($ParameterName,date('Y-m-d H:i:s', strtotime($_POST[$ParameterName])));}
	}
	return false;
}
function MySetPostAlphaNumeric($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName]))
			{return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	else{
		if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength)
			{return MySetValue($ParameterName,$_POST[$ParameterName]);}
	}
	return false;
}
//将HTTP（GET）参数赋值为全局变量（参数不存在或格式错误则返回Flase）
function MySetGet($ParameterName, $Length=0)
{
	if($Length==0){
		if (isset($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) == $Length) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
function MySetGetString($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
function MySetGetEnglish($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''   && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength && strlen($_GET[$ParameterName]) === mb_strlen($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
function MySetGetChinese($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength && (float)strlen($_GET[$ParameterName])/3 === (float)mb_strlen($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
function MySetGetNotEmpty($ParameterName)
{
	if (isset($_GET[$ParameterName]) && !empty($_GET[$ParameterName])) {return MySetValue($ParameterName,$_GET[$ParameterName]);}
	return false;
}
function MySetGetFloat($ParameterName)
{
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName])) {return MySetValue($ParameterName,(float)$_GET[$ParameterName]);}
	return false;
}
function MySetGetInteger($ParameterName)
{
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName]) && (int) $_GET[$ParameterName] == (float) $_GET[$ParameterName]) {return MySetValue($ParameterName,(int)$_GET[$ParameterName]);}
	return false;
}
function MySetGetBoolean($ParameterName)
{
	if (isset($_GET[$ParameterName]) && (strtolower($_GET[$ParameterName]) === 'true' || strtolower($_GET[$ParameterName]) === 'false')) {
		if (strtolower($_GET[$ParameterName]) === 'true') {
			$_GET[$ParameterName] = true;
		} else {
			$_GET[$ParameterName] = false;
		}
		return MySetValue($ParameterName,$_GET[$ParameterName]);
	}
	return false;
}
function MySetGetTinyInt($ParameterName)
{
	if (isset($_GET[$ParameterName]) && ($_GET[$ParameterName] === '0' || $_GET[$ParameterName] === '1')) {
		if ($_GET[$ParameterName] === '1') {
			$_GET[$ParameterName] = 1;
		} else {
			$_GET[$ParameterName] = 0;
		}
		return MySetValue($ParameterName,$_GET[$ParameterName]);
	}
	return false;
}
function MySetGetDigit($ParameterName, $Length=0)
{
	if($Length==0){
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName]))
			{return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) == $Length)
			{return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
function MySetGetDigitJsonArray($ParameterName)
{
	if (isset($_GET[$ParameterName]) && json_decode($_GET[$ParameterName],true) !== null && json_decode($_GET[$ParameterName],true) !==false) {
		$temp = json_decode($_GET[$ParameterName],true);
		foreach($temp as $item){
			if($item === '' || !ctype_digit((string)$item)){
				return false;
			}
		}
		return MySetValue($ParameterName,$temp);
	} 
	return false;
}
function MySetGetTime($ParameterName, $MinTime='', $MaxTime='')
{
	if($MinTime==='' && $MinTime===''){
		if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1)
		{return MySetValue($ParameterName,date('Y-m-d H:i:s', strtotime($_GET[$ParameterName])));}
	}
	else{
		if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1 && strtotime($_GET[$ParameterName]) >= strtotime($MinTime) && strtotime($_GET[$ParameterName]) <= strtotime($MaxTime))
		{return MySetValue($ParameterName,date('Y-m-d H:i:s', strtotime($_GET[$ParameterName])));}
	}
	return false;
}
function MySetGetAlphaNumeric($ParameterName, $MinLength=0, $MaxLength=0)
{
	if($MinLength===0 && $MaxLength===0){
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName]))
			{return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	else{
		if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength)
			{return MySetValue($ParameterName,$_GET[$ParameterName]);}
	}
	return false;
}
//将HTTP（无视POST/GET）参数赋值为全局变量（参数不存在或格式错误则返回Flase）
function MySetParameter($ParameterName, $Length=0)
{
	if (MySetPost($ParameterName, $Length)===false){
		if (MySetGet($ParameterName, $Length)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterString($ParameterName, $MinLength=0, $MaxLength=0)
{
	if (MySetPostString($ParameterName, $MinLength, $MaxLength)===false){
		if (MySetGetString($ParameterName, $MinLength, $MaxLength)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterEnglish($ParameterName, $MinLength=0, $MaxLength=0)
{
	if (MySetPostEnglish($ParameterName, $MinLength, $MaxLength)===false){
		if (MySetGetEnglish($ParameterName, $MinLength, $MaxLength)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterChinese($ParameterName, $MinLength=0, $MaxLength=0)
{
	if (MySetPostChinese($ParameterName, $MinLength, $MaxLength)===false){
		if (MySetGetChinese($ParameterName, $MinLength, $MaxLength)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterNotEmpty($ParameterName)
{
	//PHP中，0、""、null、false，用==判断是相等的，empty()都返回true，用===才能区分
	//var_dump (""==false);//结果为：bool(true)
	//var_dump (0==null);//结果为：bool(true)
	//""、0、0.0、"0"、NULL、FALSE、array()、var $var; 以及没有任何属性的对象都被认为是空的，empty()都返回true
	if (MySetPostNotEmpty($ParameterName)===false){
		if (MySetGetNotEmpty($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterFloat($ParameterName)
{
	if (MySetPostFloat($ParameterName)===false){
		if (MySetGetFloat($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterInteger($ParameterName)
{
	//注意：(int)类型是有大小限制的，-2147483648到2147483647
	if (MySetPostInteger($ParameterName)===false){
		if (MySetGetInteger($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterBoolean($ParameterName)
{
	if (MySetPostBoolean($ParameterName)===false){
		if (MySetGetBoolean($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterTinyInt($ParameterName)
{
	if (MySetPostTinyInt($ParameterName)===false){
		if (MySetGetTinyInt($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterDigit($ParameterName, $Length=0)
{
	//注意：ctype_digit检验字符串是否只由0-9构成
	//5.1.0 在 PHP 5.1.0 之前，当 text 是一个空字符串的时候，ctype_digit将返回 TRUE
	//$Length=0时，表示不限制长度
	if (MySetPostDigit($ParameterName, $Length)===false){
		if (MySetGetDigit($ParameterName, $Length)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterDigitJsonArray($ParameterName)
{
	//参数格式必须为[]或者[0,1,2,3]或者["0","1","2","3"]或[0,1.0,2.00,3.000]
	//var_dump(json_decode("[]",true));//array(0) {}
	if (MySetPostDigitJsonArray($ParameterName)===false){
		if (MySetGetDigitJsonArray($ParameterName)===false){
			return false;
		}
	}
	return true;
}
function MySetParameterTime($ParameterName, $MinTime='', $MaxTime='')
{
	//strtotime() 函数将任何英文文本的日期时间描述解析为 Unix 时间戳
	//可以使用"now"，"previous sunday"，"last Sunday"，"back of 24"，"+1 week 3 days 7 hours 5 seconds"，"today"，"tomorrow"等
	//strtotime() 函数执行成功则返回时间戳，否则返回FALSE，在 PHP 5.1.0 之前本函数在失败时返回 -1
	if (MySetPostTime($ParameterName, $MinTime ,$MaxTime )===false){
		if (MySetGetTime($ParameterName, $MinTime ,$MaxTime )===false){
			return false;
		}
	}
	return true;
}
function MySetParameterAlphaNumeric($ParameterName, $MinLength=0, $MaxLength=0)
{
	//字母和数字构成的指定长度的非空字符串
	//$MinLength=0, $MaxLength=0时，表示不限制长度
	if (MySetPostAlphaNumeric($ParameterName, $MinLength, $MaxLength)===false){
		if (MySetGetAlphaNumeric($ParameterName, $MinLength, $MaxLength)===false){
			return false;
		}
	}
	return true;
}

//执行多次调用其它函数的函数
function My($FunctionName,$FunctionParameterArray = array(array())){
	//__FUNCTION__用于指代当前函数名称
	if(count($FunctionParameterArray) === 1){
		if(!is_array($FunctionParameterArray[0])){
			$FunctionParameterArray[0] = array($FunctionParameterArray[0]);
		}
		return call_user_func_array($FunctionName,$FunctionParameterArray[0]);
	}
	else{
		foreach($FunctionParameterArray as $ParameterArray){
			if(!is_array($ParameterArray)){
				$ParameterArray = array($ParameterArray);
			}
			call_user_func_array($FunctionName,$ParameterArray);
		}
		return true;
	}
	//示例用法（检验多个参数）：
	//My("MySetGet",array("A","B"));
	//等效于如下：
	//My("MySetGet",array(array("A"),array("B")))
	//var_dump($A);
	//var_dump($B);
	//var_dump(My("MyPhpURL"));
}

//CURL操作
function MyHttpGetBody($URL,$PostData = array(),$SetHeader = array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
	//CURLOPT_RETURNTRANSFER表示将返回的内容作为变量储存，而不是直接输出
	if(is_array($PostData) && $PostData !==array()){
		//使用POST方式请求
		curl_setopt($ch, CURLOPT_POST, TRUE);
		//发送数组参数
		curl_setopt($ch, CURLOPT_POSTFIELDS, $PostData);
	}
	if(is_array($SetHeader) && $SetHeader !==array()){
		//设置Header信息
		curl_setopt($ch, CURLOPT_HTTPHEADER, $SetHeader);
		/*
		$header = array();
		$header[] = 'Content-Type:application/x-www-form-urlencoded';
		$header[] = 'Cookie:MoonLord=132357';
		curl_setopt ($ch, CURLOPT_HTTPHEADER, $header );
		*/
	}
	$BackContent = curl_exec($ch);
	//丢包重试一次
	if($BackContent === false){
		$BackContent = curl_exec($ch);
	}
	curl_close($ch);
	return $BackContent;
	//示例用法：
	//var_dump(MyHttpGetBody("http://www.baidu.com"));
	//var_dump(MyHttpFindBody("http://moonlordapi.sinaapp.com/test.php",array("123"=>"456","我" =>"你"),array("Cookie: MoonLord=132357")));
}
function MyHttpFindLocation($URL,$PostData = array(),$SetHeader = array()){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $URL);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);//获取Http-Header
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);//不获取Http-Body
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if(is_array($PostData) && $PostData !==array()){
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $PostData);
	}
	if(is_array($SetHeader) && $SetHeader !==array()){
		//设置Header信息
		curl_setopt($ch, CURLOPT_HTTPHEADER, $SetHeader);
		/*
		$header = array();
		$header[] = 'Content-Type:application/x-www-form-urlencoded';
		$header[] = 'Cookie:MoonLord=132357';
		curl_setopt ($ch, CURLOPT_HTTPHEADER, $header );
		*/
	}
	$head = curl_exec($ch);
	if($head === false){
		$head = curl_exec($ch);
	}
	var_dump($head);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);//获取网页的状态码
	curl_close($ch);
	if($httpCode===301 || $httpCode===302 || $httpCode===303 || $httpCode===307){
		//获取Http-Header的信息数组
		$headArray=explode("\r\n",trim($head));
		foreach ($headArray as $headItem){
			//获取Location信息
			if(strpos($headItem,'Location:')!==false){
				$URL = trim(str_replace('Location:','',$headItem));
				break;
			}
		}
	}
	return $URL;
	//示例用法：
	//var_dump(MyHttpFindLocation("http://www.baidu.com/s?ie=UTF-8&wd="));
}
function MyHttpFindBody($URL,$PostData = array(),$SetHeader = array()){
	$Location = MyHttpFindLocation($URL,$PostData,$SetHeader);
	while($URL!==$Location){
		$URL = $Location;
		$Location = MyHttpFindLocation($URL,$PostData,$SetHeader);
	}
	return MyHttpGetBody($URL,$PostData,$SetHeader);
	//示例用法：
	//var_dump(MyHttpFindBody("http://www.baidu.com/s?ie=UTF-8&wd="));
}

//返回API执行结果（执行die输出）
function MySuccess($data='', $description='执行成功')
{
	MySuccessReport($data, $description);
	$result = array("result"=>"success","data"=>$data,"description"=>(string)$description);
	//$result = json_encode($result);
	$result = MyJsonEncode($result);
	die($result);
}
function MyException($type='', $description='执行异常'){
	MyExceptionReport($type, $description);
	$result = array("result"=>"exception","type"=>$type,"description"=>(string)$description);
	//$result = json_encode($result);
	$result = MyJsonEncode($result);
	die($result);
}
function MyError($type='', $description='执行失败'){
	MyErrorReport($type, $description);
	$result = array("result"=>"error","type"=>$type,"description"=>(string)$description);
	//$result = json_encode($result);
	$result = MyJsonEncode($result);
	die($result);
}
//API执行结果的收集（用于监控、调优和Debug等）
function MySuccessReport($data='', $description='执行成功')
{
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	if($debug_backtrace[0]["function"]==="MySuccessReport"){unset($debug_backtrace[0]);}
	if($debug_backtrace[1]["function"]==="MySuccess"){unset($debug_backtrace[1]);}
	//var_dump($debug_backtrace);
	//待完成
}
function MyExceptionReport($type='', $description='执行异常')
{
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	if($debug_backtrace[0]["function"]==="MyExceptionReport"){unset($debug_backtrace[0]);}
	if($debug_backtrace[1]["function"]==="MyException"){unset($debug_backtrace[1]);}
	//var_dump($debug_backtrace);
	//待完成
}
function MyErrorReport($type='', $description='执行失败')
{
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	if($debug_backtrace[0]["function"]==="MyErrorReport"){unset($debug_backtrace[0]);}
	if($debug_backtrace[1]["function"]==="MyError"){unset($debug_backtrace[1]);}
	//var_dump($debug_backtrace);
	//待完成
}

//全局的错误、异常处理和脚本结束前的处理
function ErrorHandler($errno , $errstr , $errfile ,$errline , $errcontext){
	//echo "errno：";var_dump($errno);
	//echo "errstr：";var_dump($errstr);
	//echo "errfile：";var_dump($errfile);
	//echo "errline：";var_dump($errline);
	//echo "errcontext：";var_dump($errcontext);
	MyError('全局错误捕捉',$errfile."[行号".$errline."]".$errstr);
	//示例：
	//（手动创建错误）trigger_error("Uncaught Error",E_USER_WARNING);
	//注意：
	//无法处理：E_ERROR、E_PARSE、E_CORE_ERROR、E_CORE_WARNING、 E_COMPILE_ERROR、E_COMPILE_WARNING
	//无法处理：Parse error（解析错误）或Fatal error（致命错误）等
	//Parse error: syntax error, unexpected end of file
	//Fatal error: Call to undefined function
	//可以处理：E_USER_ERROR、E_USER_WARNING、E_USER_NOTICE
	//Notice: Undefined variable
}
set_error_handler("ErrorHandler",E_ALL);
function ExceptionHandler($exception){
	//var_dump($exception);
	//echo "code：";var_dump($exception->getCode());
	//echo "message：";var_dump($exception->getMessage());
	//echo "file：";var_dump($exception->getFile());
	//echo "line：";var_dump($exception->getLine());
	//echo "trace：";var_dump($exception->getTrace());
	MyError('全局异常捕捉',$exception->getFile()."[行号".$exception->getLine()."]".$exception->getMessage());
	//示例：
	//（手动创建异常）throw new Exception('Uncaught Exception');
	//注意：
	//在这个异常处理程序被调用后，脚本会停止执行	
}
set_exception_handler("ExceptionHandler");
function FinishHandler(){
	//注意：
	//register_shutdown_function是指在执行完所有PHP语句后再调用函数，不要理解成客户端关闭流浏览器页面时调用函数
	//可以这样理解调用条件：1、当页面被用户强制停止时  2、当程序代码运行超时时  3、当PHP代码执行完成时
	//发生了Fatal error: Call to undefined function时，ErrorHandler不被调用，但是FinishHandler会执行
	//发生了Parse error: syntax error, unexpected end of file时，ErrorHandler不被调用，FinishHandler也不会执行
	//echo "Finish";
	header('X-Powered-By:MoonLord');
}
register_shutdown_function("FinishHandler");


?>