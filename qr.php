<?php

include_once("Public/config.php");

$room=$_GET['room'];
$agent=$_GET['agent'];
$g=$_GET['g']; //pk10,xyft,cqssc,xy28,jnd28,jsmt,jssc,jsssc

$wx['ID'] = 'wx9c044f98156b8e20';
$time = date('Y-m-d H:i:s',time());

if($_SESSION['userid']){
    if(room_isOK($room)){
        $_SESSION['roomid'] = $room;
        $sitename = get_query_val('fn_room', 'roomname', array('roomid' => $room));

        setcookie('logintime', 'temp', time() + 7 * 24 * 60 * 60);
    }else{
        $_SESSION['error_room'] = $room;
        //echo '555';exit;
        require "Templates/error.php";
        exit();
    }

    $roomtime = get_query_val('fn_room', 'roomtime', array('roomid' => $room));
    if(strtotime($roomtime) - time() < 0){
        echo "<center><strong style='font-size:80px;'>您所访问的房间ID:{$room} <br>已于 <font color=red>$roomtime</font> 到期！<br>请提醒管理员进行续费！</strong></center>";
        exit;
    }
    if($agent != ""){
        setcookie('agent', $agent, time() + 7 * 24 * 60 * 60);
        $_COOKIE['agent'] = $agent;
    }

    $templates = get_query_val('fn_setting', 'setting_templates', array('roomid' => $_SESSION['roomid']));
    if($templates == 'old'){
        //echo $token['openid']. "<br></br>";exit;
        //U_isOK($_SESSION['userid'], $_SESSION['headimg']);
        require 'Templates/Old/index.php';
    }elseif($templates == 'new'){
        require 'Templates/New/index.php';
    }

}else{

//make code
    $oauth = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wx["ID"]."&redirect_uri=".urlencode("http://".$_SERVER["HTTP_HOST"]."/qr.php?agent=".$_GET['agent']."&g=".$_GET['g']."&room=".$_GET['room'])."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
//$oauth = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wx["ID"]."&redirect_uri=".urlencode("http://".$_SERVER["HTTP_HOST"]."/?room=".$_GET['room'])."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
    if($_GET['code']==''){
        Header("Location: $oauth");
    }
    $code=$_GET['code'];
    echo "<form style='display:none;' id='form1' name='form1' method='post' action='http://".$_SERVER["HTTP_HOST"]."/'>
             
             <input name='verify' type='text' value='n2oqcvVPpk1M' />
			  <input name='room' type='text' value='".$room."' />
			  <input name='agent' type='text' value='".$agent."' />
			  <input name='g' type='text' value='".$g."' />
			  <input name='code' type='text' value='".$code."' />
			  <input name='time' type='text' value='".$time."' />
              			  
            </form><script type='text/javascript'>function load_submit(){document.form1.submit()}load_submit();</script>";
}

?>