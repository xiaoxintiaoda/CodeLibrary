<?php
require('sendMail.php');
ini_set('memory_limit','1024M');
$ftp_server = '';        //--填上自己的ftp ip 
$ftp_user = '';          //--填上自己的ftp用户名
$ftp_pwd = '';           //--填上自己的ftp密码
$title = 'lyxweb日志';
$recipient = 'laiyouxi-seo@laiyouxi.com';
$content = '今天份的日志，请收下....';

$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
echo "----------------start connect---------------\n";
$is_connect = ftp_login($conn_id,$ftp_user,$ftp_pwd);
echo "----------------start login---------------\n";
if($is_connect){
	echo "----------------connect success---------------\r";
	$local_file = 'G:\\'.date('Ymd',time() - 86400).'_lyxweblog.log';
    if(!file_exists($local_file)){
		ftp_pasv($conn_id,true);
	    $date = date('Y/m/d',time() - 86400);
	    $remote_file = '/home/wwwlogs/bak/'.$date.'/lyxweb.log';
		$result = ftp_get($conn_id,$local_file,$remote_file,FTP_BINARY);
		if(!$result){
		    ftp_close($conn_id);
			die('download fail');
		}
        echo "----------------ftp download success---------------\n";		
		ftp_close($conn_id);
	}
	$zip = new ZipArchive();
    //需要打开的zip文件,文件不存在将会自动创建
    $filename = 'G:\\'.date('Ymd',time() - 86400).'.zip';

    if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
       //如果是Linux系统，需要保证服务器开放了文件写权限
        exit("文件打开失败!");
     }
    
    //将test.php文件添加到压缩文件中
    $zip->addFile($local_file);
    //关闭文件
    $zip->close();
	echo "----------------add zip file success---------------\n";		
	$msg = sendmail($recipient,$title,$content,$filename);
	if($msg === 0){
		die('mail send success');
	}else{
		die('mail send fail '.$msg);
	}
}else{
	die("Couldn't connect as $ftp_user\n");
}

function sendmail($recipient,$title,$content,$file){
	$mail = new Mail();
	$msg = $mail->sendMail($recipient,$title,$content,$file);
	return $msg;
}