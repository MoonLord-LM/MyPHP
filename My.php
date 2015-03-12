<?php
//MyPHP开源库
//更新时间：2015.3.12，作者：MoonLord

//基本设置：
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);//抑制警告和提示
date_default_timezone_set('PRC');//中国时区
mb_internal_encoding("UTF-8");//UTF-8编码
ignore_user_abort(true);//完整执行

//成功结束（正常的返回结果）
function MySuccess($data, $description)
{
	SuccessReport($data, $description);
	$result = array("result"=>"success","data"=>$data,"description"=>$description);
	$result = MyJson_decode($result);
	die($result);
}
//预定义的异常$type：
define('Exception_Parameter_Missing' , '参数丢失');
define('Exception_Parameter_Empty' , '参数为空值');
define('Exception_Parameter_NotNumeric' , '参数不是数值');
define('Exception_Parameter_NotBoolean' , '参数不是布尔值');
define('Exception_Parameter_NotTinyInt' , '参数不是0或1');
define('Exception_Parameter_NotInteger' , '参数不是整数');
define('Exception_Parameter_NotDigit' , '参数不是数字');
define('Exception_Parameter_NotLongDigit' , '参数不是指定长度的数字');
define('Exception_Parameter_NotAlphaNumeric' , '参数不是数字或字母');
define('Exception_Parameter_NotFixedAlphaNumeric' , '参数不是指定长度的数字或字母');
define('Exception_Parameter_NotTime' , '参数不是时间');
define('Exception_Parameter_NotFixedTime' , '参数不是指定范围的时间');
define('Exception_Parameter_NotDigitJsonArray' , '参数不是数字组成的JSON数组');
define('Exception_Parameter_NotLong' , '参数不是指定字节长度的字符串');
define('Exception_Parameter_NotLongCharacter' , '参数不是指定字符个数的字符串');
define('Exception_Parameter_NotLongEnglish' , '参数不是指定字符个数的英文字符串');
define('Exception_Parameter_NotLongChinese' , '参数不是指定字符个数的中文字符串');
define('Exception_Parameter_NotFixedLong' , '参数不是指定字节长度的字符串');
define('Exception_Parameter_NotFixedLongCharacter' , '参数不是指定字符个数的字符串');
define('Exception_Parameter_NotFixedLongEnglish' , '参数不是指定字符个数的英文字符串');
define('Exception_Parameter_NotFixedLongChinese' , '参数不是指定字符个数的中文字符串');
define('Exception_OptionalParameter_Empty' , '可选参数为空值');
define('Exception_OptionalParameter_NotNumeric' , '可选参数不是数值');
define('Exception_OptionalParameter_NotBoolean' , '可选参数不是布尔值');
define('Exception_OptionalParameter_NotTinyInt' , '可选参数不是0或1');
define('Exception_OptionalParameter_NotInteger' , '可选参数不是整数');
define('Exception_OptionalParameter_NotDigit' , '可选参数不是数字');
define('Exception_OptionalParameter_NotLongDigit' , '可选参数不是指定长度的数字');
define('Exception_OptionalParameter_NotAlphaNumeric' , '可选参数不是数字或字母');
define('Exception_OptionalParameter_NotFixedAlphaNumeric' , '可选参数不是指定长度的数字或字母');
define('Exception_OptionalParameter_NotTime' , '可选参数不是时间');
define('Exception_OptionalParameter_NotFixedTime' , '可选参数不是指定范围的时间');
define('Exception_OptionalParameter_NotDigitJsonArray' , '可选参数不是数字组成的JSON数组');
define('Exception_OptionalParameter_NotLong' , '可选参数不是指定字节长度的字符串');
define('Exception_OptionalParameter_NotLongCharacter' , '可选参数不是指定字符个数的字符串');
define('Exception_OptionalParameter_NotLongEnglish' , '可选参数不是指定字符个数的英文字符串');
define('Exception_OptionalParameter_NotLongChinese' , '可选参数不是指定字符个数的中文字符串');
define('Exception_OptionalParameter_NotFixedLong' , '可选参数不是指定字节长度的字符串');
define('Exception_OptionalParameter_NotFixedLongCharacter' , '可选参数不是指定字符个数的字符串');
define('Exception_OptionalParameter_NotFixedLongEnglish' , '可选参数不是指定字符个数的英文字符串');
define('Exception_OptionalParameter_NotFixedLongChinese' , '可选参数不是指定字符个数的中文字符串');
//异常终止（需要请求方处理）
function MyException($type, $description)
{
	ExceptionReport($type, $description);
	switch ($type)
	{
		case Exception_Parameter_Missing:
			$description = 'Http参数【' . $description . '】的值必须设置，此次请求未设置这个参数';
			break;
		case Exception_Parameter_Empty:
			$description = 'Http参数【' . $description . '】的值必须设置为非零非空的值，此次请求这个参数的值为0，或者为空字符串';
			break;
		case Exception_Parameter_NotNumeric:
			$description = 'Http参数【' . $description . '】的值必须设置为数值，此次请求这个参数的值不是数值';
			break;
		case Exception_Parameter_NotBoolean:
			$description = 'Http参数【' . $description . '】的值必须设置为true或false，此次请求这个参数的值不是true或false';
			break;
		case Exception_Parameter_NotTinyInt:
			$description = 'Http参数【' . $description . '】的值必须设置为0或1，此次请求这个参数的值不是0或1';
			break;
		case Exception_Parameter_NotInteger:
			$description = 'Http参数【' . $description . '】的值必须设置为-2147483648到2147483647的整数，此次请求这个参数的值不是-2147483648到2147483647的整数';
			break;
		case Exception_Parameter_NotDigit:
			$description = 'Http参数【' . $description . '】的值必须设置为数字0-9构成的非空字符串，此次请求这个参数的值不是数字0-9构成的非空字符串';
			break;
		case Exception_Parameter_NotLongDigit:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为数字0-9构成的' . $description[1] . '位字符串，此次请求这个参数的值不是数字0-9构成的' . $description[1] . '位字符串';
			break;
		case Exception_Parameter_NotAlphaNumeric:
			$description = 'Http参数【' . $description . '】的值必须设置为字母和数字构成的非空字符串，此次请求这个参数的值不是字母和数字构成的非空字符串';
			break;
		case Exception_Parameter_NotFixedAlphaNumeric:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为字母和数字构成的不少于' . $description[1] . '位，不多于' . $description[2] . '位的非空字符串，此次请求这个参数的值不是字母和数字构成的不少于' . $description[1] . '位，不多于' . $description[2] . '位的非空字符串';
			break;
		case Exception_Parameter_NotTime:
			$description = 'Http参数【' . $description . '】的值必须设置为标准时间字符串，此次请求这个参数的值不是标准时间字符串';
			break;
		case Exception_Parameter_NotFixedTime:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为不早于' . $description[1] . '，不晚于' . $description[2] . '的标准时间字符串，此次请求这个参数的值不是不早于' . $description[1] . '，不晚于' . $description[2] . '的标准时间字符串';
			break;
		case Exception_Parameter_NotDigitJsonArray:
			$description = 'Http参数【' . $description . '】的值必须设置为数字0-9构成的非空字符串的JSON数组，此次请求这个参数的值不是数字0-9构成的非空字符串的JSON数组';
			break;
		case Exception_Parameter_NotLong:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为字节个数为' . $description[1] . '的字符串，此次请求这个参数的值不是字节个数为' . $description[1] . '的字符串';
			break;
		case Exception_Parameter_NotLongCharacter:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为中英文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是中英文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_Parameter_NotLongEnglish:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为纯英文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是纯英文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_Parameter_NotLongChinese:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为纯中文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是纯中文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_Parameter_NotFixedLong:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为字节个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串，此次请求这个参数的值不是字节个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串';
			break;
		case Exception_Parameter_NotFixedLongCharacter:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为中英文的字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串，此次请求这个参数的值不是中英文字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串';
			break;
		case Exception_Parameter_NotFixedLongEnglish:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯英文的字符串，此次请求这个参数的值不是字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯英文的字符串';
			break;
		case Exception_Parameter_NotFixedLongChinese:
			$description = 'Http参数【' . $description[0] . '】的值必须设置为字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯中文的字符串，此次请求这个参数的值不是字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯中文的字符串';
			break;
		case Exception_OptionalParameter_Empty:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为非零非空的值，此次请求这个参数的值为0，或者为空字符串';
			break;
		case Exception_OptionalParameter_NotNumeric:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为数值，此次请求这个参数的值不是数值';
			break;
		case Exception_OptionalParameter_NotBoolean:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为true或false，此次请求这个参数的值不是true或false';
			break;
		case Exception_OptionalParameter_NotTinyInt:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为0或1，此次请求这个参数的值不是0或1';
			break;
		case Exception_OptionalParameter_NotInteger:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为-2147483648到2147483647的整数，此次请求这个参数的值不是-2147483648到2147483647的整数';
			break;
		case Exception_OptionalParameter_NotDigit:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为数字0-9构成的非空字符串，此次请求这个参数的值不是数字0-9构成的非空字符串';
			break;
		case Exception_OptionalParameter_NotLongDigit:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为数字0-9构成的' . $description[1] . '位字符串，此次请求这个参数的值不是数字0-9构成的' . $description[1] . '位字符串';
			break;
		case Exception_OptionalParameter_NotAlphaNumeric:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为字母和数字构成的非空字符串，此次请求这个参数的值不是字母和数字构成的非空字符串';
			break;
		case Exception_OptionalParameter_NotFixedAlphaNumeric:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为字母和数字构成的不少于' . $description[1] . '位，不多于' . $description[2] . '位的非空字符串，此次请求这个参数的值不是字母和数字构成的不少于' . $description[1] . '位，不多于' . $description[2] . '位的非空字符串';
			break;
		case Exception_OptionalParameter_NotTime:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为标准时间字符串，此次请求这个参数的值不是标准时间字符串';
			break;
		case Exception_OptionalParameter_NotFixedTime:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为不早于' . $description[1] . '，不晚于' . $description[2] . '的标准时间字符串，此次请求这个参数的值不是不早于' . $description[1] . '，不晚于' . $description[2] . '的标准时间字符串';
			break;
		case Exception_OptionalParameter_NotDigitJsonArray:
			$description = '可选Http参数【' . $description . '】的值要么不设置，要么必须设置为数字0-9构成的非空字符串的JSON数组，此次请求这个参数的值不是数字0-9构成的非空字符串的JSON数组';
			break;
		case Exception_OptionalParameter_NotLong:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为字节个数为' . $description[1] . '的字符串，此次请求这个参数的值不是字节个数为' . $description[1] . '的字符串';
			break;
		case Exception_OptionalParameter_NotLongCharacter:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为中英文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是中英文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_OptionalParameter_NotLongEnglish:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为纯英文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是纯英文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_OptionalParameter_NotLongChinese:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为纯中文字符个数为' . $description[1] . '的字符串，此次请求这个参数的值不是纯中文字符个数为' . $description[1] . '的字符串';
			break;
		case Exception_OptionalParameter_NotFixedLong:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为字节个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串，此次请求这个参数的值不是字节个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串';
			break;
		case Exception_OptionalParameter_NotFixedLongCharacter:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为中英文的字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串，此次请求这个参数的值不是中英文字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的字符串';
			break;
		case Exception_OptionalParameter_NotFixedLongEnglish:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯英文的字符串，此次请求这个参数的值不是字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯英文的字符串';
			break;
		case Exception_OptionalParameter_NotFixedLongChinese:
			$description = '可选Http参数【' . $description[0] . '】的值要么不设置，要么必须设置为字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯中文的字符串，此次请求这个参数的值不是字符个数为不少于' . $description[1] . '个，不多于' . $description[2] . '个的纯中文的字符串';
			break;
		default:
	}
	$result = array("result"=>"exception","type"=>$type,"description"=>$description);
	$result = MyJson_decode($result);
	die($result);
}
//预定义的错误$type：
define('Error_PhpFunction_MistakenlyUsed' , 'PHP函数调用错误');
//错误终止（需要服务器记录）
function MyError($type, $description)
{
	ErrorReport($type, $description);
	switch ($type)
	{
		case Error_PhpFunction_MistakenlyUsed:
			$description = 'PHP函数'.$description;
			break;
		default:
	}
	$result = array("result"=>"error","code"=>$type,"description"=>$description);
	$result = MyJson_decode($result);
	die($result);
}

