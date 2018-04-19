<?php
include dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php";
require "function.php";
$info = getinfo($_SESSION['userid']);
$time = $_GET['time'] == "" ? 1 : (int)$_GET['time'];
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
                        <div class="p2">欢迎来到【
                            <?php echo get_query_val("fn_room", "roomname", array("roomid" => $_SESSION['roomid']));
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
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='orderinfo.php?time=1'">今日</button>
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='orderinfo.php?time=7'">昨日</button>
                    <button class="btn btn-primary kv_item" style="text-align: center;border-radius: 35px;" onclick="window.location.href='orderinfo.php?time=30'">30日</button>
                </div>
                <br>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="text-align:center">游戏</th>
                            <th style="text-align:center">期数</th>
                            <th style="text-align:center">内容</th>
                            <th style="text-align:center">盈亏</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $data = getorder($_SESSION['userid'], $time);
foreach($data['data'] as $con){
    if($con == null)continue;
    ?>
                        <tr align="center">
                            <td><?php echo $con[2];
    ?></td>
                            <td><?php echo $con[3];
    ?></td>
                            <td><?php echo $con[4] . '/' . $con[5];
    ?></td>
                            <td>
                                <?php if((int)$con[7] < 0){
        echo '<font color="red">' . $con[7] . '</font>';
    }elseif((int)$con[7] > 0){
        echo '<font color="green">' . $con[7] . '</font>';
    }else{
        echo "未结算";
    }
    ?>
                            </td>
                        </tr>
                    <?php }
if($data['data'][0] == null){
    echo '<tr><td colspan=4 style="text-align:center;">没有任何投注信息</td></tr>';
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