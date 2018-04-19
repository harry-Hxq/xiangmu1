<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$arr = array();
if($_POST['id'] != ''){
    $id = $_POST['id'];
    $userid = get_query_vals('fn_user', '*', array('id' => $id));
    if(get_query_val("fn_ban", "id", array("userid" => $userid['userid'], 'roomid' => $_SESSION['agent_room'])) != ""){
        $arr['success'] = false;
        $arr['msg'] = '该玩家已存在禁言列表内,无法再进行添加.';
        echo json_encode($arr);
        exit();
    }
    $username = $userid['username'];
    $headimg = $userid['headimg'];
    $userid = $userid['userid'];
    insert_query("fn_ban", array("userid" => $userid, 'username' => $username, 'headimg' => $headimg, 'addtime' => 'now()', 'roomid' => $_SESSION['agent_room']));
    $arr['success'] = true;
}else{
    $arr['success'] = false;
    $arr['msg'] = '参数错误..Err(-1203)';
}
echo json_encode($arr);
?>