//成功报告（仅限Success函数调用）
function SuccessReport($data, $description)
{
	//待完成
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	unset($debug_backtrace[0]);
	unset($debug_backtrace[1]);
}
//异常报告（仅限Exception函数调用）
function ExceptionReport($type, $description)
{
	//待完成
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	unset($debug_backtrace[0]);
	unset($debug_backtrace[1]);
	var_dump($debug_backtrace);
}
//错误报告（仅限Error函数调用）
function ErrorReport($type, $description)
{
	//待完成
	$debug_backtrace = debug_backtrace();
	$count = count($debug_backtrace);
	unset($debug_backtrace[0]);
	unset($debug_backtrace[1]);
	var_dump($debug_backtrace);
}

//当前被访问的网址的完整URL
function MyCompleteURL()
{
	return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
}
//当前被访问的PHP文件的URL
function MyPhpURL()
{
	//strpos()函数返回字符串在另一个字符串中第一次出现的位置，如果没有找到该字符串，则返回false。
	if(strpos($_SERVER['REQUEST_URI'],"?")!=false){
		return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"?"));
	}
	return $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
}

//给指定名称的全局变量赋值
function SetValue($ParameterName,$Value)
{
	global ${$ParameterName};
	${$ParameterName} = $Value;
}
//将POST的变量赋值为同名的全局变量（包含类型转换）
function SetPost($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = $_POST[$ParameterName];
}
function SetPostFloat($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = (float)$_POST[$ParameterName];
}
function SetPostInteger($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = (int)$_POST[$ParameterName];
}
function SetPostDate($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = date('Y-m-d H:i:s', strtotime($_POST[$ParameterName]));
}
function SetPostBoolean($ParameterName)
{
	global ${$ParameterName};
	if (strtolower($_POST[$ParameterName]) === 'true') {
		${$ParameterName} = true;
	} else {
		${$ParameterName} = false;
	}
}
function SetPostTinyInt($ParameterName)
{
	global ${$ParameterName};
	if ($_POST[$ParameterName] === '1') {
		${$ParameterName} = 1;
	} else {
		${$ParameterName} = 0;
	}
}
//将Get的变量赋值为同名的全局变量（包含类型转换）
function SetGet($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = $_GET[$ParameterName];
}
function SetGetFloat($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = (float)$_GET[$ParameterName];
}
function SetGetInteger($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = (int)$_GET[$ParameterName];
}
function SetGetDate($ParameterName)
{
	global ${$ParameterName};
	${$ParameterName} = date('Y-m-d H:i:s', strtotime($_GET[$ParameterName]));
}
function SetGetBoolean($ParameterName)
{
	global ${$ParameterName};
	if (strtolower($_GET[$ParameterName]) === 'true') {
		${$ParameterName} = true;
	} else {
		${$ParameterName} = false;
	}
}
function SetGetTinyInt($ParameterName)
{
	global ${$ParameterName};
	if ($_GET[$ParameterName] === '1') {
		${$ParameterName} = 1;
	} else {
		${$ParameterName} = 0;
	}
}
//给不存在的Http参数赋值为默认初始值的全局变量（包含类型转换）
function CheckSetDefault($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = '';
		return true; 
	}
	return false; 
}
function CheckSetDefaultFloat($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = (float)0;
		return true; 
	}
	return false; 
}
function CheckSetDefaultInteger($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = (int)0;
		return true; 
	}
	return false; 
}
function CheckSetDefaultDate($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = date('Y-m-d H:i:s', 0);
		return true; 
	}
	return false; 
}
function CheckSetDefaultBoolean($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = false;
		return true; 
	}
	return false; 
}
function CheckSetDefaultArray($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		global ${$ParameterName};
		${$ParameterName} = array();
		return true; 
	}
	return false; 
}

