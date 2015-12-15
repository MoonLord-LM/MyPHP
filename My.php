<?php
//MyPHP
//后端API开发的开源框架
//Author：MoonLord
//Version：2015.12.07

//返回值示例：
//{"code":"1029","data":"","tips":"参数username的值格式错误","description":"参数username的值应为字符个数不小于1，不大于100的纯中文字符串，请求传递的username参数的值为111"}
//{"code":"2049","data":"","tips":"后端PHP发生错误","description":"F:\\Documents\\Website\\MyPHP\\My.php[行号689]Missing argument 1 for MyHttpGetBody"}
//返回值说明：
//code：0-1023为正确的返回码（系统正常运行可能出现的情况），1025-2047为前端BUG的返回码（比如参数格式错误），2049以上为后端BUG的返回码（比如数据库连接失败）
//data：返回的有效数据内容
//tips：提示语（可以展示给用户）
//description：详细信息（用于测试和Debug使用，不建议展示给用户）
//注意：
//以1024，2048为错误码分界线
//本文件内部已占用的错误码为1025-1039（前端参数错误）、1100-1102（安全性错误）、2049-2052（后端代码错误）

//基本设置：
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);//运行模式（抑制警告和提示信息，只显示错误信息）
error_reporting(E_ALL | E_STRICT);//调试模式（显示所有级别的报错信息）
date_default_timezone_set('PRC');//时区（中国）
mb_internal_encoding('UTF-8');//内部字符编码（UTF-8）
ignore_user_abort(true);//忽略连接断开（保证代码完整执行）
ob_start();//默认开启缓冲区（可在任意位置输出header）
header('Content-Type: text/html;charset=utf-8');//输出字符编码头（UTF-8） 
header('Content-Security-Policy: default-src \'self\';img-src *;media-src *;font-src *;script-src \'self\' \'unsafe-inline\' http://*.bdimg.com;style-src \'self\' \'unsafe-inline\' http://*.bdimg.com;');//只允许任意来源的图片、视频、字体，来自百度前端CDN的脚本和样式，不允许其它第三方资源嵌入网页
$AllowRefererDomain = array(null,'127.0.0.1','www.moonlord.cn',$_SERVER["SERVER_NAME"]);//允许的Referer值的域名（包含null表示允许没有Referer值的请求）
$AllowAjaxDomain = array(null,'127.0.0.1','www.moonlord.cn',$_SERVER["SERVER_NAME"]);//允许AJAX跨域请求的来源地址的域名（包含null表示允许没有Origin值的请求）
$JsonpCallBack = null;//Jsonp回调的JS函数名（默认值为null，则不使用Jsonp方式返回数据，而是输出JSON格式的字符串）

