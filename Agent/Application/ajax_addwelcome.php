<?php include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'add'){
    $text = $_POST['text'];
    $room = $_SESSION['agent_room'];
    insert_query("fn_welcome", array("roomid" => $room, 'content' => $text, 'addtime' => 'now()'));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'del'){
    $id = $_POST['id'];
    delete_query("fn_welcome", array("id" => $id));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'sendmsg'){
    $text = $_POST['text'];
    $id = $_POST['id'];
    $sql = get_query_vals('fn_chat', '*', array('id' => $id));
    $username = $sql['username'];
    $game = $sql['game'];
    $room = $sql['roomid'];
    $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $room));
    insert_query("fn_chat", array("userid" => "system", "username" => "管理员", "headimg" => $headimg, 'content' => '@' . $username . ', ' . $text, 'addtime' => date('H:i:s'), 'game' => $game, 'roomid' => $room, 'type' => 'S1'));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'sendcustom'){
    $text = $_POST['text'];
    $id = $_POST['id'];
    $sql = get_query_vals('fn_custom', '*', array('id' => $id));
    $username = $sql['username'];
    $userid = $sql['userid'];
    $room = $sql['roomid'];
    $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $room));
    insert_query("fn_custom", array("userid" => $userid, 'username' => '管理员', 'headimg' => $headimg, 'content' => $text, 'addtime' => 'now()', 'roomid' => $room, 'status' => '回复', 'type' => 'S1'));
    update_query("fn_custom", array("status" => "已回"), array("id" => $id));
    echo json_encode(array("success" => true));
    exit();
}
?>