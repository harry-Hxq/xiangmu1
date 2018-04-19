<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_POST['type'];
$id = $_POST['id'];
$money = $_POST['money'];
switch($type){
case 'up': $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    用户_上分($userid, $money, $_SESSION['agent_room']);
    echo json_encode(array("success" => true, "msg" => "操作成功"));
    break;
case "down": $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    用户_下分($userid, $money, $_SESSION['agent_room']);
    echo json_encode(array("success" => true, "msg" => "操作成功"));
    break;
}
function 用户_上分($Userid, $Money, $room){
update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => '管理员手动上分', 'money' => $Money, 'addtime' => 'now()'));
}
function 用户_下分($Userid, $Money, $room){
update_query('fn_user', array('money' => '-=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '下分', 'content' => '管理员手动下分', 'money' => $Money, 'addtime' => 'now()'));
}
?>