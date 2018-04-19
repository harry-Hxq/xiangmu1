<?php include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
select_query("fn_welcome", '*', array("roomid" => $_SESSION['roomid']));
while($con = db_fetch_array()){
    $welcome .= "\"{$con['content']}\",";
}
$welcome = substr($welcome, 0, strlen($welcome) - 1);
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
	function getUserInfo(){
		$.ajax({
			url:'/Application/ajax_getuserinfo.php',
			type: 'get',
			cache:false,
			dataType:'json',
			success:function(data){
				if(data.success){
					$('#userinfo1').html('剩余点数：'+data.price+'点');
					$('#userinfo2').html('线上人数：'+data.online+'人');
				}else{
					alert('登录过期,请重新登录！');
					window.top.href="http://<?php echo $_SERVER['HTTP_HOST'];
?>/qr.php?room=<?php echo $_SESSION['roomid'];
?>"
				}
			},
			error:function(){}
		});
	}
	setInterval(getUserInfo,5000);
	$(document).ready(function(){
		getUserInfo();
	});
	var headimg = "<?php echo $_SESSION['headimg'];
?>";
	var nickname = "<?php echo $_SESSION['username'];
?>";
	var welcome = new Array(<?php echo $welcome;
?>);
	var welHeadimg = "<?php echo get_query_val("fn_setting", "setting_sysimg", array("roomid" => $_SESSION['roomid']));
?>";
</script>
<script src="/Style/Old/js/chat.js"></script>
</head>
<body>
<table width="100%">
	<tbody>
		<tr>
			<td Style="width:580px;">			
				<input type="text" id="msg" maxlength="50" placeholder="赛道/类型/金额" Style="width: 300px; height: 70px; border: 2px solid #888; font-size: 32px; margin: 10px 10px;">
				<img src="/Style/images/kb.png" class="keybord">
				<button id="butSend" class="sendbutton" >发 送</button>
				<div class="keybord_div" id="keybord_div"><em>1</em><em>2</em><em>3</em><em>4</em><em>5</em><em>大</em><em>小</em><em>单</em><em>双</em><em>6</em><em>7</em><em>8</em><em>9</em><em>0</em><em>/</em><em>龙</em><em>虎</em><em>和</em><em>-</em><em>.</em><em>查</em><em>回</em><em class="c2">发送</em><em class="c">清</em><em class="c">←</em><em class="close">×</em></div>
			</td>
			<td Style="float: right;padding-right: 0px;">
				<span id="userinfo1" class="userinfo1">剩余点数：0点</span>
				<br>
				<span id="userinfo2" class="userinfo2">线上人数：0人</span>
			</td>
		</tr>
	</tbody>
</table>
<div id="messageWindow" class="messageWindow">
	<div id="messageLoading">Loading...</div>
</div>
</body>
<script>
	$(".keybord").on('touchstart',function(){
			$(this).toggleClass("gray");
			$(".keybord_div").toggle();
			$("#msg").attr("readonly",!$(this).hasClass('gray'));
	});
	$(".keybord_div em").on('touchstart',function(){
		$(this).addClass("on").siblings().removeClass('on');
		var val = $("#msg").val();
		var vkey = $(this).html();
		if(vkey == "清"){
			return $("#msg").val('');
		}
		if(vkey=="←" ||vkey=="删"){
			return $("#msg").val(val.substr(0,val.length-1));
		}
		if(vkey=="发"||vkey=="发送"){
			$('#butSend').click();
		}
		if(vkey=="×"){
			$('.keybord').addClass("gray");
			$(".keybord_div").hide();
			$("#msg").attr("readonly",false);
			return;
		}
		$("#msg").val(val+vkey);
	});
	$(".keybord_div em").on('touchend',function(){
		$(this).removeClass("on");
	})
</script>
</html>