<?php
 switch($_COOKIE['game']){
case 'pk10': $lot = 'fn_lottery1';
    break;
case "xyft": $lot = 'fn_lottery2';
    break;
case "jsmt": $lot = 'fn_lottery6';
    break;
case "jssc": $lot = 'fn_lottery7';
    break;
}
$info = get_query_vals($lot, '*', array('roomid' => $_SESSION['roomid']));

//
$info1 = get_query_vals('fn_lottery1', '*', array('roomid' => $_SESSION['roomid']));
$info2 = get_query_vals('fn_lottery2', '*', array('roomid' => $_SESSION['roomid']));
$info3 = get_query_vals('fn_lottery3', '*', array('roomid' => $_SESSION['roomid']));
$info4 = get_query_vals('fn_lottery4', '*', array('roomid' => $_SESSION['roomid']));
$info5 = get_query_vals('fn_lottery5', '*', array('roomid' => $_SESSION['roomid']));
$info6 = get_query_vals('fn_lottery6', '*', array('roomid' => $_SESSION['roomid']));
$info7 = get_query_vals('fn_lottery7', '*', array('roomid' => $_SESSION['roomid']));
$info8 = get_query_vals('fn_lottery8', '*', array('roomid' => $_SESSION['roomid']));
$info9 = get_query_vals('fn_lottery9', '*', array('roomid' => $_SESSION['roomid']));

$pk10open = $info1['gameopen'];
$xyftopen = $info2['gameopen'];
$cqsscopen = $info3['gameopen'];
$xy28open = $info4['gameopen'];
$jnd28open = $info5['gameopen'];
$jsmtopen = $info6['gameopen'];
$jsscopen = $info7['gameopen'];
$jssscopen = $info8['gameopen'];
$kuai3open = $info9['gameopen'];

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no"-->
<meta name="viewport" content="user-scalable=no">
<title><?php echo $sitename ?></title>
<link rel="Stylesheet" type="text/css" href="Style/Old/css/weui.min.css" />
<link rel="Stylesheet" type="text/css" href="Style/Old/css/style.css" />
<link rel="Stylesheet" type="text/css" href="Style/Old/css/bootstrap.min.css" />
<link rel="Stylesheet" type="text/css" href="Style/Xs/Public/css/wx.css" />
<link rel="Stylesheet" type="text/css" href="Style/Xs/Public/css/layout.css" />
<link rel="Stylesheet" type="text/css" href="Style/Xs/static/css/iconfont.css" />
<script src="Style/Old/js/jquery.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="Style/Old/js/bootstrap.min.js"></script>
</head>
<body>
<script type="text/javascript">
var info = {
	'nickname': "<?php echo $_SESSION['username'] ?>", 
	'headimg':"<?php echo $_SESSION['headimg'] ?>", 
	'userid':"<?php echo $_SESSION['userid'] ?>", 
	'roomid':"<?php echo $_SESSION['roomid'] ?>", 
	'game': "<?php echo $_COOKIE['game'];
?>"
	};
var welcome = new Array(<?php echo $welcome;
?>);
var welHeadimg = "<?php echo get_query_val("fn_setting", "setting_sysimg", array("roomid" => $_SESSION['roomid']));
?>";

var sharetitle = '[<?php echo $_SESSION['username']?>]邀请您光临<?php echo $sitename;
?>:公平、公正的娱乐房间!';
var shareurl = '<?php echo $oauth . '&room=' . $room;
?>';
var shareImg = '<?php echo $_SESSION['headimg'];
?>';
var sharedesc="我正在澎湃娱乐系统提供的游戏房间玩耍！赶紧加入吧！[长按收藏]永不丢失加入口！";
var para = {};
para.url = decodeURIComponent(location.href.split('#')[0]);
$.ajax({
	url: 'Public/initJs.php',
	type: 'post',
	data: para,
	dataType: 'json',
	success: function(data){
		wx.config({
			debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			appId: data.appId, // 必填，公众号的唯一标识
			timestamp: data.timestamp, // 必填，生成签名的时间戳
			nonceStr: data.noncestr, // 必填，生成签名的随机串
			signature: data.signature,// 必填，签名，见附录1
			jsApiList : [ "onMenuShareTimeline","onMenuShareAppMessage", "onMenuShareQQ","onMenuShareWeibo", "chooseImage","previewImage", "getNetworkType", "scanQRCode","chooseWXPay" ]
		});
	},
	error:function(error){ console.log(error);  }
});

