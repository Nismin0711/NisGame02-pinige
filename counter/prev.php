<?php
header('Content-Type: text/html; charset=UTF-8');

$password = '2022';

$cntdatas = array();	if(!$password || (isset($_POST["pass"]) && $_POST["pass"] == $password)){
$lockfile = './data.lock';	$lockfp = fopen($lockfile,"r");	flock($lockfp,LOCK_EX);	$cntfile = './data/cnt.csv';	$cfp = fopen($cntfile, "r");	$list = array();	$length = 4096;	while(!feof($cfp)){	$ret = fgets($cfp,$length);	$ret = chop($ret);	array_push($cntdatas,$ret);}
fclose($cfp);
if($password && isset($_POST["mode"]) && $_POST["mode"] == 'clear'){	$cookiename = 'counterphp';	$cookievalue = '';	setcookie ($cookiename,$cookievalue,time() + 86400);	$logfile = './data/access.log';	$loout = fopen($logfile,'w');	fwrite($loout, "");	fclose($loout);}
flock($lockfp,LOCK_UN);	fclose($lockfp);}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="Content-Style-Type" content="text/css">
<META name="viewport" content="width=device-width, initial-scale=1.0">
<TITLE>CounterPHP</TITLE>
<STYLE type="text/css">
<!--
.BT {	line-height:140%;	cursor:pointer;	font-size : 12px;	padding: 3px;	margin: 2px;	color     :#555555;	background-image:linear-gradient(#FAFAFA,#CCCCCC);	border : 1px solid #999999;	border-width:0px 1px 1px 0px;	border-radius:3px;}
.BT:hover {	line-height:140%;	cursor:pointer;	font-size : 12px;	padding: 3px;	margin: 2px;	color     :#FFFFFF;	background-image:linear-gradient(#999999,#CCCCCC);	border : 1px solid #999999;	border-width:1px 0px 0px 1px;}
-->
</STYLE>
</HEAD>
<BODY>
<?php
echo "<div align=\"center\">\n";	if($password && (!isset($_POST["pass"]) || $password != $_POST["pass"])){	echo " <form action=\"prev.php\" method=\"post\" style=\"display: inline\">\n";	echo " <table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"80%\" bgcolor=\"#CCCCCC\">\n";	echo "  <tr>\n";	echo "   <td align=\"center\" bgcolor=\"#EFEFEF\">パスワード</td>\n";	echo "   <td align=\"center\" bgcolor=\"#FFFFFF\">\n";	echo "   <input type=\"password\" name=\"pass\" size=\"16\" value=\"\">\n";	echo "   </td>\n";	echo "  </tr>\n";	echo "  <tr>\n";	echo "   <td align=\"center\" bgcolor=\"#FFFFFF\" colspan=\"2\">\n";	echo "   <input class=\"BT\" type=\"submit\" value=\"閲覧（送信）\">\n";	echo "   </td>\n";	echo "  </tr>\n";	echo " </table>\n";	echo " </form>\n";	}elseif(is_array($cntdatas)){	echo " <table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"80%\" bgcolor=\"#CCCCCC\">\n";	echo "  <tr bgcolor=\"#FFEECC\">\n";	echo "   <td align=\"center\">年月日</td>\n";	echo "   <td align=\"center\">Today</td>\n";	echo "   <td align=\"center\">Total</td>\n";	echo "  </tr>\n";	foreach($cntdatas as $cntdatas_){	$cntdata = explode(',',$cntdatas_);	if(isset($cntdata[0]) && $cntdata[0]){	echo "  <tr bgcolor=\"#FFFFFF\">\n";	echo "   <td align=\"center\" bgcolor=\"#EFEFEF\">",$cntdata[0],"</td>\n";	echo "   <td align=\"right\">",number_format($cntdata[1]),"</td>\n";	echo "   <td align=\"right\">",number_format($cntdata[2]),"</td>\n";	echo "  </tr>\n";}}
echo " </table>\n";	echo "<br>\n";	if(isset($_POST["pass"])){	echo " <form action=\"prev.php\" method=\"post\" style=\"display: inline\">\n";	echo " <input type=\"hidden\" name=\"pass\" value=\"",$_POST["pass"],"\">\n";	echo " <input type=\"hidden\" name=\"mode\" value=\"clear\">\n";	echo " <input class=\"BT\" type=\"submit\" value=\"クッキー等の削除\" onclick=\"return confirm('削除して良いですか？')\">\n";	echo "アクセス履歴（IP等）を削除し、この機種のクッキーを削除します。<br>\n";	echo "2重アクセスを回避し、１カウント増やせるようになります。<br>\n";	echo " </form>\n";}}
echo "</div>\n";	echo "<div align=\"center\">\n";	echo "<HR SIZE=\"2\" COLOR=\"#FFCC99\" style=\"border-style:dotted\" width=\"80%\">\n";	echo "DesignCounterPHP&copy;CopyRight&nbsp;<a href=\"https://www.chama.ne.jp\" target=\"ChamaNet\">ChamaNet</a>\n";	echo "</div>\n";	?>
</BODY>
</HTML>
