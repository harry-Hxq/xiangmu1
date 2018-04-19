<?php
function getSubstr($str, $leftStr, $rightStr){
    $left = strpos($str, $leftStr);
    $right = strpos($str, $rightStr, $left);
    if($left < 0 or $right < $left)return '';
    return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}
session_start();
$info = "http://" . $_SERVER['HTTP_HOST'] . "/qr.php?room=" . $_SESSION['roomid'] . "&agent=" . $_SESSION['userid'];
$info1 = "http://" . $_SERVER['HTTP_HOST'] . "%2Fqr.php%3Froom%3D" . $_SESSION['roomid'] . "%26agent%3D" . $_SESSION['userid'];
//$html = file_get_contents("https://cli.im/api/qrcode/code?text=" . $info1 . "&mhid=vBaVXwrmmM8hMHcsL9FUP6I");
$html = file_get_contents("https://cli.im/api/qrcode/code?text=" . $info1 . "");
$qrcode = getSubstr($html, "<img src=\"", "\" id=")?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="/Style/New/css/activity-style.css" rel="stylesheet" type="text/css">

</head>

<body class="activity-lottery-winning">
        <div class="main">
                <div id="outercont">
                    <div id="outer-cont">
                         <div id="outer"><img src="<?php echo $qrcode;
?>" width="200"/></div> 
                    </div>
                </div>
                <div class="content">
                    <div class="boxcontent boxyellow">
                        <div class="box">
                            <div class="title-green"><span>复制链接：</span></div>
                            <div class="Detail">
                                <p>
                                    <?php echo $info;
?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="boxcontent boxyellow">
                        <div class="box">
                            <div class="title-green">转发小提示：</div>
                            <div class="Detail">
                                <p>方法1：长按二维码，保存到手机发送给朋友！</p>
                                <p>方法2：长按链接全选复制粘贴给朋友！</p>
                            </div>
                        </div>
                    </div>
                </div>

        </div>



</body>

</html>


<!-- 以下是统计及其他信息，与演示无关，不必理会 -->
</div>