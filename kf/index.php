<?php
include_once("../Public/config.php");

if($_GET['aa']!='aaa555'){
	echo "ERROR ENTRY."; exit;

}

//$lastroomid = get_query_val('fn_room', '*', 'roomid>0 order by roomid desc limit 1');
$lastroomid = db_query("select `roomid` from `fn_room` where `roomid`>0 order by `roomid` desc limit 1");
$lastroomid = db_fetch_array();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8" />
	<title>代理开房间</title>
</head>
<body>
	<form action="index.do.php" method="POST">
	<p>账号:</p>
	<p><input type="text" name="username" value="ht" /></p>
	<p>密码:</p>
	<p><input type="text" name="password" value="<?php echo rand(44444444,99999999);?>" /></p>
	<p>房间号:</p>
	<p><input type="text" name="roomid" value="" /></p>
	<p>到期日期:</p>
	<p><input type="text" name="enddate" value="<?php echo date('Y-m-d', strtotime('+1 month')); ?> 23:59:59" /></p>
	<button type="submit">提交</button>
	</form>
	<p>最后的房间号 roomid：<?php echo $lastroomid[0]; ?></p>
</body>
</html>