wx.ready(function(){
	wx.onMenuShareTimeline({
		title: sharetitle, // 分享标题
		link: shareurl, // 分享链接
		imgUrl: shareImg, // 分享图标
		success: function () { 
			
		},
		cancel: function () { 
		
		}
	});
	wx.onMenuShareAppMessage({
		title: sharetitle, // 分享标题
		desc: sharedesc, // 分享描述
		link: shareurl, // 分享链接
		imgUrl: shareImg, // 分享图标
		type: '', // 分享类型,music、video或link，不填默认为link
		dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
		success: function () { 
			// 用户确认分享后执行的回调函数
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});	
});

</script>
<!-- New Templates Update -->
<script type="text/javascript" src="/Style/Old/js/tools.js"></script>
<script type="text/javascript" src="/Style/Old/js/chat.js"></script>
<script type="text/javascript" src="/Style/Old/js/pk10.js"></script>
<!-- ./New Templates Update -->

<iframe onload="iFrameHeight2();" src="/Templates/Old/shipin.php" name="ifarms" width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" id="ifarms" class="ifarms"></iframe>
<!-- 信息框 -->
<div class="modal fade" id="msgdialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="left">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <center>
		<?php $qrcode = $sql['setting_qrcode'];
if($qrcode == ""){
?>
			<strong Style="font-size:25px;color:red">财务还没设置二维码噢</strong>
		<?php }else{
?>
			<strong Style="font-size:25px;color:red">长按二维码点击识别</strong><br /><br />
			<img src="<?php echo $qrcode;
?>">
		<?php }
?>
		</center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="leftdiv">
	<ul>
		<li class="ulogo"><a href="/Templates/user/"><img src="<?php echo $_SESSION['headimg'];
?>" class="mlogo"></a></li>
		<li class="home" data-id="home"><span>菜单</span></li>
		<li class="guess" data-id="guess"><span>竞猜</span></li>
		<?php if($sql['display_custom'] != 'false'){
?><li class="skefu" data-id="skefu"><span>客服<em>0</em></span></li><?php }
?>
		<li class="logs" data-id="logs"><span>记录</span></li>
		<li class="caiwu" data-id="caiwu"><span>财务</span></li>
		<?php if($sql['display_extend'] != 'false'){
?><li class="tg" data-id="tgzq"><span>推广</span></li><?php }
?>
		<!--<li class="fresh" data-id="reload"><span>刷新</span></li>-->
		<!--li class="cz" data-id="cz"><span>充值</span></li-->
	</ul>
</div>
<div id="frameRIGHTH">
	<div class="nav_banner">
		<ul class="lottery">
		<?php if($sql['display_game'] != 'false'){
?>
			<li class="home" data-id="lottery">
				<span> <i class="iconfont"></i>频道:<?php echo formatgame($game);
?></span>
			</li>
		<?php }
?>
            <li class="dh" data-id="logs"><span>下注核对</span></li>
            <li class="dh" data-id="lsjl"><a href="/Templates/Old/addpage/kjjl.php"><span>历史记录</span></a></li>
            <li class="dh" data-id="donghua"><span>动画</span></li>
            <li class="wz" data-id="wenzi"><span>走势</span></li>
            <?php if($sql['display_plan'] != 'false'){
            ?><!--<li class="cl" data-id="changlong"><span>长龙</span></li>--><?php }
        ?>
            <!--			<li class="gz" data-id="guize"><span>规则</span></li>-->
            <!--			<li class="sx" data-id="reload2"><span>刷新动画</span></li>-->
            <!--			<li class="smallwindows" data-id="smallwindows"><span>小窗</span></li>-->
		</ul>
		<ul class="uinfo">
			<li class="uname">昵称:<?php echo $_SESSION['username'];
?></li>
			<li class="money">余点: <b class="balance">0</b></li>
			<li class="oline">在线: <b class="online">0</b>人</li>
		</ul>
	</div>
	<div class="touzu rbox">
	<div class="user_messages">
			<div>
				<input placeholder="车道/车号/金额" type="text" id="Message">
				<img src="/Style/Xs/Public/images/kb.png" class="keybord gray">
				<span class="sendemaill">发 送</span>
			</div>
			<span class="txtbet">快捷下注</span>
			<div class="keybord_div" id="keybord_div">
				<em>1</em>
				<em>2</em>
				<em>3</em>
				<em>4</em>
				<em>5</em>
				<em>6</em>
				<em>7</em>
				<em>8</em>
				<em>9</em>
				<em>0</em>
				<em>大</em>
				<em>小</em>
				<em>单</em>
				<em>双</em>
				<em>龙</em>
				<em>虎</em>
				<em>和</em>
				<em>/</em>
				<em>-</em>
				<em>.</em>
				<em>查</em>
				<em>回</em>
				<em class="c2">发送</em>
				<em class="c">清</em>
				<em class="c">←</em>
				<em class="close">×</em>
			</div>
																																																																																			</div>
		</div>
	<div class="game-box" style="display: none;">
		<div class="game-hd">
			<div class="menu">
				<ul>
					<li class="gameli"><a href="javascript:;" class="on" data-t="1">猜大小单双</a></li>
					<li class="gameli"><a href="javascript:;" data-t="2" class="">猜车号</a></li>
					<li class="gameli"><a href="javascript:;" data-t="4" class="">猜龙虎</a></li>
					<li class="more-game">
						<a href="javascript:;"><img src="/Style/images/game-arrow.png"></a>
						<div class="sub-menu" style="display: none;">
							<a href="javascript:;" data-t="3">猜组合</a>
							<a href="javascript:;" data-t="6">猜前二号码</a>
							<a href="javascript:;" data-t="7">冠亚和大小单双</a>
							<a href="javascript:;" data-t="8">猜冠亚和值</a>
						</div>
					</li>
				</ul>
				<!--<h4 id="game-gtype">猜大小单双</h4>-->
			</div>
			<div class="infuse" style="display: none;">
				<a href="javascript:;" class="clearnum">清空所选</a>
				<em id="bet_num">共<b>0</b>注</em>
				<a href="javascript:;" class="confirm-pour">确定下注</a>
			</div>
		</div>
		<div class="game-bd">
			<div class="gamenum game-type-1" style="">
				<div class="rank-tit">
					<span class="change">猜大小单双</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-line="1"><div class="h5">冠军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="2"><div class="h5">亚军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="3"><div class="h5">季军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="4"><div class="h5">第四名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="5"><div class="h5">第五名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="6"><div class="h5">第六名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="7"><div class="h5">第七名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="8"><div class="h5">第八名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="9"><div class="h5">第九名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="0"><div class="h5">第十名</div></a>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="大">
						<div class="h5">
							<h5>大</h5>
							<p>6、7、8、9、10</p>
							<p><em>× <?php echo $info['da'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="小">
						<div class="h5">
							<h5>小</h5>
							<p>1、2、3、4、5</p>
							<p><em>× <?php echo $info['xiao'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="单">
						<div class="h5">
							<h5>单</h5>
							<p>1、3、5、7、9</p>
							<p><em>× <?php echo $info['dan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="双">
						<div class="h5">
							<h5>双</h5>
							<p>2、4、6、8、10</p>
							<p><em>× <?php echo $info['shuang'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--猜大小单双-->
			<div class="gamenum game-type-2" style="display: none;">
				<div class="rank-tit">
					<span class="change">猜车号</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-line="1"><div class="h5">冠军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="2"><div class="h5">亚军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="3"><div class="h5">季军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="4"><div class="h5">第四名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="5"><div class="h5">第五名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="6"><div class="h5">第六名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="7"><div class="h5">第七名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="8"><div class="h5">第八名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="9"><div class="h5">第九名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="0"><div class="h5">第十名</div></a>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="1">
						<div class="h5">
							<h5>1号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="2">
						<div class="h5">
							<h5>2号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="3">
						<div class="h5">
							<h5>3号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="4">
						<div class="h5">
							<h5>4号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="5">
						<div class="h5">
							<h5>5号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="6">
						<div class="h5">
							<h5>6号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="7">
						<div class="h5">
							<h5>7号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="8">
						<div class="h5">
							<h5>8号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="9">
						<div class="h5">
							<h5>9号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="0">
						<div class="h5">
							<h5>10号车</h5>
							<p><em>× <?php echo $info['tema'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--猜车号-->
			<div class="gamenum game-type-3" style="display: none;">
				<div class="rank-tit">
					<span class="change">组合竞猜</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-line="1"><div class="h5">冠军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="2"><div class="h5">亚军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="3"><div class="h5">季军</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="4"><div class="h5">第四名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="5"><div class="h5">第五名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="6"><div class="h5">第六名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="7"><div class="h5">第七名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="8"><div class="h5">第八名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="9"><div class="h5">第九名</div></a>
					<a href="javascript:;" class="btn mini-btn" data-line="0"><div class="h5">第十名</div></a>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="大单">
						<div class="h5">
							<h5>大单</h5>
							<p>7、9</p>
							<p><em>× <?php echo $info['dadan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="大双">
						<div class="h5">
							<h5>大双</h5>
							<p>6、8、10</p>
							<p><em>× <?php echo $info['dashuang'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="小单">
						<div class="h5">
							<h5>小单</h5>
							<p>1、3、5</p>
							<p><em>× <?php echo $info['xiaodan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="小双">
						<div class="h5">
							<h5>小双</h5>
							<p>2、4</p>
							<p><em>× <?php echo $info['xiaoshuang'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--龙虎-->
			<div class="gamenum game-type-4" style="display: none;">
				<div class="rank-tit">
					<span class="change">猜龙虎</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="龙">
						<div class="h5">
							<h5>龙</h5>
							<p><em>× <?php echo $info['long'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="虎">
						<div class="h5">
							<h5>虎</h5>
							<p><em>× <?php echo $info['hu'];
?></em></p>
						</div>
					</a>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn large-btn" data-line="1">
						<div class="h5">
							<h5>冠军</h5>
							<p>vs</p>
							<p>第十名</p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-line="2">
						<div class="h5">
							<h5>亚军</h5>
							<p>vs</p>
							<p>第九名</p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-line="3">
						<div class="h5">
							<h5>季军</h5>
							<p>vs</p>
							<p>第八名</p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-line="4">
						<div class="h5">
							<h5>第四名</h5>
							<p>vs</p>
							<p>第七名</p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-line="5">
						<div class="h5">
							<h5>第五名</h5>
							<p>vs</p>
							<p>第六名</p>
						</div>
					</a>
				</div>
			</div>
			<!--冠亚号码组合-->
			<div class="gamenum game-type-6" style="display: none;">
				<div class="rank-tit">
					<span class="change">冠亚号码组合</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-val="1"><div class="h5">01</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="2"><div class="h5">02</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="3"><div class="h5">03</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="4"><div class="h5">04</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="5"><div class="h5">05</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="6"><div class="h5">06</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="7"><div class="h5">07</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="8"><div class="h5">08</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="9"><div class="h5">09</div></a>
					<a href="javascript:;" class="btn mini-btn" data-val="0"><div class="h5">10</div></a>
				</div>
			</div>
			<!--猜冠亚大小单双-->
			<div class="gamenum game-type-7" style="display: none;">
				<div class="rank-tit">
					<span class="change">冠亚和大小单双</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn large-btn" data-val="大">
						<div class="h5">
							<h5>大</h5>
							<p>12、13、14、15、16、17、18、19</p>
							<p><em>× <?php echo $info['heda'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-val="小">
						<div class="h5">
							<h5>小</h5>
							<p>3、4、5、6、7、8、9、10、11</p>
							<p><em>×  <?php echo $info['hexiao'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-val="单">
						<div class="h5">
							<h5>单</h5>
							<p>3、5、7、9、11、13、15、17、19</p>
							<p><em>×  <?php echo $info['hedan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-val="双">
						<div class="h5">
							<h5>双</h5>
							<p>4、6、8、10、12、14、16、18</p>
							<p><em>×  <?php echo $info['heshuang'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--冠亚猜数字-->
			<div class="gamenum game-type-8" style="display: none;">
				<div class="rank-tit">
					<span class="change">冠亚和数字</span>
				</div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-val="3">
						<div class="h5">
							<h5>03</h5>
							<p><em>× <?php echo $info['he341819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="4">
						<div class="h5">
							<h5>04</h5>
							<p><em>× <?php echo $info['he341819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="5">
						<div class="h5">
							<h5>05</h5>
							<p><em>× <?php echo $info['he561617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="6">
						<div class="h5">
							<h5>06</h5>
							<p><em>× <?php echo $info['he561617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="7">
						<div class="h5">
							<h5>07</h5>
							<p><em>× <?php echo $info['he781415'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="8">
						<div class="h5">
							<h5>08</h5>
							<p><em>× <?php echo $info['he781415'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="9">
						<div class="h5">
							<h5>09</h5>
							<p><em>× <?php echo $info['he9101213'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="10">
						<div class="h5">
							<h5>10</h5>
							<p><em>× <?php echo $info['he9101213'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="11">
						<div class="h5">
							<h5>11</h5>
							<p><em>× <?php echo $info['he11'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="12">
						<div class="h5">
							<h5>12</h5>
							<p><em>× <?php echo $info['he9101213'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="13">
						<div class="h5">
							<h5>13</h5>
							<p><em>× <?php echo $info['he9101213'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="14">
						<div class="h5">
							<h5>14</h5>
							<p><em>× <?php echo $info['he781415'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="15">
						<div class="h5">
							<h5>15</h5>
							<p><em>× <?php echo $info['he781415'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="16">
						<div class="h5">
							<h5>16</h5>
							<p><em>× <?php echo $info['he561617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="17">
						<div class="h5">
							<h5>17</h5>
							<p><em>× <?php echo $info['he561617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="18">
						<div class="h5">
							<h5>18</h5>
							<p><em>× <?php echo $info['he341819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="19">
						<div class="h5">
							<h5>19</h5>
							<p><em>× <?php echo $info['he341819'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--猜特码数字-->
		</div>
	</div>
	<div id="touzhu" class="">
		<div class="pour-info">
			<h4 class="game-tit game-tit-bg" style="font-size:45px;line-height:100px;">竞猜大小单双<a href="javascript:;" class="close">×</a></h4>
			<div class="m-bd">
				<h4>共<em class="bet_n">1</em>注，投注金额<em class="bet_total">0</em>元</h4>
				<dl>
					<dt>
						<span>下注金额：</span>
						<input type="number" class="text text-right bet_money" placeholder="下注金额">
						<a href="javascript:;" class="money_clear">清零</a>
					</dt>
					<dd>
						<i class="m5" data-money="5"></i>
						<i class="m10" data-money="10"></i>
						<i class="m50" data-money="50"></i>
						<i class="m100" data-money="100"></i>
						<i class="m500" data-money="500"></i>
						<i class="m1000" data-money="1000"></i>
						<i class="m5000" data-money="5000"></i>
					</dd>
				</dl>
				<div class="sub-btn">
					<a href="javascript:;" class="cancel">取消下注</a>
					<a href="javascript:;" class="confirm">确定下注</a>
				</div>
			</div>
		</div>
	</div>
		<div class="rightdiv">
			<!--div class="saidright">
				<img src="/Public/images/gm.jpg">
				<div class="tousaidl">
					<span class="tousaid2">13:21:50</span>&nbsp;&nbsp;
					<span class="tousaid1">系统GM</span>
				</div>
				<div class="ts"> 
					<b style="border-color:transparent  transparent transparent #FFBBBB;"></b>
					<span class="neirongsaidl" style="background-color: #FFBBBB;">北京赛车<br>期号:632246<br>已封盘，请耐心等待开奖！</span>
				</div>
			</div>
			<div class="saidright">
				<img src="/Public/images/gm.jpg">
				<div class="tousaidl">
					<span class="tousaid2">13:21:50</span>&nbsp;&nbsp;
					<span class="tousaid1">系统GM</span>
				</div>
				<div class="ts"> 
					<b style="border-color:transparent  transparent transparent #98E165;"></b>
					<span class="neirongsaidl" style="background-color:#98E165;max-width: 100%">北京赛车<br>期号:632246<br>已封盘，请耐心等待开奖！</span>
				</div>
			</div>
			<div class="saidright">
				<img src="/Public/images/gm.jpg">
				<div class="tousaidl">
					<span class="tousaid2">13:21:50</span>&nbsp;&nbsp;
					<span class="tousaid1">系统GM</span>
				</div>
				<div class="ts"> 
					<b style=""></b>
					<span class="neirongsaidl" style="">北京赛车<br>期号:632246<br>已封盘，请耐心等待开奖！</span>
				</div>
			</div>
			<div class="saidleft">
				<img src="/Public/images/gm.jpg">
				<div class="tousaid">
					<span class="tousaid2">13:21:50</span>&nbsp;&nbsp;
					<span class="tousaid1">系统GM</span>
				</div>
				<div class="tsf"> 
					<b></b>
					<span class="neirongsaid" style="">北京赛车<br>期号:632246<br>已封盘，请耐心等待开奖！</span>
				</div>
			</div-->
		</div>
	</div>
	<!--div class="kefu rbox" style="display:none">
		<div class="user_messages">
			<input type="text" id="kfs"><span id="sendkf">发 送</span>
		</div>
		<div class="kfcs">
			<div class="saidright">
				<img src="/Public/images/kefu2.jpg">
				<div class="tousaidl">
					<span class="tousaid2">16:22:17</span>&nbsp;&nbsp;<span class="tousaid1">客服</span>
				</div>
				<div class="ts"> 
					<b></b>
					<span class="neirongsaidl">有任何问题请留言，我们将尽快为您解答。</span>
				</div>
			</div>
		</div>
	</div-->
	<div id="ss_menu" style="">	
		<div class="ss_nav">
			<i class="iconfont close" data-id="#ss_menu"></i>
			<?php if($sql['display_game'] != 'false'){
?>
			<ul class="lottery">
			<li <?php if($pk10open == 'false') echo 'class="gray hideli"';
?>>
					<a <?php if($pk10open == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=pk10'";
}
?>>
						<img src="/Style/Home/images/pk10-logo.png" title="北京赛车">
						<font>北京赛车</font>
					</a>
				</li>
				<li <?php if($xyftopen == 'false') echo 'class="gray hideli"';
?>>
					<a <?php if($xyftopen == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=xyft'";
}
?>>
						<img src="/Style/Home/images/xyft-logo.png" title="幸运飞艇">
						<font>幸运飞艇</font>
					</a>
				</li>
				<li <?php if($cqsscopen == 'false') echo 'class="gray hideli"';
?>>
					<a <?php if($cqsscopen == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=cqssc'";
}
?>>
						<img src="/Style/Home/images/cqssc-logo.png" title="重庆时时彩">
						<font>重庆时时彩</font>
					</a>
				</li>
				<li <?php if($xy28open == 'false') echo 'class="gray hideli"';
?>>
					<a <?php if($xy28open == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=xy28'";
}
?>>
						<img src="/Style/Home/images/xy28-logo.png" title="幸运28">
						<font>幸运28</font>
					</a>
				</li>
				<li <?php if($jnd28open == 'false') echo 'class="gray hideli"';
?>>
					<a <?php if($jnd28open == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=jnd28'";
}
?>>
						<img src="/Style/Home/images/jnd28-logo.png" title="加拿大28">
						<font>加拿大28</font>
					</a>
				</li>
				<li <?php if($jsmtopen == 'false')echo 'class="gray hideli"';
?>>
					<a <?php if($jsmtopen == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=jsmt'";
}
?>>
						<img src="/Style/Home/images/jsmt-logo.png" title="极速摩托">
						<font>极速摩托</font>
					</a>
				</li>
				<li <?php if($jsscopen == 'false')echo 'class="gray hideli"';
?>>
					<a <?php if($jsscopen == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=jssc'";
}
?>>
						<img src="/Style/Home/images/jssc-logo.png" title="极速赛车">
						<font>极速赛车</font>
					</a>
				</li>
				<li <?php if($jssscopen == 'false')echo 'class="gray hideli"';
?>>
					<a <?php if($jssscopen == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=jsssc'";
}
?>>
						<img src="/Style/Home/images/jsssc-logo.png" title="极速时时彩">
						<font>极速时时彩</font>
					</a>
				</li>	
				<li <?php if($kuai3open == 'false')echo 'class="gray hideli"';
?>>
					<a <?php if($kuai3open == 'false'){
    echo 'href="#" class="gray"';
}else{
    echo "href='/qr.php?room={$_SESSION['roomid']}&g=kuai3'";
}
?>>
						<img src="/Style/Home/images/jsk3-logo.png" title="江苏快三">
						<font>江苏快三</font>
					</a>
				</li>
			</ul>
			<?php }
?>
			<ul class="menu" style="">
				<h3 class="tit">快捷菜单：</h3>
				<!--li>
					<a href="/">
						<i class="iconfont"></i> 
						<font>回到大厅</font>
					</a>
				</li-->
				<li>
					<a href="/Templates/user/">
						<i class="iconfont"></i>
						<font>个人中心</font>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<iframe width="880" height="0" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no" id="iframe" class="iframe" style="display:none" onload="iFrameHeight();"/>
</div>

<div class="zytips"><div>数据加载中..</div></div>
</body>
</html>