//检验指定Http参数必须存在
function ExistCheck($ParameterName)
{
	if (!isset($_POST[$ParameterName]) && !isset($_GET[$ParameterName])) {
		MyException(Exception_Parameter_Missing, $ParameterName);
	}
}

//检验PHP函数的参数的值必须为正整数
function LengthCheck($FUNCTION,$LengthName,$Length)
{
	if (!isset($Length) || !is_numeric($Length) || (int) $Length != (float) $Length || (int) $Length < 1) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$LengthName.'必须为正整数，此次调用这个PHP函数，参数'.$LengthName.'的值为【' . $Length . '】');
	}
}
//检验PHP函数的参数的值必须为时间字符串
function TimeCheck($FUNCTION,$TimeName,$Time)
{
	if (!isset($Time) || strtotime($Time) === false && strtotime($Time) === -1) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$TimeName.'必须为表示时间的字符串，此次调用这个PHP函数，参数'.$TimeName.'的值为【' . $Time . '】');
	}
}
//检验PHP函数的参数的值必须为数组
function ArrayCheck($FUNCTION,$ArrayName,$Array)
{
	if (!isset($Array) || !is_array($Array)) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$ArrayName.'必须为数组，此次调用这个PHP函数，参数'.$ArrayName.'的值为【' . $Array . '】');
	}
}
//检验PHP函数的参数的值必须为已经定义的函数的名称
function FunctionCheck($FUNCTION,$FunctionName,$Function)
{
	if (!isset($Function) || !function_exists($Function)) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$FunctionName.'必须为已经定义的函数的名称，此次调用这个PHP函数，参数'.$FunctionName.'的值为【' . $Function . '】');
	}
}
//检验PHP函数的参数的值必须为非空字符串
function StringCheck($FUNCTION,$StringName,$String)
{
	if (!isset($String)) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$StringName.'必须为非空字符串，此次调用这个PHP函数，参数'.$StringName.'的值未设置');
	}
	if ($String==="") {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$StringName.'必须为非空字符串，此次调用这个PHP函数，参数'.$StringName.'的值为空字符串');
	}
	if (!is_string($String)) {
		MyError(Error_PhpFunction_MistakenlyUsed, $FUNCTION.'的参数'.$StringName.'必须为非空字符串，此次调用这个PHP函数，参数'.$StringName.'的值不是字符串');
	}
}

