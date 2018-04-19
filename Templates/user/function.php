<?php
 function getinfo($userid){
    $time = array();
    $time[0] = date('Y-m-d') . " 00:00:00";
    $time[1] = date('Y-m-d') . " 23:59:59";
    $sf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '上分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $xf = (int)get_query_val('fn_upmark', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and type = '下分' and status = '已处理' and (time between '{$time[0]}' and '{$time[1]}')");
    $allm = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $allz = get_query_val('fn_order', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $sscm = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $pcm = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $mtm = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $jsscm = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $jssscm = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = '{$_SESSION['roomid']}' and userid = '{$userid}' and (`addtime` between '{$time[0]}' and '{$time[1]}')  and (status > 0 or status < 0)");
    $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = '{$_SESSION['roomid']}' and (`addtime` between '{$time[0]}' and '{$time[1]}') and status > 0 and userid = '{$userid}'");
    $sscyk = $sscz - $sscm;
    $jssscyk = $jssscz - $jssscm;
    $jsscyk = $jsscz - $jsscm;
    $mtyk = $mtz - $mtm;
    $pcyk = $pcz - $pcm;
    $yk = $allz - $allm;
    $yk += $pcyk + $mtyk + $sscyk + $jsscyk + $jssscyk;
    $allm += $pcm + $mtm + $sscm + $jsscm + $jssscm;
    $yk = round($yk, 2);
    return array("yk" => $yk, 'liu' => $allm);
}
function getextend($userid, $time){
    $liushui = 0;
    $yk = 0;
    $money = 0;
    $sf = 0;
    $time = explode(' - ', $time);
    if(count($time) == 2){
        $ordersql = " and (`addtime` between '{$time[0]}' and '{$time[1]}')";
        $marksql = " and (`time` between '{$time[0]}' and '{$time[1]}')";
    }else{
        $ordersql = '';
        $marksql = '';
    }
    select_query("fn_user", '*', "roomid = {$_SESSION['roomid']} and agent = '{$userid}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)" . $ordersql);
        $z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0" . $ordersql);
        $y = $z - $l;
        $pcy = $pcz - $pcl;
        $sscy = $sscz - $sscl;
        $mty = $mtz - $mtl;
        $jsscy = $jsscz - $jsscl;
        $jssscy = $jssscz - $jssscl;
        $s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` = '已处理'" . $marksql);
        $liushui += ($l + $pcl + $sscl + $mtl + $jsscl + $jssscl);
        $yk += ($y + $pcy + $sscy + $mty + $jsscy + $jssscy);
        $money += $con['money'];
        $sf += $s;
    }
    $arr = array('liu' => $liushui, 'yk' => sprintf("%.2f", substr(sprintf("%.3f", $yk), 0, -2)), 'money' => $money, 'pay' => $sf);
    return $arr;
}
function getorder($userid, $time){
    $time2 = date('Y-m-d');
    switch($time){
    case 1: $time = date('Y-m-d');
        break;
    case 7: $time = date('Y-m-d', strtotime('-1 day'));
        $time2 = date('Y-m-d', strtotime("-1 day"));
        break;
    case 30: $time = date('Y-m-d', strtotime('-30 day'));
        break;
    }
    $id = $userid;
    $code = "";
    $allmoney = 0;
    $allstatus = 0;
    if($code == "" || $code == 'pk10' || $code == 'xyft'){
    select_query('fn_order', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
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
select_query('fn_sscorder', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
while($con = db_fetch_array()){
    $cons[] = $con;
    if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
    if($con['status'] > 0)$allstatus += $con['status'];
    $data['data'][] = array('#' . $con['id'], $con['username'], '重庆时时彩', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
}
}
if($code == '' || $code == 'xy28' || $code == 'jnd28'){
select_query('fn_pcorder', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
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
select_query('fn_mtorder', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
while($con = db_fetch_array()){
    $cons[] = $con;
    if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
    if($con['status'] > 0)$allstatus += $con['status'];
    $data['data'][] = array('#' . $con['id'], $con['username'], '极速摩托', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
}
}
if($code == '' || $code == 'jssc'){
select_query('fn_jsscorder', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
while($con = db_fetch_array()){
    $cons[] = $con;
    if($con['status'] != '已撤单' && $con['status'] != '未结算')$allmoney += (int)$con['money'];
    if($con['status'] > 0)$allstatus += $con['status'];
    $data['data'][] = array('#' . $con['id'], $con['username'], '极速赛车', $con['term'], $con['mingci'] . '/' . $con['content'], $con['money'], $con['addtime'], $con['status']);
}
}
if($code == '' || $code == 'jsssc'){
select_query('fn_jssscorder', '*', "roomid = '{$_SESSION['roomid']}' and userid = '{$id}' and (`addtime` between '{$time} 00:00:00' and '{$time2} 23:59:59')");
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
return $data;
}
function getxia($userid){
$allmoney = 0;
$allliu = 0;
$allyk = 0;
$alls = 0;
select_query("fn_user", '*', "roomid = {$_SESSION['roomid']} and agent = '{$userid}'");
while($con = db_fetch_array()){
$cons[] = $con;
}
foreach($cons as $con){
$l = (int)get_query_val('fn_order', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$pcl = (int)get_query_val('fn_pcorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$sscl = (int)get_query_val('fn_sscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$mtl = (int)get_query_val('fn_mtorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$jsscl = (int)get_query_val('fn_jsscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$jssscl = (int)get_query_val('fn_jssscorder', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and (`status` > 0 or `status` < 0)");
$z = get_query_val('fn_order', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
$pcz = get_query_val('fn_pcorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
$sscz = get_query_val('fn_sscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
$mtz = get_query_val('fn_mtorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
$jsscz = get_query_val('fn_jsscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
$jssscz = get_query_val('fn_jssscorder', 'sum(`status`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` > 0");
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
$s = get_query_val('fn_upmark', 'sum(`money`)', "roomid = {$_SESSION['roomid']} and userid = '{$con['userid']}' and `status` = '已处理'");
$alls += $s;
$arr['data'][] = array($con['id'], "<img src='{$con['headimg']}' style='width:30px;height:30px'> ", $con['username'], $con['money'], "$liushui", $yk, $s == "" ? '0.00' : $s, date('Y-m-d H:i:s', $con['statustime']));
$allmoney += $con['money'];
}
$arr['allmoney'] = sprintf("%.2f", substr(sprintf("%.3f", $allmoney), 0, -2));
$arr['allyk'] = sprintf("%.2f", substr(sprintf("%.3f", $allyk), 0, -2));
$arr['alls'] = $alls;
$arr['allliu'] = $allliu;
return $arr;
}
?>