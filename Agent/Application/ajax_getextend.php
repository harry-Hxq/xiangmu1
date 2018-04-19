<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'get'){
    $id = $_GET['id'];
    $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    $allmoney = 0;
    $allliu = 0;
    $allyk = 0;
    $alls = 0;
    select_query("fn_user", '*', "roomid = {$_SESSION['agent_room']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0");
        $y = $z - $l;
        $pcy = $pcz - $pcl;
        $sscy = $sscz - $sscl;
        $mty = $mtz - $mtl;
        $jsscy = $jsscz - $jsscl;
        $jssscy = $jssscz - $jssscl;
        $yk = $y + $pcy + $sscy + $mty + $jsscy + $jssscy;
        $yk = sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2));
        $allyk += $yk;
        $liushui = $l + $pcl + $sscl + $mtl + $jsscl + $jssscl;
        $allliu += $liushui;
        $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` = '已处理'");
        $alls += $s;
        $arr['data'][] = array($con['id'], "<img src='{$con['headimg']}' height='30' width='30'> " . $con['username'], $con['money'], "$liushui", $yk, $s, date('Y-m-d H:i:s', $con['statustime']));
        $allmoney += $con['money'];
    }
    if(count($cons) == 0){
        $arr['data'][0] = 'null';
    }
    $arr['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $arr['allyk'] = sprintf("%.2f", substr(sprintf("%.3f", $allyk), 0, -2));
    $arr['alls'] = $alls;
    $arr['allliu'] = $allliu;
    echo json_encode($arr);
    exit();
}elseif($type == 'giveone'){
    $id = $_POST['id'];
    $num = $_POST['num'];
    $mode = $_POST['mode'];
    $time = $_POST['time'] == '' ? '总报表账目' : $_POST['time'];
    $num = $num / 100;
    $userid = get_query_val('fn_user', 'userid', array('id' => $id));
    if($_POST['time'] != ""){
        $a = explode(' - ', $_POST['time']);
        $ordersql = " and (`addtime` between '{$a[0]}' and '$a[1]')";
        $marksql = " and (`time` between '{$a[0]}' and '$a[1]')";
    }else{
        $ordersql = '';
        $marksql = '';
    }
    select_query("fn_user", '*', "roomid = {$_SESSION['agent_room']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        switch($mode){
        case 'liushui': $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $liushui = $l + $pcl + $sscl + $mtl + $jsscl + $jssscl;
            $money = $liushui * $num;
            用户_上分($userid, $money, $_SESSION['agent_room'], $time);
            echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            break;
        case "yingli": $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $yk = $z - $l;
            $yk += $pcz - $pcl;
            $yk += $sscz - $sscl;
            $yk += $mtz - $mtl;
            $yk += $jsscz - $jsscl;
            $yk += $jssscz - $jssscl;
            if($yk > 0){
                $money = $yk * $num;
                用户_上分($userid, $money, $_SESSION['agent_room'], $time);
                echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            }else{
                echo json_encode(array("success" => true, "msg" => "该团队并无盈利", "money" => getmoney($userid)));
            }
            break;
        case "kuisun": $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
            $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
            $yk = $z - $l;
            $yk += $pcz - $pcl;
            $yk += $sscz - $sscl;
            $yk += $mtz - $mtl;
            $yk += $jsscz - $jsscl;
            $yk += $jssscz - $jssscl;
            if($yk < 0){
                $money = $yk * $num;
                用户_上分($userid, $money, $_SESSION['agent_room'], $time);
                echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            }else{
                echo json_encode(array("success" => true, "msg" => "该团队并无亏损"));
            }
            break;
        case "chongzhi": $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['agent_room']} and userid = '{$con['userid']}' and `status` = '已处理'" . $marksql);
            $money = $s * $num;
            用户_上分($userid, $money, $_SESSION['agent_room'], $time);
            echo json_encode(array("success" => true, "msg" => "分红完毕,获得" . $money . '元', 'money' => getmoney($userid)));
            break;
        }
    }
}elseif($type == 'add'){
    $id = $_GET['id'];
    update_query("fn_user", array("isagent" => "true"), array("id" => $id));
    echo json_encode(array("success" => true));
}elseif($type == 'addxia'){
    $id = $_GET['id'];
    $agent = $_GET['agent'];
    if(get_query_val("fn_user", "agent", array("id" => $id)) != 'null'){
        echo json_encode(array('success' => false, 'msg' => '该玩家已经拥有下线,无法手动为该玩家添加下线..'));
        exit;
    }else{
        $userid = get_query_val('fn_user', 'userid', array('id' => $agent));
        update_query("fn_user", array("agent" => $userid), array('id' => $id));
        echo json_encode(array("success" => true));
        exit;
    }
}elseif($type == 'removexia'){
    $id = $_GET['id'];
    update_query("fn_user", array("agent" => "null"), array("id" => $id));
    echo json_encode(array("success" => true));
    exit;
}
function 用户_上分($Userid, $Money, $room, $time){
    update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
    insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => $time . '推广分红', 'money' => $Money, 'addtime' => 'now()'));
}
function getmoney($userid){
    return get_query_val('fn_user', 'money', array('roomid' => $_SESSION['agent_room'], 'userid' => $userid));
}
?>