<?php include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Pragma" content="no-cache">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $sitename;
?></title>
<link rel="Stylesheet" type="text/css" href="/Style/Old/css/style.css">
<link rel="Stylesheet" type="text/css" href="/Style/Old/css/style2.css">
<script src="/Style/Old/js/jquery.min.js"></script>
<script type = "text/javascript">
	var headimg = "<?php echo $_SESSION['headimg'];
?>";
	var nickname = "<?php echo $_SESSION['username']?>";
</script>
<script src="/Style/Old/js/kefu.js"></script>
<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	background: #000 url(/Style/Xs/Public/images/bg.png);
}
</style>
</head>
<body>
<table width="100%">
	<tbody>
		<tr>
			<td style="wdith:100%">
				<input type="text" id="msg" maxlength="50" style="width: 700px; height: 70px; border: 2px solid #888; font-size: 32px; margin: 10px 10px;">
				<button id="butSend" style="background: #a0e759; width: 120px; height: 70px; font-size: 25px; margin: 0 10px;border:0px;">发 送</button>
			</td>
		</tr>
	</tbody>
</table>
<?php $kefu = get_query_val('fn_setting', 'setting_kefu', array("roomid" => $_SESSION['roomid']));
if($kefu != ""){
    ?>
<div align="center">
	<div class="bubble bubbleS4" style="font-size:30px;"><font style="font-size:35px;">公告</font><br><?php echo $kefu;
    ?></div>
</div>
<?php }
?>
<div id="messageWindow" class="messageWindow">
	<div id="messageLoading">Loading...</div>
	<!--table class="msgItem msgUsr">
		<tbody>
			<tr>
				<td class="msgItem1">
					<img src="http://wx.qlogo.cn/mmopen/tf6MhYwQwkIQslWUkgxbYD4q0vQJ8XnRqPNicXWDwRT8FYuXzwzXg7Zd32RibzZ7kkIQZTAibRZ6jw8PRRia3ZQWS8zefJegu44Q/0">
				</td>
				<td class="msgItem2">
					<span class="msgName">爱来尔</span>
					<span class="msgTime">01:22:17</span>
					<br>
					<div class="bubble bubbleL bubbleU3">小50</div>
				</td>
			</tr>
		</tbody>
	</table>	
	<table class="msgItem msgUsr">
		<tbody>
			<tr>
				<td class="msgItem1">
					<img src="http://wx.qlogo.cn/mmopen/tf6MhYwQwkIQslWUkgxbYD4q0vQJ8XnRqPNicXWDwRT8FYuXzwzXg7Zd32RibzZ7kkIQZTAibRZ6jw8PRRia3ZQWS8zefJegu44Q/0">
				</td>
				<td class="msgItem2">
					<span class="msgName">爱来尔</span>
					<span class="msgTime">01:22:17</span>
					<br>
					<div class="bubble bubbleL bubbleU1">小50</div>
				</td>
			</tr>
		</tbody>
	</table>	
	<table class="msgItem msgUsr">
		<tbody>
			<tr>
				<td class="msgItem1">
					<img src="http://wx.qlogo.cn/mmopen/tf6MhYwQwkIQslWUkgxbYD4q0vQJ8XnRqPNicXWDwRT8FYuXzwzXg7Zd32RibzZ7kkIQZTAibRZ6jw8PRRia3ZQWS8zefJegu44Q/0">
				</td>
				<td class="msgItem2">
					<span class="msgName">爱来尔</span>
					<span class="msgTime">01:22:17</span>
					<br>
					<div class="bubble bubbleL bubbleU2">小50</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="msgItem msgSys">
		<tbody>
			<tr>
				<td class="msgItem2">
					<span class="msgTime">01:18:46</span>
					<span class="msgName">管理员</span>
					<br>
					<div class="bubble bubbleR bubbleS3">期号:20161125147关闭，请耐心等待开奖</div>
				</td>
				<td class="msgItem1">
					<img src="../images/avatar-AdmIssu.jpg">
				</td>
			</tr>
		</tbody>
	</table-->
</div>
</body>
</html>