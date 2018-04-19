<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'getuser'){
    $time = $_GET['time'];
    $time = explode(' - ', $time);
    $arr = array();
    select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $sf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
        $xf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
        $allm = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $allz = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $sscm = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $pcm = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $mtm = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $jsscm = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $jssscm = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
        $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
        $sscyk = $sscz - $sscm;
        $jssscyk = $jssscz - $jssscm;
        $jsscyk = $jsscz - $jsscm;
        $mtyk = $mtz - $mtm;
        $pcyk = $pcz - $pcm;
        $yk = $allz - $allm;
        $yk += $pcyk + $mtyk + $sscyk + $jsscyk + $jssscyk;
        $allm += $pcm + $mtm + $sscm + $jsscm + $jssscm;
        $yk = sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2));
        $arr['data'][] = array($con['id'], $con['username'], $con['userid'], $sf, $xf, $yk, $allm);
    }
    echo json_encode($arr);
}elseif($type == 'onetui'){
    $time = $_POST['time'];
    $mode = $_POST['mode'];
    $plan1 = $_POST['plan1'];
    $plan1s = $_POST['plan1s'];
    $plan2 = $_POST['plan2'] == '-' ? '': $_POST['plan2'];
    $plan2s = $_POST['plan2s'];
    $plan3 = $_POST['plan3'] == '-' ? '': $_POST['plan3'];
    $plan3s = $_POST['plan3s'];
    if($plan1 != '' && $plan1s != ''){
        $plan1 = explode('-', $plan1);
        $plan1s = $plan1s / 100;
        if($plan2 != '' && $plan2s != ''){
            $plan2 = explode('-', $plan2);
            $plan2s = $plan2s / 100;
        }
        if($plan3 != '' && $plan3s != ''){
            $plan3 = explode('-', $plan3);
            $plan3s = $plan3s / 100;
        }
    }else{
        echo json_encode(array("success" => false, "msg" => "方案没有填写!!"));
        exit();
    }
    $arr = array();
    switch($mode){
    case 'liushui': $arr['mode'] = '流水';
        break;
    case "kuisun": $arr['mode'] = '亏损';
        break;
    case "yingli": $arr['mode'] = '盈利';
        break;
    case "shangfen": $arr['mode'] = '上分';
        break;
    case "xiafen": $arr['mode'] = '下分';
        break;
    }
    select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
    while($con = db_fetch_array()){
    $cons[] = $con;
}
foreach($cons as $con){
    switch($mode){
    case 'liushui': $liushui = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $liushui += (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $liushui += (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $liushui += (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $liushui += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $liushui += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
            $tui = $liushui * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
            $tui = $liushui * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
            $tui = $liushui * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }else{
            continue;
        }
        break;
    case "kuisun": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $z - $m;
        $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        if($yk < 0){
            $yk = abs($yk);
            if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
                $tui = $yk * $plan1s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
                $tui = $yk * $plan2s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
                $tui = $yk * $plan3s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }else{
                continue;
            }
        }
        break;
    case "yingli": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $z - $m;
        $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')  and (status > 0 or status < 0)");
        $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59') and status > 0 and userid = '{$con['userid']}'");
        $yk = $yk + ($z - $m);
        if($yk > 0){
            if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
                $tui = $yk * $plan1s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
                $tui = $yk * $plan2s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
                $tui = $yk * $plan3s;
                用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
                $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
                continue;
            }else{
                continue;
            }
        }
        break;
    case "shangfen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (`time` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
            $tui = $liushui * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
            $tui = $liushui * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
            $tui = $liushui * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }else{
            continue;
        }
        break;
    case "xiafen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (`time` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
            $tui = $liushui * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
            $tui = $liushui * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
            $tui = $liushui * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
            continue;
        }else{
            continue;
        }
        break;
    }
}
$arr['success'] = true;
echo json_encode($arr);
exit();
}elseif($type == 'twotui'){
$time = $_POST['time'];
$time = explode(' - ', $time);
$mode = $_POST['mode'];
$plan1 = $_POST['plan1'];
$plan1s = $_POST['plan1s'];
$plan2 = $_POST['plan2'] == '-' ? '': $_POST['plan2'];
$plan2s = $_POST['plan2s'];
$plan3 = $_POST['plan3'] == '-' ? '': $_POST['plan3'];
$plan3s = $_POST['plan3s'];
if($plan1 != '' && $plan1s != ''){
    $plan1 = explode('-', $plan1);
    $plan1s = $plan1s / 100;
    if($plan2 != '' && $plan2s != ''){
        $plan2 = explode('-', $plan2);
        $plan2s = $plan2s / 100;
    }
    if($plan3 != '' && $plan3s != ''){
        $plan3 = explode('-', $plan3);
        $plan3s = $plan3s / 100;
    }
}else{
    echo json_encode(array("success" => false, "msg" => "方案没有填写!!"));
    exit();
}
$arr = array();
switch($mode){
case 'liushui': $arr['mode'] = '流水';
    break;
case "kuisun": $arr['mode'] = '亏损';
    break;
case "yingli": $arr['mode'] = '盈利';
    break;
case "shangfen": $arr['mode'] = '上分';
    break;
case "xiafen": $arr['mode'] = '下分';
    break;
}
select_query("fn_user", '*', "roomid = '{$_SESSION['agent_room']}' and jia = 'false'");
while($con = db_fetch_array()){
$cons[] = $con;
}
foreach($cons as $con){
switch($mode){
case 'liushui': $liushui = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $liushui += (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }else{
        continue;
    }
    break;
case "kuisun": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $z - $m;
    $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    if($yk < 0){
        $yk = abs($yk);
        if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
            $tui = $yk * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
            $tui = $yk * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
            $tui = $yk * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }else{
            continue;
        }
    }
    break;
case "yingli": $m = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $z - $m;
    $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    $m = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $z = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['agent_room']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$con['userid']}'");
    $yk = $yk + ($z - $m);
    if($yk > 0){
        if(($yk > $plan1[0] && $yk < $plan1[1]) && $plan1s != ''){
            $tui = $yk * $plan1s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }elseif(($yk > $plan2[0] && $yk < $plan2[1]) && $plan2s != ''){
            $tui = $yk * $plan2s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }elseif(($yk > $plan3[0] && $yk < $plan3[1]) && $plan3s != ''){
            $tui = $yk * $plan3s;
            用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
            $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $yk, 'money' => $tui);
            continue;
        }else{
            continue;
        }
    }
    break;
