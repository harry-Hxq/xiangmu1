<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'addplan'){
    $plan = $_POST['plan'];
    $game = $_POST['game'];
    insert_query("fn_robotplan", array("content" => $plan, 'game' => $game, 'addtime' => 'now()', 'roomid' => $_SESSION['agent_room']));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'delplan'){
    $id = $_POST['id'];
    delete_query("fn_robotplan", array("id" => $id));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'getplan'){
    $game = $_POST['game'];
    $str = '';
    select_query("fn_robotplan", '*', "roomid = {$_SESSION['agent_room']} and game = '$game'");
    while($con = db_fetch_array()){
        $str .= "<option value='{$con['id']}'>方案ID:{$con['id']} [ {$con['content']} ]</option>";
    }
    echo $str;
}elseif($type == 'addrobot'){
    $game = $_POST['addgame'];
    $plan = $_POST['addplan'];
    $name = $_POST['addname'];
    if($_FILES['addheadimg']['size'] > 0){
        if ((($_FILES["addheadimg"]["type"] == "image/gif") || ($_FILES["addheadimg"]["type"] == "image/jpeg") || ($_FILES["addheadimg"]["type"] == "image/png")) && ($_FILES["addheadimg"]["size"] < 2000000)){
            if ($_FILES["addheadimg"]["error"] > 0){
                echo json_encode(array('success' => false, 'msg' => '上传文件出错..错误代码:' . $_FILES["addheadimg"]["error"]));
                exit();
            }else{
                $addheadimg = '/upload/' . date('Ymd') . time() . '.' . getfileType($_FILES['addheadimg']['name']);
                $filedir = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . $addheadimg;
                move_uploaded_file($_FILES["addheadimg"]["tmp_name"], $filedir);
            }
        }else{
            echo json_encode(array("success" => false, "msg" => "头像上传错误,请联系客服.."));
            exit();
        }
    }else{
        echo json_encode(array("success" => false, "msg" => "没有找到头像,请重新上传.."));
        exit();
    }
    foreach($plan as $x){
        $plans .= $x . '|';
    }
    $plans = substr($plans, 0, strlen($plans)-1);
    insert_query("fn_robots", array("headimg" => $addheadimg, 'name' => $name, 'plan' => $plans, 'game' => $game, 'roomid' => $_SESSION['agent_room']));
    echo json_encode(array("success" => true));
    exit();
}elseif($type == 'delrobot'){
    $id = $_POST['id'];
    delete_query("fn_robots", array("id" => $id));
    echo json_encode(array("success" => true));
}elseif($type == 'start'){
    $open = get_query_val('fn_setting', 'setting_runrobot', array('roomid' => $_SESSION['agent_room']));
    $robots = get_query_val('fn_robots', 'count(*)', "roomid = {$_SESSION['agent_room']}");
    if($open == ''){
        $open = 'true';
        update_query("fn_setting", array("setting_robots" => $robots), array('roomid' => $_SESSION['agent_room']));
    }elseif($open == 'true'){
        $open = 'false';
        update_query("fn_setting", array("setting_robots" => '0'), array("roomid" => $_SESSION['agent_room']));
    }elseif($open == 'false'){
        $open = 'true';
        update_query("fn_setting", array("setting_robots" => $robots), array('roomid' => $_SESSION['agent_room']));
    }
    update_query("fn_setting", array("setting_runrobot" => $open), array('roomid' => $_SESSION['agent_room']));
    echo $open;
    exit;
}elseif($type == 'set'){
    $min = $_POST['min'];
    $max = $_POST['max'];
    $point_min = $_POST['point_min'];
    $point_max = $_POST['point_max'];
    update_query("fn_setting", array("setting_robot_min" => $min, 'setting_robot_max' => $max, 'setting_robot_pointmin' => $point_min, 'setting_robot_pointmax' => $point_max), array('roomid' => $_SESSION['agent_room']));
    echo json_encode(array("success" => true));
    exit;
}
function getfileType($file){
    return substr(strrchr($file, '.'), 1);
}
?>