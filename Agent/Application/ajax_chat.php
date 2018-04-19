<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['type'];
$sql = $_GET['game'] == '' ? '' : "and `game` = '{$_GET['game']}'";
switch($type){
case 'first': $arr = array();
    select_query("fn_chat", '*', "roomid = {$_SESSION['agent_room']} $sql order by id desc limit 0,20");
    while($x = db_fetch_array()){
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => $x['addtime'], 'type' => $x['type'], 'game' => $x['game'], 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "update": $arr = array();
    $chatid = $_GET['id'];
    select_query("fn_chat", '*', "roomid = {$_SESSION['agent_room']} $sql and id>$chatid order by id asc");
    while($x = db_fetch_array()){
        if($x['userid'] == $_SESSION['userid'])continue;
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => $x['addtime'], 'type' => $x['type'], 'game' => $x['game'], 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "send": $content = $_POST['content'];
    $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['agent_room']));
    if($_GET['game'] != ""){
        insert_query('fn_chat', array('username' => '管理员', 'content' => $content, 'addtime' => date('H:i:s'), 'headimg' => $headimg, 'type' => 'S1', 'game' => $_GET['game'], 'userid' => 'system', 'roomid' => $_SESSION['agent_room']));
    }else{
        insert_query("fn_chat", array("username" => "管理员", "content" => $content, 'addtime' => date('H:i:s'), 'headimg' => $headimg, 'type' => 'S1', 'game' => 'pk10', 'userid' => 'system', 'roomid' => $_SESSION['agent_room']));
        insert_query("fn_chat", array("username" => "管理员", "content" => $content, 'addtime' => date('H:i:s'), 'headimg' => $headimg, 'type' => 'S1', 'game' => 'xyft', 'userid' => 'system', 'roomid' => $_SESSION['agent_room']));
        insert_query("fn_chat", array("username" => "管理员", "content" => $content, 'addtime' => date('H:i:s'), 'headimg' => $headimg, 'type' => 'S1', 'game' => 'xy28', 'userid' => 'system', 'roomid' => $_SESSION['agent_room']));
        insert_query("fn_chat", array("username" => "管理员", "content" => $content, 'addtime' => date('H:i:s'), 'headimg' => $headimg, 'type' => 'S1', 'game' => 'jnd28', 'userid' => 'system', 'roomid' => $_SESSION['agent_room']));
    }
    echo json_encode(array("success" => true, "content" => $content));
    break;
}
?>