//输出结果（使用die输出JSON格式的字符串或者Jsonp回调）
function MyResult($code='', $data='', $tips='', $description ='')
{
	global $JsonpCallBack;
	MyResultReport($code, $data, $tips, $description,$JsonpCallBack);
	$content = ob_get_contents();
	ob_end_clean();
	ob_start();
	if (strlen($content)>0)
	{
		$result = array('code'=>(string)$code,'data'=>$data,'tips'=>(string)$tips,'description'=>(string)$description,'ignore_content'=>(string)$content);
	}
	else
	{
		$result = array('code'=>(string)$code,'data'=>$data,'tips'=>(string)$tips,'description'=>(string)$description);
	}
	//$result = json_encode($result);
	$result = MyJsonEncode($result);
	if ($JsonpCallBack===null)
	{
		die($result);
	}
	else{
		$result = str_replace("\\","\\\\",$result);
		$result = str_replace("'","\\'",$result);
		die('<script type="text/javascript">'.$JsonpCallBack.'(\''.$result.'\');</script>');
		//注意：需要Content-Security-Policy的script-src中的'unsafe-inline'属性存在，才允许执行内联脚本
		//实测：无论是html代码中的script的src或者script代码动态引入js文件都会被检验来源
	}
	
}
//监测结果（完善此函数，可进行API的监控、统计、调优等）
function MyResultReport($code='', $data='', $tips='', $description ='',$callback='')
{
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	if($debug_backtrace[0]['function']==='MyResultReport'){unset($debug_backtrace[0]);}
	if($debug_backtrace[1]['function']==='MyResult'){unset($debug_backtrace[1]);}
	//var_dump($debug_backtrace);
	//待完成
}
//全局环境的错误、异常、脚本结束前的处理函数
function ErrorHandler($errno='' , $errstr='' , $errfile='' ,$errline='' , $errcontext=''){
	//echo 'errno：';var_dump($errno);
	//echo 'errstr：';var_dump($errstr);
	//echo 'errfile：';var_dump($errfile);
	//echo 'errline：';var_dump($errline);
	//echo 'errcontext：';var_dump($errcontext);
	//示例：
	//（手动创建错误）trigger_error('Uncaught Error',E_USER_WARNING);
	//注意：
	//无法处理：E_ERROR、E_PARSE、E_CORE_ERROR、E_CORE_WARNING、 E_COMPILE_ERROR、E_COMPILE_WARNING
	//无法处理：Parse error（解析错误）或Fatal error（致命错误）等
	//Parse error: syntax error, unexpected end of file
	//Fatal error: Call to undefined function
	//可以处理：E_USER_ERROR、E_USER_WARNING、E_USER_NOTICE
	//Notice: Undefined variable
	//注意：
	//在这个异常处理程序被调用后，脚本会停止执行
	MyResult('2049','','后端PHP发生错误',$errfile.'[行号'.$errline.']'.$errstr);
}
set_error_handler('ErrorHandler',E_ALL | E_STRICT);
function ExceptionHandler($exception){
	//var_dump($exception);
	//echo 'code：';var_dump($exception->getCode());
	//echo 'message：';var_dump($exception->getMessage());
	//echo 'file：';var_dump($exception->getFile());
	//echo 'line：';var_dump($exception->getLine());
	//echo 'trace：';var_dump($exception->getTrace());
	//示例：
	//（手动创建异常）throw new Exception('Uncaught Exception');
	//注意：
	//在这个异常处理程序被调用后，脚本会停止执行
	MyResult('2050','','后端PHP发生异常',$exception->getFile().'[行号'.$exception->getLine().']'.$exception->getMessage());
}
set_exception_handler('ExceptionHandler');
function FinishHandler(){
	//注意：
	//register_shutdown_function是指在执行完所有PHP语句后再调用函数，不要理解成客户端关闭流浏览器页面时调用函数
	//可以这样理解调用条件：1、当页面被用户强制停止时  2、当程序代码运行超时时  3、当PHP代码执行完成时
	//发生了Fatal error: Call to undefined function时，ErrorHandler不被调用，但是FinishHandler会执行
	//发生了Parse error: syntax error, unexpected end of file时，ErrorHandler不被调用，FinishHandler也不会执行
	header('X-Powered-By:MoonLord-MyPHP');
}
register_shutdown_function('FinishHandler');

