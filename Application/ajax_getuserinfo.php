<?php
include "../Public/config.php";
$arr = array();
if($_SESSION['userid'] == ""){
    $arr['success'] = false;
}else{
    $time = time()-300;
    update_query("fn_user", array("statustime" => time()), array("userid" => $_SESSION['userid'], 'roomid' => $_SESSION['roomid']));
    $arr['online'] = (int)get_query_val('fn_user', 'count(*)', "`roomid` = '{$_SESSION['roomid']}' and `statustime` > $time") + get_query_val('fn_setting', 'setting_people', array('roomid' => $_SESSION['roomid'])) + get_query_val('fn_setting', 'setting_robots', array('roomid' => $_SESSION['roomid']));
    $arr['price'] = get_query_val('fn_user', 'money', array('userid' => $_SESSION['userid'], 'roomid' => $_SESSION['roomid']));
    $arr['success'] = true;
}
echo json_encode($arr);
?>