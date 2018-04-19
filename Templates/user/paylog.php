<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$info = getinfo($_SESSION['userid']);
$time = $_GET['time'] == "" ? 1:$_GET['time'];
switch($time){
case 1: $time = date('Y-m-d');
    $time2 = date('Y-m-d');
    break;
case 7: $time = date('Y-m-d', strtotime("-1 day"));
    $time2 = date('Y-m-d', strtotime('-1 day'));
    break;
case 30: $time = date('Y-m-d', strtotime("-30 day"));
    $time2 = date('Y-m-d');
    break;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="user-scalable=no,width=device-width" />
    <meta name="baidu-site-verification" content="W8Wrhmg6wj" />
    <meta content="telephone=no" name="format-detection">
    <meta content="1" name="jfz_login_status">
    <script type="text/javascript" src="js/record.origin.js"></script>
    
    <link rel="stylesheet" type="text/css" href="css/common.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="css/new_cfb.css?v=1.2" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css?v=1.2" />

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.js?v=1.2"></script>
    <script type="text/javascript" src="js/global.js?v=1.2"></script>
    <script type="text/javascript" src="js/common.v3.js?v=1.2"></script>
    <script type="text/javascript" src="js/jweixin-1.0.0.js"></script>
    <title>个人中心</title>
</head>

<body>

    <div class="wx_cfb_container wx_cfb_account_center_container">
        <div class="wx_cfb_account_center_wrap">
            <div class="wx_cfb_ac_fund_detail">
                <div class="user_info clearfix">
                    <div class="user_photo"><img src="<?php echo $_SESSION['headimg'];
?>" style="width:45px; height:45px; "></div>
                    <div class="user_txt">
                        <div class="p1">
                            <?php echo $_SESSION['username'];
?>
                        </div>
                        <div class="p2">欢迎来到【<?php echo get_query_val("fn_room", "roomname", array("roomid" => $_SESSION['roomid']));
?>】娱乐房间</div>
                    </div>
                </div>
                <div class="fund_info">
                    <div class="kv_tb_list clearfix">
                        <div class="kv_item">
                            <span class="val"><?php echo get_query_val("fn_user", "money", array("roomid" => $_SESSION['roomid'], 'userid' => $_SESSION['userid']));
?></span>
                            <span class="key">我的钱包</span>
                        </div>
                        <div class="kv_item">
                            <span class="val"><?php echo $info['yk'];
?></span>
                            <span class="key">今日盈亏</span>
                        </div>
                        <div class="kv_item">
                            <span class="val"><?php echo $info['liu'];
?></span>
                            <span class="key">今日流水</span>
                        </div>
                    </div>
                </div>
            </div>
            <!--入口-->
            <div class="wx_cfb_entry_list">
                <br>
                <div class="kv_tb_list clearfix">
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='paylog.php?time=1'">今日</button>
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='paylog.php?time=7'">昨日</button>
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='paylog.php?time=30'">30日</button>
                </div>
                <br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="text-align:center">类型</th>
                            <th style="text-align:center">金额</th>
                            <th style="text-align:center">时间</th>
                            <th style="text-align:center">状态</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php select_query("fn_upmark", '*', "userid = '{$_SESSION['userid']}' and roomid = {$_SESSION['roomid']} and time between '$time 00:00:00' and '$time2 23:59:59'");
while($con = db_fetch_array()){
$cons[] = $con;
?>
                        <tr align="center">
                            <td><?php echo $con['type']?></td>
                            <td><?php echo $con['money']?></td>
                            <td><?php echo $con['time']?></td>
                            <td><?php echo $con['status']?></td>
                        </tr>
                    <?php }
if(count($cons) == 0){
echo '<tr><td colspan=4 style="text-align: center">您没有任何充值记录</td></tr>';
}
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="wx_cfb_fixed_btn_box">
        <div class="wx_cfb_fixed_btn_wrap">
            <div class="btn_box clearfix">
                <a href="/qr.php?room=<?php echo $_SESSION['roomid'];
?>" class="btns tel_btn clearfix">
                    <em class="ico ui_ico_size_40 ui_tel_ico"></em><span class="txt">返回游戏</span>
                </a>
            </div>
        </div>
    </div>

    </div>

</html>