//验证请求来源（帮助防御CSRF攻击）：
function MyAllowRefererCheck($AllowRefererDomain){
	$Allow = false;
	if(isset($_SERVER['HTTP_REFERER'])===false){
		foreach ($AllowRefererDomain as $A){
			if($A===null){
				$Allow = true;
				break;
			}
		}
	}
	else{
		$Referer = trim(trim($_SERVER['HTTP_REFERER'],'http://'),'https://');
		foreach ($AllowRefererDomain as $A){
			if(strpos($Referer, $A) === 0){
				$Allow = true;
				break;
			}
		}
	}
	if ($Allow === false)
	{
		MyResult('1100', '','请求来源不被允许','页面来源Referer的值应在指定的范围内：'.MyJsonEncode($AllowRefererDomain));
	}
	else
	{
		header('Request-Referer:'.(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'null'));
	}
}
MyAllowRefererCheck($AllowRefererDomain);
//验证允许跨域（帮助防御CSRF攻击）：
function MyAllowAjaxCheck($AllowAjaxDomain){
	//Access-Control-Allow-Origin:*是html5新增的一项标准功能，因此 IE10 以下版本的浏览器是不支持的
	$Allow = false;
	if(isset($_SERVER['HTTP_ORIGIN'])===false){
		foreach ($AllowAjaxDomain as $A){
			if($A===null){
				$Allow = true;
				break;
			}
		}
	}
	else{
		$Ajax = trim(trim($_SERVER['HTTP_ORIGIN'],'http://'),'https://');
		foreach ($AllowAjaxDomain as $A){
			if(strpos($Ajax, $A) === 0){
				$Allow = true;
				break;
			}
		}
	}
	if ($Allow === false)
	{
		MyResult('1101', '', '跨域请求不被允许','跨域请求的来源应在指定的范围内：'.MyJsonEncode($AllowAjaxDomain));
	}
	else
	{
		header('Request-Origin:'.(isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'null'));
		header('Access-Control-Allow-Origin:'.(isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:'*'));
	}
}
MyAllowAjaxCheck($AllowAjaxDomain);
//字符串过滤函数（直接输出变量到html代码中时，防御XSS攻击）
function MySafeHtml($Source){
	return htmlspecialchars($Source, ENT_QUOTES);
	//&（和号）成为 &amp;
	//"（双引号）成为 &quot;
	//'（单引号）成为 &#039;
	//<（小于）成为 &lt;
	//>（大于）成为 &gt;
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
//数组转换为JSON字符串（保留中文）
function MyJsonEncode($data){
	if (defined(JSON_UNESCAPED_UNICODE))
	{
		//需要PHP版本5.4以上
		return json_encode($data,JSON_UNESCAPED_UNICODE);
	}
	return urldecode(json_encode(MyUrlEncode($data)));
}
//JSON字符串（保留中文）转换为数组
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
//自定义的（对数组的键值进行）URL编码函数
function MyUrlEncode($data) {
	//可对关联数组进行URL编码，并处理特殊符号
	//内部递归调用
	//用于MyJsonEncode函数调用
	if(!is_array($data)){
		$data = str_replace("\\","\\\\",$data);
		$data = str_replace('"','\"',$data);
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
//自定义的（对数组的键值进行）URL解码函数
function MyUrlDecode($data) {
	//可对关联数组进行URL解码，并处理特殊符号
	//内部递归调用
	//用于MyJsonDecode函数调用
	if(!is_array($data)){
		$data = urldecode($data);
		$data = str_replace('\r',"\r",$data);
		$data = str_replace('\n',"\n",$data);
		$data = str_replace("\\\\","\\",$data);
		$data = str_replace('\"','"',$data);
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
//当前请求的UserAgent
function MyUserAgent()
{
	return $_SERVER['HTTP_USER_AGENT'];
}
//当前请求的来源IP
function MyClientIP()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$IP = explode(',',$_SERVER['HTTP_CLIENT_IP']);
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$IP = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
	}
	elseif (!empty($_SERVER['REMOTE_ADDR'])) {
		$IP = explode(',',$_SERVER['REMOTE_ADDR']);
	}
	else {
		$IP[0] = 'Unknown';   
	}
	header('Request-IP:'.$IP[0]);
	return $IP[0];
}
$MyClientIP = MyClientIP();
//当前时间（'Y-m-d H:i:s'格式，用于存入MySQL的datetime类型）
function MyDateTime(){
	return '\'' . date('Y-m-d H:i:s',time()) . '\'';
}

//Session操作（与$MyClientIP信息绑定）
function MySessionStart(){
	if(!isset($_SESSION)){
		//PHP 5.2.0	才加入session_set_cookie_params的第5个参数以及setcookie的第7个参数，httponly
		//之前版本可以使用header("Set-Cookie: key=value; httponly");达到类似效果
		session_set_cookie_params(0, null, null, null, true);
		session_start();
		if(session_save_path()===''){//用于拦截Notice消息：Unknown[行号0]Unknown: Failed to write session data (files). Please verify that the current setting of session.save_path is correct ()
			MySessionClear();
			MyResult('2051','','后端代码执行失败',__FILE__.'[行号'.__LINE__.']'.'session_save_path()返回值为空字符串');
		}
		//setcookie(session_name(),session_id(),time()+1800,null, null, null, true);
	}
}
function MySessionSet($key,$value){
	global $MyClientIP;
	MySessionStart();
	if (is_string($key)===false || is_numeric($key) && (int)$key == (float)$key)
	{
		MyResult('2052','','后端PHP代码缺陷',__FILE__.'[行号'.__LINE__.']'.'$_SESSION是以关联数组保存的，所以$key值不能为数值类型或者整数字符串，参数$key的值为'.$key);
	}
	$_SESSION[$key]='';
	$_SESSION[$MyClientIP.$key]=$value;
	//$_SESSION是以关联数组保存的，所以$key值不能为数值或者整数字符串，否则会报错
	//$_SESSION的$key值错误会在MyResult函数执行完成之后发生，因此会导致输出结果不为JSON格式字符串
	//$key不允许的值，例如：13.0  "23"  "-33"
}
function MySessionGet($key){
	global $MyClientIP;
	MySessionStart();
	if(!isset($_SESSION[$key])){
		return false;
	}
	else
	{
		if(!isset($_SESSION[$MyClientIP.$key])){
			MyResult('1102','','检测到请求IP发生变化','此次请求的来源IP为'.$MyClientIP);
		}
	}	
	return $_SESSION[$MyClientIP.$key];
}
function MySessionHave($key){
	global $MyClientIP;
	MySessionStart();
	return isset($_SESSION[$key]) && isset($_SESSION[$MyClientIP.$key]);
}
function MySessionUnset($key){
	global $MyClientIP;
	MySessionStart();
	unset($_SESSION[$key]);
	unset($_SESSION[$MyClientIP.$key]);
}
function MySessionClear(){
	MySessionStart();
	session_unset();//释放所有$_SESSION变量，$_SESSION变为array()
	session_destroy();//结束Session会话，删除Session文件，释放对应的SessionID
	//调用session_destroy函数后，对$_SESSION的任何操作，都将对其它PHP无效
	//调用2次session_destroy函数会报错：session_destroy(): Trying to destroy uninitialized session
}

//重定向跳转
function MyRedirect($URL,$Code=0){
	//状态码$Code可以为301、302、302、307
	if (in_array($Code,array(301,302,303,307)))
	{
		switch ($Code)
		{
			case 301:
				header('HTTP/1.1 301 Moved Permanently');
				break;  
			case 302:
				header('HTTP/1.1 302 Found');
				break;
			case 303:
				header('HTTP/1.1 303 See Other');
				break;
			case 307:
				header('HTTP/1.1 307 Temporary Redirect');
				break;
			default:
		}
	}
	header('Location: '.$URL);
	//使用示例：MyRedirect("index.php");
	//Chrome浏览器实测：302 Moved Temporarily
}
//延时刷新跳转
function MyRefresh($URL, $DelaySecond=0){
	header('Refresh:'.$DelaySecond.';url='.$URL);
}

//将指定的值赋值为全局变量
function MySetValue($Name,$Value)
{
	global ${$Name};
	${$Name} = $Value;
	//等效于在全局非函数的区域写了一句：
	//${$Name} = $Value;
}
//参数检查
//存在：把参数赋值为同名的全局变量 | 不存在：有默认值则赋值为默认值，没有默认值则报错
function MyCheckParameterGet($ParameterName,$DefaultValue=null)
{
	if (isset($_GET[$ParameterName])){
		MySetValue($ParameterName,$_GET[$ParameterName]);
		return;
	}
	if (isset($DefaultValue)){
		MySetValue($ParameterName,$DefaultValue);
		return;
	}
	MyResult('1025', $data='', $tips='请求缺少'.$ParameterName.'参数', $description ='请求需要的'.$ParameterName.'参数应使用GET方式传递');
}
function MyCheckParameterPost($ParameterName,$DefaultValue=null)
{
	if (isset($_POST[$ParameterName])){
		MySetValue($ParameterName,$_POST[$ParameterName]);
		return;
	}
	if (isset($DefaultValue)){
		MySetValue($ParameterName,$DefaultValue);
		return;
	}
	MyResult('1026', $data='', $tips='请求缺少'.$ParameterName.'参数', $description ='请求需要的'.$ParameterName.'参数应使用POST方式传递');
}
function MyCheckParameter($ParameterName,$DefaultValue=null)
{
	if (isset($_POST[$ParameterName])){
		MySetValue($ParameterName,$_POST[$ParameterName]);
		return;
	}
	if (isset($_GET[$ParameterName])){
		MySetValue($ParameterName,$_GET[$ParameterName]);
		return;
	}
	if (isset($DefaultValue)){
		MySetValue($ParameterName,$DefaultValue);
		return;
	}
	MyResult('1027', $data='', $tips='请求缺少'.$ParameterName.'参数', $description ='请求需要的'.$ParameterName.'参数可使用POST、GET方式传递');
}
//满足条件：无操作 | 不满足条件：报错
//限制长度
function MyCheckString($ParameterName, $MinLength=1, $MaxLength=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && mb_strlen($Parameter) >= $MinLength && mb_strlen($Parameter) <= $MaxLength){
		return;
	}
	MyResult('1028', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为字符个数不小于'.$MinLength.'，不大于'.$MaxLength.'的字符串，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//纯英文（字母、数字、英文特殊符号）
function MyCheckEnglish($ParameterName, $MinLength=1, $MaxLength=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && strlen($Parameter) >= $MinLength && strlen($Parameter) <= $MaxLength && strlen($Parameter) === mb_strlen($Parameter)){
		return;
	}
	MyResult('1029', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为字符个数不小于'.$MinLength.'，不大于'.$MaxLength.'的纯英文字符串，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//纯中文（汉字，中文特殊符号）
function MyCheckChinese($ParameterName, $MinLength=1, $MaxLength=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && mb_strlen($Parameter) >= $MinLength && mb_strlen($Parameter) <= $MaxLength && (float)strlen($Parameter)/3 === (float)mb_strlen($Parameter)){
		return;
	}
	MyResult('1030', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为字符个数不小于'.$MinLength.'，不大于'.$MaxLength.'的纯中文字符串，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//纯数字
function MyCheckDigit($ParameterName, $MinLength=1, $MaxLength=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && ctype_digit($Parameter) && strlen($Parameter) >= $MinLength && strlen($Parameter) <= $MaxLength){
		return;
	}
	MyResult('1031', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为字符个数不小于'.$MinLength.'，不大于'.$MaxLength.'的纯数字组成的字符串，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//字母和数字
function MyCheckAlphaNumeric($ParameterName, $MinLength=1, $MaxLength=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && ctype_alnum($Parameter) && strlen($Parameter) >= $MinLength && strlen($Parameter) <= $MaxLength){
		return;
	}
	MyResult('1032', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为字符个数不小于'.$MinLength.'，不大于'.$MaxLength.'的数字和字母组成的字符串，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//浮点数（限制大小）
function MyCheckFloat($ParameterName, $MinValue=1, $MaxValue=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && is_numeric($Parameter) &&  (float)$Parameter>= $MinValue && (float)$Parameter <= $MaxValue){
		${$ParameterName} = (float)$Parameter;
		return;
	}
	MyResult('1033', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为不小于'.$MinValue.'，不大于'.$MaxValue.'的数值，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//整数（限制大小）
function MyCheckInteger($ParameterName, $MinValue=1, $MaxValue=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && is_numeric($Parameter) && (int)$Parameter == (float)$Parameter &&  (float)$Parameter>= $MinValue && (float)$Parameter <= $MaxValue){
		${$ParameterName} = (int)$Parameter;
		return;
	}
	MyResult('1034', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为不小于'.$MinValue.'，不大于'.$MaxValue.'的整数，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//true或false
function MyCheckBoolean($ParameterName)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && (strtolower($Parameter) === 'true' || strtolower($Parameter) === 'false')){
		${$ParameterName} = (bool)$Parameter;
		return;
	}
	MyResult('1035', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为true或false，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//0或1
function MyCheckTinyInt($ParameterName)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && (strtolower($Parameter) === '0' || strtolower($Parameter) === '1')){
		${$ParameterName} = (int)$Parameter;
		return;
	}
	MyResult('1036', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为0或1，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//时间字符串
function MyCheckTime($ParameterName)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (isset($Parameter) && strtotime($Parameter) !== false && strtotime($Parameter) !== -1){
		${$ParameterName} = date('Y-m-d H:i:s', strtotime($Parameter));
		return;
	}
	MyResult('1037', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为0或1，请求传递的'.$ParameterName.'参数的值为'.$Parameter);
}
//整数数组（限制大小）
function MyCheckIntegerArray($ParameterName, $MinValue=1, $MaxValue=PHP_INT_MAX)
{
	global ${$ParameterName};
	$Parameter = ${$ParameterName};
	if (!is_array($Parameter))
	{
		MyResult('1038', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为不小于'.$MinValue.'，不大于'.$MaxValue.'的整数的数组，请求传递的'.$ParameterName.'参数的值不是数组，参数的值为'.$Parameter);
	}
	for ($i = 0; $i < count($Parameter); $i++)
	{
		$P = $Parameter[$i];
		if (isset($P) && is_numeric($P) && (int)$P == (float)$P &&  (float)$P>= $MinValue && (float)$P <= $MaxValue){
			${$ParameterName}[$i] = (int)$P;
		}
		else
		{
			MyResult('1039', $data='', $tips='参数'.$ParameterName.'的值格式错误', $description ='参数'.$ParameterName.'的值应为不小于'.$MinValue.'，不大于'.$MaxValue.'的整数的数组，请求传递的'.$ParameterName.'参数的数组元素的值不符合限制条件，参数的值为'.MyJsonEncode($Parameter));
		}
	}
}

//Curl访问外部网络
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
	//失败则重试一次
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
?>