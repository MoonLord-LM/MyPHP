<?php
//SAE的Mail操作
define('Sae_Mail_From_Address' , '156984619@qq.com');//发送邮件的邮箱的账号
define('Sae_Mail_From_Password' , 'TX1312357@LOVE');//发送邮件的邮箱的密码

//自定义的Mail发送文本函数（成功返回true，失败【或不允许重复发送】返回false）
function SaeMailSendText($ToAddress, $Title = '', $TextContent = '', $AttachFile = array() ){
	//由于采用邮件队列发送，本函数返回成功时，只意味着邮件成功送到发送队列，并不等效于邮件已经成功发送（有延时）
	$mail = new SaeMail();
	if(is_array($AttachFile) && $AttachFile !==array()){
		$mail->setAttach($AttachFile);
	}
	$mail->setOpt( array("content_type"=>"TEXT") );
	$result = $mail->quickSend($ToAddress , $Title , $TextContent , Sae_Mail_From_Address , Sae_Mail_From_Password);
	//发送失败则：
	//$result === false;
	//$mail->errno()===1009
	//$mail->errmsg()==='You have sended a same message to the same recipient 178910432@qq.com in ';
	//发送成功则：
	//$result === true;
	//$mail->errno()===0
	//$mail->errmsg()==='OK';
	return $result;
	//示例代码：
	//var_dump(SaeMailSendText('178910432@qq.com','标题','<A href="http://www.moonlord.cn">正文</A>',array(  '附件.txt' => '这里是附件的二进制数据' , '附件.png' =>file_get_contents("https://www.baidu.com/img/bdlogo.png") )));
}
//自定义的Mail发送HTML文本函数（成功返回true，失败【或不允许重复发送】返回false）
function SaeMailSendHtml($ToAddress, $Title = '', $HtmlContent = '', $AttachFile = array() ){
	$mail = new SaeMail();
	if(is_array($AttachFile) && $AttachFile !==array()){
		$mail->setAttach($AttachFile);
	}
	$mail->setOpt( array("content_type"=>"HTML") );
	$result = $mail->quickSend($ToAddress , $Title , $HtmlContent , Sae_Mail_From_Address , Sae_Mail_From_Password);
	return $result;
	//示例代码：
	//var_dump(SaeMailSendHtml('178910432@qq.com','标题','<A href="http://www.moonlord.cn">正文</A>',array(  '附件.txt' => '这里是附件的二进制数据' , '附件.png' =>file_get_contents("https://www.baidu.com/img/bdlogo.png") )));
}

?>