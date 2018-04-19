<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$old = $_POST['pass'];
$new = $_POST['newpass'];
if(md5($old) != get_query_val('fn_room', 'roompass', array('roomid' => $_SESSION['agent_room']))){
    echo json_encode(array('success' => false, 'msg' => '旧密码不正确,请重新输入'));
    exit();
}else{
  	if($_SESSION['agent_room']!=19){
    update_query("fn_room", array("roompass" => md5($new)), array('roomid' => $_SESSION['agent_room']));
    echo json_encode(array("success" => true));
    exit();
    }
}
?>