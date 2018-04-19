<?php
include_once("../Public/config.php");
$type = $_GET['type'];
switch($type){
case 'first': $arr = array();
    select_query("fn_custom", '*', "roomid = {$_SESSION['roomid']} and userid = '{$_SESSION['userid']}' order by id desc limit 0,50");
    while($x = db_fetch_array()){
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => date('H:i:s', strtotime($x['addtime'])), 'type' => $x['type'], 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "update": $arr = array();
    $chatid = $_GET['id'];
    select_query("fn_custom", '*', "roomid = {$_SESSION['roomid']} and userid = '{$_SESSION['userid']}' and id>$chatid order by id asc");
    while($x = db_fetch_array()){
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => date('H:i:s', strtotime($x['addtime'])), 'type' => $x['type'], 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "send": $nickname = $_SESSION['username'];
    $content = $_POST['content'];
    $headimg = $_SESSION['headimg'];
    $type = "U2";
    echo json_encode(array("success" => true, "content" => $content));
    insert_query("fn_custom", array("username" => $nickname, 'content' => $content, 'addtime' => 'now()', 'headimg' => $headimg, 'type' => $type, 'userid' => $_SESSION['userid'], 'status' => '未读', 'roomid' => $_SESSION['roomid']));
    break;
}
?>