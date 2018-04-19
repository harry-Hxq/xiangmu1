<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == '1'){
    $id = $_GET['userid'];
    $time = $_GET['time'] == '' ? date('Y-m-d'): $_GET['time'];
    $username = get_query_val('fn_user', 'username', array('id' => $id));
    $id = get_query_val('fn_user', 'userid', array('id' => $id));
    select_query("fn_marklog", '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
    while($con = db_fetch_array()){
        $cons[] = $con;
        $data['data'][] = array($con['id'], $username, $con['type'], $con['money'], $con['content'], $con['addtime']);
    }
    if (count($cons) == 0){
        $data['data'][] = null;
    }
    echo json_encode($data);
    exit();
}elseif($type == '2'){
    $id = $_GET['userid'];
    $time = $_GET['time'] == "" ? date('Y-m-d'): $_GET['time'];
    $code = $_GET['code'];
    $allmoney = 0;
    $allstatus = 0;
    $id = get_query_val('fn_user', 'userid', array('id' => $id));
    if($code == "" || $code == 'pk10' || $code == 'xyft'){
        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $game = strlen($con['term']) < 8 ? '北京赛车' : '幸运飞艇';
            if($code == 'pk10' && $game == '幸运飞艇')continue;
            if($code == 'xyft' && $game == '北京赛车')continue;
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], $game, $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if($code == '' || $code == 'jsmt'){
        select_query('fn_sscorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], '重庆时时彩', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if($code == '' || $code == 'xy28' || $code == 'jnd28'){
        select_query('fn_pcorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $game = (int)$con['term'] > 2000000 ? '加拿大28' : '幸运28';
            if($code == 'xy28' && $game == '加拿大28')continue;
            if($code == 'jnd28' && $game == '幸运28')continue;
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], $game, $con['term'], $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if($code == '' || $code == 'jsmt'){
        select_query('fn_mtorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], '极速摩托', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if($code == '' || $code == 'jssc'){
        select_query('fn_jsscorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], '极速赛车', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if($code == '' || $code == 'jsssc'){
        select_query('fn_jssscorder', '*', "roomid = '{$_SESSION['agent_room']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time} 23:59:59')");
        while($con = db_fetch_array()){
            $cons[] = $con;
            if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
            if($con['status'] > 0)$allstatus += $con['status'];
            $data['data'][] = array('#' . $con['id'], $con['username'], '极速时时彩', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
        }
    }
    if (count($cons) == 0){
        $data['data'][] = null;
    }
    $allstatus = $allstatus - $allmoney;
    $data['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
    $data['allstatus'] = sprintf("%.2f", substr(sprintf("%.3f", $allstatus), 0, -2));
    echo json_encode($data);
    exit();
}elseif($type == '3'){
    $term = $_GET['term'];
    $game = $_GET['game'];
    if($game == 'pk10' || $game == 'xyft'){
        select_query('fn_order', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        $allz = 0;
        $allm = 0;
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'cqssc'){
        select_query('fn_sscorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'xy28' || $game == 'jnd28'){
        select_query('fn_pcorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jsmt'){
        select_query('fn_mtorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jssc'){
        select_query('fn_jsscorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }elseif($game == 'jsssc'){
        select_query('fn_jssscorder', '*', "roomid = {$_SESSION['agent_room']} and term = {$term}");
        while($con = db_fetch_array()){
            $cons[] = $con;
            $arr['data'][] = array($con['id'], $con['username'], $con['content'], $con['money'], $con['addtime'], $con['status']);
            $allm += (int)$con['money'];
            if((int)$con['status'] > 0){
                $allz += (int)$con['status'];
            }
        }
        $arr['allm'] = $allm;
        $arr['allz'] = number_format($allz - $allm, 2);
        if(count($cons) == 0){
            $arr['data'][0] = 'null';
        }
        echo json_encode($arr);
        exit;
    }
}
?>