<?php include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$sql = get_query_vals('fn_setting', '*', array('roomid' => $_SESSION['roomid']));
if($_GET['g'] == ''){
    setcookie('game', $sql['setting_game'], time() + 36000000);
}else{
    $version = get_query_val('fn_room', 'version', array('roomid' => $_SESSION['roomid']));
    if($version != '会员版' && $version != '尊享版'){
        echo '<center><strong style="color:red;font-size:150px">不支持此功能</strong></center>';
        exit;
    }
    setcookie("game", $_GET['g'], time() + 36000000);
}
select_query("fn_welcome", '*', array("roomid" => $_SESSION['roomid']));
while($con = db_fetch_array()){
    $welcome .= "\"{$con['content']}\",";
}
$welcome = substr($welcome, 0, strlen($welcome) - 1);
?>
<!DOCTYPE html>
<html lang="en" data-dpr="2" style="font-size: 58.5938px;">
<head>
    <meta charset="UTF-8">
    <title> <?php echo $sitename;
?></title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/Style/New/css/reset.min.css">
    <link rel="stylesheet" type="text/css" href="/Style/New/css/style.css">
    <script type="text/javascript" src="/Style/New/js/jquery.min.js"></script>
    <style type="text/css">
        .mask {
            position: absolute;
            top: 0px;
            filter: alpha(opacity=95);
            background-color: black;
            z-index: 1002;
            left: 0px;
            opacity: 0.95;
            -moz-opacity: 0.95;
            display: none;
        }

        .mask img {
            align: center;
            width: 100%;
        }
    </style>
    <script>
        function getUserInfo(){
            $.ajax({
                url:'/Application/ajax_getuserinfo.php',
                type: 'get',
                cache:false,
                dataType:'json',
                success:function(data){
                    if(data.success){
                        $('#price').html(data.price);
                        $('#online').html(data.online);
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
    </script>
</head>

<body style="">
    <div class="content">
        <div class="person_desc">
            <a class="icon" href="user" target="_blank"><img src="<?php echo $_SESSION['headimg'];
?>" style="width:35px; height:35px;  border-radius:100px "></a>
            <div id="jryk">
                <p><?php echo $_SESSION['username'];
?></p>
                <p>积分:<span id="price">0</span></p>
            </div>
            <a class="down" href="pay/AiYang/cash.php" target="_blank">提现</a>
            <p data-listorder=""><a class="up" href="javascript:void(0);" onclick="showMask()">财微</a></p>
            <a class="down" href="pay/AiYang/cash.php" target="_blank">提现</a>
            <a class="up" href="pay/lkbpay/index.php" target="_blank">充值</a>
        </div>
        <div class="game_era" id="vodbox">
            <iframe name="window" src="/Templates/New/shipin.php" border="0" scrolling="no" style="transform: scale(0.375);"></iframe>
        </div>
        <div id="wrap">
            <div id="gameBanner" style="height:0.7rem;display:none;">

            </div>
            <div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <td style="width:20%; ">
                                <div class="list" style="/*top:0.8rem*/">
                                    <p style="font-size:10px;color:black"><span id="online">10</span>人在线</p>
                                    <p data-listorder="1" style=""><a style="background:#80ffff;" href="/Templates/New/Rules.php" target="window">游戏规则</a></p>
                                    <p data-listorder="7" style=""><a style="background:#87ce64;" href="javascript:;" id="hidvod">隐藏视频</a></p>
                                    <p data-listorder="10" style=""><a style="background:#f3400c;" href="/Templates/New/shipin.php" target="window">刷新视频</a></p>
                                    <p data-listorder="5" style=""><a style="background:#ffff00;" href="/Templates/New/BetTrend.php" target="window">历史开奖</a></p>
                                    <p data-listorder="8" style=""><a style="background:#8080ff;" href="/Templates/New/Bet.php" target="window">下注明细</a></p>
                                    <p data-listorder="4" style=""><a style="background:#ff00ff;" href="javascript:void(0);" onclick="showMask()">财务微信</a></p>
                                    <p data-listorder="4" style=""><a style="background:#0099ff;<?php if($sql['display_custom'] == 'false')echo 'display:none';
?>" href="javascript:void(0);" onclick="showMask()">联系客服</a></p>
                                    <p data-listorder="4" style=""><a style="background:#FA9F34;<?php if($sql['display_plan'] == 'false')echo 'display:none';
?>" href="javascript:void(0);" onclick="showMask()">长龙计划</a></p>
                                    <p data-listorder="3" style=""><a style="background:#80ff80;<?php if($sql['display_extend'] == 'false')echo 'display:none';
?>" href="user/tgzq/tgzq.php">推广赚钱</a></p>
                                </div>
                            </td>
                            <td style="width:80%; ">
                                <div class="main jq-create-wrap" align="left" id="">
                                    <ul>
                                        <ul class="fc jq-create-ul" id="chat"></ul>
                                    </ul>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="chat_box">
        <div>
           <table width="90%" border="0" cellspacing="0" cellpadding="0">
               <tbody>
                    <tr>
                      <td><input type="text" name="c0" id="c0" style="width:90%; height:35px; border:solid #c0c0c0 0.3px;color:#999;background-color: #ffffff;"
                                maxlength="16" placeholder="车道/玩法/金额"></td>
                      <td><input type="submit" name="chat" style="width:120%; height:35px; font-size:16px; background:#0099ff;border:solid #0099ff 1px; color:#ffffff;"
                                id="sendbtn" value="发送" ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script charset="utf-8" type="text/javascript" src="/Style/New/js/wap-size.js"></script>
    <script charset="utf-8" type="text/javascript" src="/Style/New/js/index.js"></script>
    <script type="text/javascript" src="/Style/New/js/app.js"></script>
    <div id="mask" class="mask" onclick="hideMask()">
        <?php $qrcode = get_query_val('fn_setting', 'setting_qrcode', array('roomid' => $_SESSION['roomid']));
if($qrcode == ""){
    echo '<br/><br/><center><span style="color:red;font-weight:bold;font-size:0.5rem">客服还没设置二维码噢</span></center>';
}else{
    echo "<img src='$qrcode'>";
}
?>
    
    </div>
    <script type="text/javascript">
        app.init();
        var headimg = "<?php echo $_SESSION['headimg'];
?>";
	    var nickname = "<?php echo $_SESSION['username'];
?>";
        function showMask() {
            $("#mask").css("height", $(document).height());
            $("#mask").css("width", $(document).width());
            $("#mask").show();
        }
        function hideMask() {
            $("#mask").hide();
        }
        var welcome = new Array(<?php echo $welcome;
?>);
	    var welHeadimg = "<?php echo get_query_val("fn_setting", "setting_sysimg", array("roomid" => $_SESSION['roomid']));
?>";

    </script>
</body>

</html>