//对Http参数进行批量检验，参数为：要检验的Http参数名称的字符串数组，检验使用的函数名称（Must或Can），检验使用的函数的参数的数组（从第二位参数开始）
function MyCheck($ParameterNameArray,$FunctionName,$FunctionParameterArray = array()){
	//参数检验（第三个参数可选，但是只能设置为数组）
	ArrayCheck(__FUNCTION__ , '$ParameterNameArray' , $ParameterNameArray );
	ArrayCheck(__FUNCTION__ , '$FunctionParameterArray' , $FunctionParameterArray );
	FunctionCheck(__FUNCTION__ , '$FunctionName' , $FunctionName );
	//参数重新排列，将$ParameterNameArray[$i]添加到$FunctionParameterArray[0]
	for($i = 1;$i<count($FunctionParameterArray);$i++){
		$FunctionParameterArray[$i] = $FunctionParameterArray[$i-1];
	}
	for($i = 0;$i<count($ParameterNameArray);$i++){
		$FunctionParameterArray[0] = $ParameterNameArray[$i];
		call_user_func_array($FunctionName,$FunctionParameterArray);
	}
}

//检验指定Http参数必须存在，并赋值为同名全局变量
function MyMustExist($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//此时Http参数的值可能为空字符串
	if (isset($_POST[$ParameterName])) {SetPost($ParameterName);return;}
	if (isset($_GET[$ParameterName])) {SetGet($ParameterName);return;}
	MyException(Exception_Parameter_Missing, $ParameterName);
}
//检验指定Http参数必须存在且非零非空，并赋值为同名全局变量
function MyMustNotEmpty($ParameterName)
{
	//PHP中，0、""、null、false，用==判断是相等的，empty()都返回true，用===才能区分
	//var_dump (""==false);//结果为：bool(true)
	//var_dump (0==null);//结果为：bool(true)
	//""、0、0.0、"0"、NULL、FALSE、array()、var $var; 以及没有任何属性的对象都被认为是空的，empty()都返回true
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	ExistCheck($ParameterName);
	if (!empty($_POST[$ParameterName])) {SetPost($ParameterName);return;}
	if (!empty($_GET[$ParameterName])) {SetGet($ParameterName);return;}
	MyException(Exception_Parameter_Empty, $ParameterName);
}
//检验指定Http参数必须存在且为数值，并赋值为同名全局变量
function MyMustNumeric($ParameterName)
{
	//注意：(float)类型是有精度限制的，转换后不能保证无损失
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName])) {SetPostFloat($ParameterName);return;}
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName])) {SetGetFloat($ParameterName);return;}
	MyException(Exception_Parameter_NotNumeric, $ParameterName);
}
//检验指定Http参数必须存在且为"true"或"false"，并赋值为同名全局变量
function MyMustBoolean($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && (strtolower($_POST[$ParameterName]) === 'true' || strtolower($_POST[$ParameterName]) === 'false')) {SetPostBoolean($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && (strtolower($_GET[$ParameterName]) === 'true' || strtolower($_GET[$ParameterName]) === 'false')) {SetGetBoolean($ParameterName);return;}
	MyException(Exception_Parameter_NotBoolean, $ParameterName);
}
//检验指定Http参数必须存在且为"0"或"1"，并赋值为同名全局变量
function MyMustTinyInt($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && ($_POST[$ParameterName] === '0' || $_POST[$ParameterName] === '1')) {SetPostTinyInt($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && ($_GET[$ParameterName] === '0' || $_GET[$ParameterName] === '1')) {SetGetTinyInt($ParameterName);return;} 
	MyException(Exception_Parameter_NotTinyInt, $ParameterName);
}
//检验指定Http参数必须存在且为整数-2147483648到2147483647，并赋值为同名全局变量
function MyMustInteger($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//注意：(int)类型是有大小限制的，-2147483648到2147483647
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName]) && (int) $_POST[$ParameterName] == (float) $_POST[$ParameterName]) {SetPostInteger($ParameterName);return;}  
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName]) && (int) $_GET[$ParameterName] == (float) $_GET[$ParameterName]) {SetGetInteger($ParameterName);return;}  
	MyException(Exception_Parameter_NotInteger, $ParameterName);
}
//检验指定Http参数必须存在且为数字0-9构成的非空字符串，并赋值为同名全局变量
function MyMustDigit($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//注意：ctype_digit检验字符串是否只由0-9构成
	//5.1.0 在 PHP 5.1.0 之前，当 text 是一个空字符串的时候，ctype_digit将返回 TRUE
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotDigit, $ParameterName);
}
//检验指定Http参数必须存在且为数字0-9构成的指定长度的非空字符串，并赋值为同名全局变量
function MyMustLongDigit($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;}  
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotLongDigit, array($ParameterName,$Length) );
}
//检验指定Http参数必须存在且为字母和数字构成的非空字符串，并赋值为同名全局变量
function MyMustAlphaNumeric($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotAlphaNumeric, $ParameterName);
}
//检验指定Http参数必须存在且为字母和数字构成的指定长度的非空字符串，并赋值为同名全局变量
function MyMustFixedAlphaNumeric($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//检验$MinLength和$MaxLength：
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedAlphaNumeric, array($ParameterName, $MinLength, $MaxLength) );
}
//检验指定Http参数必须存在且为标准时间字符串，并赋值为同名全局变量
function MyMustBeTime($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//strtotime() 函数将任何英文文本的日期时间描述解析为 Unix 时间戳
	//可以使用"now"，"last Sunday"，"+1 week 3 days 7 hours 5 seconds"，"today"，"tomorrow"等
	//strtotime() 函数执行成功则返回时间戳，否则返回 FALSE，在 PHP 5.1.0 之前本函数在失败时返回 -1
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1) {SetPostDate($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1) {SetGetDate($ParameterName);return;} 
	MyException(Exception_Parameter_NotTime, $ParameterName );
}
//检验指定Http参数必须存在且为标准时间字符串，并赋值为同名全局变量
function MyMustBeFixedTime($ParameterName, $MinTime, $MaxTime)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//检验$MinTime和$MaxTime：
	TimeCheck(__FUNCTION__ , '$MinTime' , $MinTime );
	TimeCheck(__FUNCTION__ , '$MaxTime' , $MaxTime );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1 && strtotime($_POST[$ParameterName]) >= strtotime($MinTime) && strtotime($_POST[$ParameterName]) <= strtotime($MaxTime)) {SetPostDate($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1 && strtotime($_GET[$ParameterName]) >= strtotime($MinTime) && strtotime($_GET[$ParameterName]) <= strtotime($MaxTime)) {SetGetDate($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedAlphaNumeric, array($ParameterName, $MinLength, $MaxLength) );
}
//检验指定Http参数必须存在且为数字0-9构成的非空字符串的JSON数组，并赋值为同名全局变量
function MyMustDigitJsonArray($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//参数格式必须为[]或者[0,1,2,3]或者["0","1","2","3"]或[0,1.0,2.00,3.000]
	//var_dump(json_decode("[]",true));//array(0) {}
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && json_decode($_POST[$ParameterName],true) !== null && json_decode($_POST[$ParameterName],true) !==false) {
		//设置了POSTHttp参数
		$temp = json_decode($_POST[$ParameterName],true);
		for($i = 0;$i<count($temp);$i++){
			if($temp[$i] === '' || !ctype_digit((string)$temp[$i])){
				MyException(Exception_Parameter_NotDigitJsonArray, $ParameterName );						
			}
		}
		Set($ParameterName,$temp);return;
	} 
	if (isset($_GET[$ParameterName]) && json_decode($_GET[$ParameterName],true) !== null && json_decode($_GET[$ParameterName],true) !==false) {
		//设置了GETHttp参数
		$temp = json_decode($_GET[$ParameterName],true);
		for($i = 0;$i<count($temp);$i++){
			if($temp[$i] === '' || !ctype_digit((string)$temp[$i])){
				MyException(Exception_Parameter_NotDigitJsonArray, $ParameterName );						
			}
		}
		Set($ParameterName,$temp);return;
	}
	MyException(Exception_Parameter_NotDigitJsonArray, $ParameterName );
}
//检验指定Http参数必须存在且为指定字节个数的字符串，并赋值为同名全局变量
function MyMustLong($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：strlen，1个中文会相当于3个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotLong, array($ParameterName, $Length) );
}
//检验指定Http参数必须存在且为指定长度的中英文字符字符串，并赋值为同名全局变量
function MyMustLongCharacter($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotLongCharacter, array($ParameterName, $Length) );
}
//检验指定Http参数必须存在且为指定长度的纯英文字符字符串，并赋值为同名全局变量
function MyMustLongEnglish($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) == $Length && strlen($_POST[$ParameterName]) === mb_strlen($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) == $Length && strlen($_GET[$ParameterName]) === mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotLongEnglish, array($ParameterName, $Length) );
}
//检验指定Http参数必须存在且为指定长度的纯中文字符字符串，并赋值为同名全局变量
function MyMustLongChinese($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) == $Length && (float)strlen($_POST[$ParameterName])/3 === (float)mb_strlen($_POST[$ParameterName]) ) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) == $Length && (float)strlen($_GET[$ParameterName])/3 === (float)mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotLongChinese, array($ParameterName, $Length) );
}
//检验指定Http参数必须存在且为指定字节个数的字符串，并赋值为同名全局变量
function MyMustFixedLong($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：strlen，1个中文会相当于3个英文
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedLong, array($ParameterName, $MinLength, $MaxLength) );
}
//检验指定Http参数必须存在且为指定长度的中英文字符的字符串，并赋值为同名全局变量
function MyMustFixedLongCharacter($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedLongCharacter, array($ParameterName, $MinLength, $MaxLength) );
}
//检验指定Http参数必须存在且为指定长度的纯英文字符的字符串，并赋值为同名全局变量
function MyMustFixedLongEnglish($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength && strlen($_POST[$ParameterName]) === mb_strlen($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength && strlen($_GET[$ParameterName]) === mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedLongEnglish, array($ParameterName, $MinLength, $MaxLength) );
}
//检验指定Http参数必须存在且为指定长度的纯中文字符的字符串，并赋值为同名全局变量
function MyMustFixedLongChinese($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	//提示：mb_strlen，1个中文相当于1个英文
	//检验$Length：
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	ExistCheck($ParameterName);
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength && (float)strlen($_POST[$ParameterName])/3 === (float)mb_strlen($_POST[$ParameterName]) ) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength && (float)strlen($_GET[$ParameterName])/3 === (float)mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_Parameter_NotFixedLongChinese, array($ParameterName, $MinLength, $MaxLength) );
}