case "shangfen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '上分' and status = '已处理' and (`time` between '{$time[0]}' and '{$time[1]}')");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }else{
        continue;
    }
    break;
case "xiafen": $liushui = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['agent_room']}' and userid = '{$con['userid']}' and type = '下分' and status = '已处理' and (`time` between '{$time[1]}' and '{$time[0]}')");
    if(($liushui > $plan1[0] && $liushui < $plan1[1]) && $plan1s != ''){
        $tui = $liushui * $plan1s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan2[0] && $liushui < $plan2[1]) && $plan2s != ''){
        $tui = $liushui * $plan2s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }elseif(($liushui > $plan3[0] && $liushui < $plan3[1]) && $plan3s != ''){
        $tui = $liushui * $plan3s;
        用户_上分($con['userid'], $tui, $_SESSION['agent_room'], $time[0] . ' - ' . $time[1]);
        $arr['tuidata'][] = array('username' => $con['username'], 'userid' => $con['userid'], 'id' => $con['id'], 'mode' => $liushui, 'money' => $tui);
        continue;
    }else{
        continue;
    }
    break;
}
}
$arr['success'] = true;
echo json_encode($arr);
exit();
}
function 用户_上分($Userid, $Money, $room, $time){
update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
insert_query("fn_marklog", array("roomid" => $room, 'userid' => $Userid, 'type' => '上分', 'content' => $time . '系统退水', 'money' => $Money, 'addtime' => 'now()'));
}
?>