<?php include_once("../Public/config.php");
$BetGame = $_COOKIE['game'];
if($BetGame == 'xy28'){
    $BetTerm = get_query_val('fn_open', 'next_term', "`type` = '4' order by `term` desc limit 1");
    $time = (int)get_query_val('fn_lottery4', 'fengtime', array('roomid' => $_SESSION['roomid']));
    $djs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '4' order by `term` desc limit 1")) - time();
    $gametype = 4;
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'jnd28'){
    $BetTerm = get_query_val('fn_open', 'next_term', "`type` = '5' order by `term` desc limit 1");
    $time = (int)get_query_val('fn_lottery5', 'fengtime', array('roomid' => $_SESSION['roomid']));
    $djs = strtotime(get_query_val('fn_open', 'next_time', "`type` = '5' order by `term` desc limit 1")) - time();
    $gametype = 5;
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}
$delid = $_POST['id'];
$arr = array();
$cheterm = get_query_val('fn_pcorder', 'term', array('id' => $delid));
$chemoney = get_query_val('fn_pcorder', 'money', array('id' => $delid));
$chedan = get_query_val('fn_setting', 'setting_cancelbet', array('roomid' => $_SESSION['roomid'])) == 'open' ? true : false;
if($chedan){
    $arr['success'] = false;
    $arr['msg'] = "本房间禁止撤单！";
    echo json_encode($arr);
    exit();
}elseif($fengpan){
    $arr['success'] = false;
    $arr['msg'] = "当期[$BetTerm]已经截止投注，无法取消订单！";
    echo json_encode($arr);
    exit();
}elseif(get_query_val("fn_open", "code", array("term" => $cheterm, 'type' => $gametype)) != ""){
    $arr['success'] = false;
    $arr['msg'] = "您撤销的期号[$cheterm]已经开奖，无法撤单！\n如未结算请联系管理员!";
    echo json_encode($arr);
    exit();
}elseif(strtotime(get_query_val("fn_open", "next_time", array("next_term" => $cheterm, 'type' => $gametype))) - time() < 0){
    $arr['success'] = false;
    $arr['msg'] = "您撤销的期号[$cheterm]正在开奖，无法撤单！\n如未结算请联系管理员!";
    echo json_encode($arr);
    exit();
}elseif(get_query_val("fn_pcorder", "status", array("id" => $delid)) == '已撤单'){
    $arr['success'] = false;
    $arr['msg'] = "您选择的订单已经撤销,相关金额已经退回您的余额,请点击左侧菜单进行刷新!";
    echo json_encode($arr);
    exit();
}else{
    update_query("fn_pcorder", array("status" => "已撤单"), array("id" => $delid));
    update_query("fn_user", array("money" => "+=" . $chemoney), array('userid' => $_SESSION['userid'], 'roomid' => $_SESSION['roomid']));
    insert_query("fn_marklog", array("userid" => $_SESSION['userid'], 'type' => '上分', 'content' => '投注撤单退还', 'money' => $chemoney, 'roomid' => $_SESSION['roomid'], 'addtime' => 'now()'));
    $arr['success'] = true;
    $arr['msg'] = '撤单成功！';
    echo json_encode($arr);
    exit();
}
?>