//检验可选Http参数是否存在，并赋值为同名全局变量（默认赋值为""）
function MyCanExist($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName])) {SetPost($ParameterName);return;}
	if (isset($_GET[$ParameterName])) {SetGet($ParameterName);return;}
}
//检验可选Http参数是否存在且非零非空，并赋值为同名全局变量（默认赋值为""）
function MyCanNotEmpty($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (!empty($_POST[$ParameterName])) {SetPost($ParameterName);return;}
	if (!empty($_GET[$ParameterName])) {SetGet($ParameterName);return;}
	MyException(Exception_OptionalParameter_Empty, $ParameterName);
}
//检验可选Http参数是否存在且为数值，并赋值为同名全局变量（默认赋值为(float)0）
function MyCanNumeric($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultFloat($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName])) {SetPostFloat($ParameterName);return;}
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName])) {SetGetFloat($ParameterName);return;}
	MyException(Exception_OptionalParameter_NotNumeric, $ParameterName);
}
//检验可选Http参数是否存在且为"true"或"false"，并赋值为同名全局变量（默认赋值为false）
function MyCanBoolean($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultBoolean($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && (strtolower($_POST[$ParameterName]) === 'true' || strtolower($_POST[$ParameterName]) === 'false')) {SetPostBoolean($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && (strtolower($_GET[$ParameterName]) === 'true' || strtolower($_GET[$ParameterName]) === 'false')) {SetGetBoolean($ParameterName);return;}
	MyException(Exception_OptionalParameter_NotBoolean, $ParameterName);
}
//检验可选Http参数是否存在且为"0"或"1"，并赋值为同名全局变量（默认赋值为0）
function MyCanTinyInt($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultInteger($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && ($_POST[$ParameterName] === '0' || $_POST[$ParameterName] === '1')) {SetPostTinyInt($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && ($_GET[$ParameterName] === '0' || $_GET[$ParameterName] === '1')) {SetGetTinyInt($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotTinyInt, $ParameterName);
}
//检验可选Http参数是否存在且为整数-2147483648到2147483647，并赋值为同名全局变量并赋值同名变量（默认赋值为0）
function MyCanInteger($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultInteger($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && is_numeric($_POST[$ParameterName]) && (int) $_POST[$ParameterName] == (float) $_POST[$ParameterName]) {SetPostInteger($ParameterName);return;}  
	if (isset($_GET[$ParameterName]) && is_numeric($_GET[$ParameterName]) && (int) $_GET[$ParameterName] == (float) $_GET[$ParameterName]) {SetGetInteger($ParameterName);return;}  
	MyException(Exception_OptionalParameter_NotInteger, $ParameterName);
}
//检验可选Http参数是否存在且为数字0-9构成的非空字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanDigit($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotDigit, $ParameterName);
}
//检验可选Http参数是否存在且为数字0-9构成的指定长度的非空字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanLongDigit($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_digit($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;}  
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_digit($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotLongDigit, array($ParameterName,$Length) );
}
//检验可选Http参数是否存在且为字母和数字构成的非空字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanAlphaNumeric($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotAlphaNumeric, $ParameterName);
}
//检验可选Http参数是否存在且为字母和数字构成的指定长度的非空字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanFixedAlphaNumeric($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && ctype_alnum($_POST[$ParameterName]) && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== '' && ctype_alnum($_GET[$ParameterName]) && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedAlphaNumeric, array($ParameterName, $MinLength, $MaxLength) );
}
//检验可选Http参数是否存在且为标准时间字符串，并赋值为同名全局变量（默认赋值为"1970-01-01 08:00:00"）
function MyCanBeTime($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultDate($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1) {SetPostDate($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1) {SetGetDate($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotTime, $ParameterName );
}
//检验可选Http参数是否存在且为标准时间字符串，并赋值为同名全局变量（默认赋值为"1970-01-01 08:00:00"）
function MyCanBeFixedTime($ParameterName, $MinTime, $MaxTime)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	TimeCheck(__FUNCTION__ , '$MinTime' , $MinTime );
	TimeCheck(__FUNCTION__ , '$MaxTime' , $MaxTime );
	if(CheckSetDefaultDate($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && strtotime($_POST[$ParameterName]) !== false && strtotime($_POST[$ParameterName]) !== -1 && strtotime($_POST[$ParameterName]) >= strtotime($MinTime) && strtotime($_POST[$ParameterName]) <= strtotime($MaxTime)) {SetPostDate($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && strtotime($_GET[$ParameterName]) !== false && strtotime($_GET[$ParameterName]) !== -1 && strtotime($_GET[$ParameterName]) >= strtotime($MinTime) && strtotime($_GET[$ParameterName]) <= strtotime($MaxTime)) {SetGetDate($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedAlphaNumeric, array($ParameterName, $MinLength, $MaxLength) );
}
//检验可选Http参数是否存在且为数字0-9构成的非空字符串的JSON数组，并赋值为同名全局变量（默认赋值为array()）
function MyCanDigitJsonArray($ParameterName)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	if(CheckSetDefaultArray($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && json_decode($_POST[$ParameterName],true) !== null && json_decode($_POST[$ParameterName],true) !==false) {
		//设置了POSTHttp参数
		$temp = json_decode($_POST[$ParameterName],true);
		for($i = 0;$i<count($temp);$i++){
			if($temp[$i] === '' || !ctype_digit((string)$temp[$i])){
				MyException(Exception_OptionalParameter_NotDigitJsonArray, $ParameterName );						
			}
		}
		Set($ParameterName,$temp);return;
	} 
	if (isset($_GET[$ParameterName]) && json_decode($_GET[$ParameterName],true) !== null && json_decode($_GET[$ParameterName],true) !==false) {
		//设置了GETHttp参数
		$temp = json_decode($_GET[$ParameterName],true);
		for($i = 0;$i<count($temp);$i++){
			if($temp[$i] === '' || !ctype_digit((string)$temp[$i])){
				MyException(Exception_OptionalParameter_NotDigitJsonArray, $ParameterName );						
			}
		}
		Set($ParameterName,$temp);return;
	}
	MyException(Exception_OptionalParameter_NotDigitJsonArray, $ParameterName );
}
//检验可选Http参数是否存在且为指定字节个数的字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanLong($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotLong, array($ParameterName, $Length) );
}
//检验可选Http参数是否存在且为指定长度的中英文字符字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanLongCharacter($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) == $Length) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) == $Length) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotLongCharacter, array($ParameterName, $Length) );
}
//检验可选Http参数是否存在且为指定长度的纯英文字符字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanLongEnglish($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) == $Length && strlen($_POST[$ParameterName]) === mb_strlen($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) == $Length && strlen($_GET[$ParameterName]) === mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotLongEnglish, array($ParameterName, $Length) );
}
//检验可选Http参数是否存在且为指定长度的纯中文字符字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanLongChinese($ParameterName, $Length)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$Length' , $Length );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) == $Length && (float)strlen($_POST[$ParameterName])/3 === (float)mb_strlen($_POST[$ParameterName]) ) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) == $Length && (float)strlen($_GET[$ParameterName])/3 === (float)mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotLongChinese, array($ParameterName, $Length) );
}
//检验可选Http参数是否存在且为指定字节个数的字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanFixedLong($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedLong, array($ParameterName, $MinLength, $MaxLength) );
}
//检验可选Http参数是否存在且为指定长度的中英文字符的字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanFixedLongCharacter($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedLongCharacter, array($ParameterName, $MinLength, $MaxLength) );
}
//检验可选Http参数是否存在且为指定长度的纯英文字符的字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanFixedLongEnglish($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && strlen($_POST[$ParameterName]) >= $MinLength && strlen($_POST[$ParameterName]) <= $MaxLength && strlen($_POST[$ParameterName]) === mb_strlen($_POST[$ParameterName])) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && strlen($_GET[$ParameterName]) >= $MinLength && strlen($_GET[$ParameterName]) <= $MaxLength && strlen($_GET[$ParameterName]) === mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedLongEnglish, array($ParameterName, $MinLength, $MaxLength) );
}
//检验可选Http参数是否存在且为指定长度的纯中文字符的字符串，并赋值为同名全局变量（默认赋值为""）
function MyCanFixedLongChinese($ParameterName, $MinLength, $MaxLength)
{
	StringCheck(__FUNCTION__ , '$ParameterName' , $ParameterName );
	LengthCheck(__FUNCTION__ , '$MinLength' , $MinLength );
	LengthCheck(__FUNCTION__ , '$MaxLength' , $MaxLength );
	if(CheckSetDefault($ParameterName)===true){return;}
	if (isset($_POST[$ParameterName]) && $_POST[$ParameterName] !== '' && mb_strlen($_POST[$ParameterName]) >= $MinLength && mb_strlen($_POST[$ParameterName]) <= $MaxLength && (float)strlen($_POST[$ParameterName])/3 === (float)mb_strlen($_POST[$ParameterName]) ) {SetPost($ParameterName);return;} 
	if (isset($_GET[$ParameterName]) && $_GET[$ParameterName] !== ''  && mb_strlen($_GET[$ParameterName]) >= $MinLength && mb_strlen($_GET[$ParameterName]) <= $MaxLength && (float)strlen($_GET[$ParameterName])/3 === (float)mb_strlen($_GET[$ParameterName])) {SetGet($ParameterName);return;} 
	MyException(Exception_OptionalParameter_NotFixedLongChinese, array($ParameterName, $MinLength, $MaxLength) );
}

//用于URL的改进Base64编码
function MyUrlBase64_encode($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}
//用于URL的改进Base64解码
function MyUrlBase64_decode($data) {
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}
//对数组进行URL编码（递归调用，用于MyJson_decode函数调用）
function ArrayUrl_encode($data) {
	if(is_array($data)){
		foreach($data as $key=>$value) {
			$data[urlencode($key)] = ArrayUrl_encode($value);
		}
	}
	else {
		$data = urlencode($data);
	}
	return $data;
}
//保留中文的JSON转换字符串函数
function MyJson_decode($data) { 
	//var_dump(ArrayUrl_encode($data));
	//var_dump(json_encode(ArrayUrl_encode($data)));
	//var_dump(urldecode(json_encode(ArrayUrl_encode($data))));
	return urldecode(json_encode(ArrayUrl_encode($data)));
	//需要PHP版本5.4以上：
	//return json_encode($data,JSON_UNESCAPED_UNICODE);
}

//未完待续

?>