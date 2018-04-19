<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$content = $_POST['cont'];
$id = $_POST['id'];
$userid = get_query_val('fn_user', 'userid', array('id' => $id));
$headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['agent_room']));
insert_query("fn_custom", array("userid" => $userid, 'username' => '管理员', 'headimg' => $headimg, 'content' => $content, 'addtime' => 'now()', 'roomid' => $_SESSION['agent_room'], 'status' => '已读', 'type' => 'S1'));
echo json_encode(array("success" => true))?>