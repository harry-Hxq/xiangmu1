<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
$id = $_GET['id'];
$id = get_query_vals('fn_upmark', '*', array('id' => $id));
$userid = $id['userid'];
$username = $id['username'];
$money = $id['money'];
$game = $id['game'];
$room = $_SESSION['agent_room'];
switch($type){
case 'up': 用户_上分($userid, $money, $room);
    update_query("fn_upmark", array("status" => "已处理"), array("id" => $id['id']));
    管理员喊话("@$username,您的上分请求已同意!", $game);
    echo json_encode(array("success" => true));
    exit();
    break;
case "down": if(get_query_val("fn_setting", "setting_downmark", array("roomid" => $room)) != 'true'){
        用户_下分($userid, $money, $room);
        管理员喊话("@$username,您的下分请求已同意!", $game);
    }
    echo json_encode(array("success" => true));
    update_query("fn_upmark", array("status" => "已处理"), array("id" => $id['id']));
    exit();
    break;
case "exit": if(get_query_val("fn_setting", "setting_downmark", array("roomid" => $room)) == 'true' && $id['type'] == '下分'){
        update_query('fn_user', array('money' => '+=' . $money), array('userid' => $userid, 'roomid' => $room));
        insert_query("fn_marklog", array("roomid" => $room, 'userid' => $userid, 'type' => '上分', 'content' => '管理拒绝下分,分数退还' . $money, 'money' => $money, 'addtime' => 'now()'));
    }
    update_query("fn_upmark", array("status" => "已拒绝"), array("id" => $id['id']));
    echo json_encode(array("success" => true));
    exit();
    break;
case "set": update_query("fn_setting", array("setting_downmark" => $_POST['status']), array('roomid' => $room));
    echo json_encode(array("success" => true, "msg" => "操作成功"));
    exit();
    break;
}
function 用户_上分($Userid, $Money, $room){
update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => '管理同意上分' . $Money, 'money' => $Money, 'addtime' => 'now()'));
}
function 用户_下分($Userid, $Money, $room){
update_query('fn_user', array('money' => '-=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '下分', 'content' => '管理员同意下分' . $Money, 'money' => $Money, 'addtime' => 'now()'));
}
function 管理员喊话($Content, $game){
$headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['agent_room']));
insert_query("fn_chat", array("userid" => "system", "username" => "管理员", "headimg" => $headimg, 'game' => $game, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S1', 'roomid' => $_SESSION['agent_room']));
}
?>