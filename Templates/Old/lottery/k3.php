<?php
switch($_COOKIE['game']){
case 'kuai3': $lot = 'fn_lottery9';
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
var sharedesc="我正在卓越娱乐系统提供的游戏房间玩耍！赶紧加入吧！[长按收藏]永不丢失加入口！";
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
<script type="text/javascript" src="/Style/Old/js/pc.js"></script>
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
				<input placeholder="投注类型/金额" type="text" id="Message">
				<img src="/Style/Xs/Public/images/kb.png" class="keybord gray">
				<span class="sendemaill">发 送</span>
			</div>
			<span class="txtbet">快捷下注</span>
			<div class="keybord_div" id="keybord_div">
				<em>0</em>
				<em>1</em>
				<em>2</em>
				<em>3</em>
				<em>4</em>
				<em>5</em>
				<em>6</em>
				<em>7</em>
				<em>8</em>
				<em>9</em>
				<em>大</em>
				<em>小</em>
				<em>单</em>
				<em>双</em>
				<em>极大</em>
				<em>极小</em>
				<em>对子</em>
				<em>顺子</em>
				<em>豹子</em>
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
	<div class="game-box" style="display:none">
		<div class="game-hd">
			<div class="menu">
				<ul>
					<li class="gameli"><a href="javascript:;" class="on" data-t="1">猜和值</a></li>
					<li class="gameli"><a href="javascript:;" data-t="2">猜极值</a></li>
					<li class="gameli"><a href="javascript:;" data-t="3">猜组合</a></li>
					<li class="gameli"><a href="javascript:;" data-t="4">猜类型</a></li>
					</li>
				</ul>
			</div>
			<!--<h4 id="game-gtype">猜大小单双</h4>-->
			<div class="infuse">
				<a href="javascript:;" class="clearnum">清空所选</a>
				<em id="bet_num">共0注</em>
				<a href="javascript:;" class="confirm-pour">确定下注</a>
			</div>
		</div>
		<div class="game-bd">
			<!--猜大小单双-->
			<div class="gamenum game-type-1">
				<div class="rank-tit"><span class="change">猜和值</span></div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn mini-btn" data-val="0">
						<div class="h5">
							<h5>0</h5>
							<p><em>× <?php echo $info['0027'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="1">
						<div class="h5">
							<h5>1</h5>
							<p><em>× <?php echo $info['0126'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="2">
						<div class="h5">
							<h5>2</h5>
							<p><em>× <?php echo $info['0225'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="3">
						<div class="h5">
							<h5>3</h5>
							<p><em>× <?php echo $info['0324'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="4">
						<div class="h5">
							<h5>4</h5>
							<p><em>× <?php echo $info['0423'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="5">
						<div class="h5">
							<h5>5</h5>
							<p><em>× <?php echo $info['0522'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="6">
						<div class="h5">
							<h5>6</h5>
							<p><em>× <?php echo $info['0621'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="7">
						<div class="h5">
							<h5>7</h5>
							<p><em>× <?php echo $info['0720'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="8">
						<div class="h5">
							<h5>8</h5>
							<p><em>× <?php echo $info['891819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="9">
						<div class="h5">
							<h5>9</h5>
							<p><em>× <?php echo $info['891819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="10">
						<div class="h5">
							<h5>10</h5>
							<p><em>× <?php echo $info['10111617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="11">
						<div class="h5">
							<h5>11</h5>
							<p><em>× <?php echo $info['10111617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="12">
						<div class="h5">
							<h5>12</h5>
							<p><em>× <?php echo $info['1215'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="13">
						<div class="h5">
							<h5>13</h5>
							<p><em>× <?php echo $info['1314'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="14">
						<div class="h5">
							<h5>14</h5>
							<p><em>× <?php echo $info['1314'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="15">
						<div class="h5">
							<h5>15</h5>
							<p><em>× <?php echo $info['1215'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="16">
						<div class="h5">
							<h5>16</h5>
							<p><em>× <?php echo $info['10111617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="17">
						<div class="h5">
							<h5>17</h5>
							<p><em>× <?php echo $info['10111617'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="18">
						<div class="h5">
							<h5>18</h5>
							<p><em>× <?php echo $info['891819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="19">
						<div class="h5">
							<h5>19</h5>
							<p><em>× <?php echo $info['891819'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="20">
						<div class="h5">
							<h5>20</h5>
							<p><em>× <?php echo $info['0720'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="21">
						<div class="h5">
							<h5>21</h5>
							<p><em>× <?php echo $info['0621'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="22">
						<div class="h5">
							<h5>22</h5>
							<p><em>× <?php echo $info['0522'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="23">
						<div class="h5">
							<h5>23</h5>
							<p><em>× <?php echo $info['0423'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="24">
						<div class="h5">
							<h5>24</h5>
							<p><em>× <?php echo $info['0324'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="25">
						<div class="h5">
							<h5>25</h5>
							<p><em>× <?php echo $info['0225'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="26">
						<div class="h5">
							<h5>26</h5>
							<p><em>× <?php echo $info['0126'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn mini-btn" data-val="27">
						<div class="h5">
							<h5>27</h5>
							<p><em>× <?php echo $info['0027'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<!--猜三球总和-->
			<div class="gamenum game-type-2">
				<div class="rank-tit"><span class="change"></span></div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="极大">
						<div class="h5">
							<h5>极大</h5>
							<p><em>× <?php echo $info['jida'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="虎">
						<div class="h5">
							<h5>极小</h5>
							<p><em>× <?php echo $info['jixiao'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<div class="gamenum game-type-3">
				<div class="rank-tit"><span class="change"></span></div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn middle-btn" data-val="大单">
						<div class="h5">
							<h5>大单</h5>
							<p><em>× <?php echo $info['dadan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="小单">
						<div class="h5">
							<h5>小单</h5>
							<p><em>× <?php echo $info['xiaodan'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="大双">
						<div class="h5">
							<h5>大双</h5>
							<p><em>× <?php echo $info['dashuang'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn middle-btn" data-val="小双">
						<div class="h5">
							<h5>小双</h5>
							<p><em>× <?php echo $info['xiaoshuang'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
			<div class="gamenum game-type-4">
				<div class="rank-tit"><span class="change"></span></div>
				<div class="btn-box btn-grounp">
					<a href="javascript:;" class="btn large-btn" data-val="豹子">
						<div class="h5">
							<h5>豹子</h5>
							<p><em>× <?php echo $info['baozi'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-val="对子">
						<div class="h5">
							<h5>对子</h5>
							<p><em>× <?php echo $info['duizi'];
?></em></p>
						</div>
					</a>
					<a href="javascript:;" class="btn large-btn" data-val="顺子">
						<div class="h5">
							<h5>顺子</h5>
							<p><em>× <?php echo $info['shunzi'];
?></em></p>
						</div>
					</a>
				</div>
			</div>
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
				<li <?php if($pk10open == 'false')echo 'class="gray"';
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
				<li <?php if($xyftopen == 'false')echo 'class="gray"';
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
				<li <?php if($cqsscopen == 'false')echo 'class="gray"';
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
				<li <?php if($xy28open == 'false')echo 'class="gray"';
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
				<li <?php if($jnd28open == 'false')echo 'class="gray"';
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
				<li <?php if($jsmtopen == 'false')echo 'class="gray"';
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
				<li <?php if($jsscopen == 'false')echo 'class="gray"';
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
				<li <?php if($jssscopen == 'false')echo 'class="gray"';
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
				<li <?php if($kuai3open == 'false')echo 'class="gray"';
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