<?php
header('Content-Type: text/html; charset=UTF-8');	date_default_timezone_set('Asia/Tokyo');

$version = '1.00.00';
$cookiename = 'counterphp';	$nowtime = time();	$checktime = $nowtime - 86400;
$writemax = 90;	$tempdata = '';	$rflag = 0;	//クッキーの読み込み
if(isset($_COOKIE[$cookiename])){	$lasttime = $_COOKIE[$cookiename];	}else{	$lasttime = 0;}
$lockfile = './data.lock';	$lockfp = fopen($lockfile,"r");	flock($lockfp,LOCK_EX);
$logfile = './data/access.log';	$logdatas = array();	$fp = fopen($logfile, "r");	$list = array();	$length = 4096;	while(!feof($fp)){	$ret = fgets($fp,$length);	$ret = chop($ret);	array_push($logdatas,$ret);}
fclose($fp);	if(isset($_GET["v"])){$tempdata .= 'ChamaCounterPHP v'.$version;}
$aflist = array();	if(is_array($logdatas)){	foreach($logdatas as $logdatas_){	$logdata = explode(',',$logdatas_);	if(isset($logdata[1]) && $logdata[1] == $_SERVER["REMOTE_ADDR"]){	if(isset($logdata[0]) && $logdata[0] > $checktime && $logdata[0] > $lasttime){	$lasttime = $logdata[0];}
}elseif(isset($logdata[0]) && $logdata[0] > $checktime){	array_push($aflist,$logdatas_);}}}
if($lasttime < $checktime){	$rflag = 1;	$cookievalue = $nowtime;	setcookie ($cookiename,$cookievalue,time() + 86400);	$afdata = $nowtime.','.$_SERVER["REMOTE_ADDR"]."\n";	$loout = fopen($logfile,'w');	fwrite($loout, $afdata);	foreach($aflist as $aflist_){	fwrite($loout, $aflist_);	fwrite($loout, "\n");}
fclose($loout);}
$cntfile = './data/cnt.csv';	$cntdatas = array();	$cfp = fopen($cntfile, "r");	$list = array();	$length = 4096;	while(!feof($cfp)){	$ret = fgets($cfp,$length);	$ret = chop($ret);	array_push($cntdatas,$ret);}
fclose($cfp);
if($rflag == 1){	$lastcntdata = explode(',',$cntdatas[0]);	$today = date("Y",$nowtime)."年".date("m月d日",$nowtime);	if($lastcntdata[0] == $today){	$todaycnt = $lastcntdata[1] + 1;	$totalcnt = $lastcntdata[2] + 1;	$newdata = '';	$cntdatas[0] = $today.','.$todaycnt.','.$totalcnt;	}elseif(!isset($lastcntdata[1])){	$todaycnt = 1;	$totalcnt = 1;	$newdata = $today.','.$todaycnt.','.$totalcnt;	}else{	$todaycnt = 1;	$totalcnt = $lastcntdata[2] + 1;	$newdata = $today.','.$todaycnt.','.$totalcnt;}
$cntout = fopen($cntfile,'w');	if($newdata){	fwrite($cntout, $newdata);	fwrite($cntout, "\n");}
if(is_array($cntdatas)){	$rcnt = 0;	foreach($cntdatas as $cntdatas_){	if($cntdatas_ && $rcnt < $writemax){	fwrite($cntout, $cntdatas_);	fwrite($cntout, "\n");	$rcnt++;}}}
fclose($cntout);	}else{	$lastcntdata = explode(',',$cntdatas[0]);	if(isset($lastcntdata[1])){	$todaycnt = $lastcntdata[1];	$totalcnt = $lastcntdata[2];	}else{	$todaycnt = 1;	$totalcnt = 1;}}
$tempfile = './data/template.dat';	$tempdatas = file($tempfile);	$tempdata .= join("",$tempdatas);	$tempdata = str_replace('%TODAY%',number_format($todaycnt),$tempdata);	$tempdata = str_replace('%TOTAL%',number_format($totalcnt),$tempdata);	//ロック解除
flock($lockfp,LOCK_UN);	fclose($lockfp);	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Style-Type" content="text/css">
<META name="viewport" content="width=device-width, initial-scale=1.0">
<LINK type="text/css" rel="stylesheet" href="counter.css">
<TITLE>CounterPHP</TITLE>
</HEAD>
<BODY>
<?php
echo $tempdata;	?>
</BODY>
</HTML>
