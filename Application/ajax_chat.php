<?php
include_once("../Public/config.php");
$type = $_GET['type'];
$BetGame = $_COOKIE['game'];
switch($type){
case 'first': $arr = array();
    select_query("fn_chat", '*', "roomid = {$_SESSION['roomid']} and game = '{$BetGame}' order by id desc limit 0,50");
    while($x = db_fetch_array()){
        if($x['userid'] == $_SESSION['userid']){
            $type = 'U2';
        }else{
            $type = $x['type'];
        }
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => $x['addtime'], 'type' => $type, 'game' => $BetGame, 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "update": $arr = array();
    $chatid = $_GET['id'];
    select_query("fn_chat", '*', "roomid = {$_SESSION['roomid']} and game = '{$BetGame}' and id>$chatid order by id asc");
    while($x = db_fetch_array()){
        if($x['userid'] == $_SESSION['userid'])continue;
        $arr[] = array('nickname' => $x['username'], 'headimg' => $x['headimg'], 'content' => $x['content'], 'addtime' => $x['addtime'], 'type' => $x['type'], 'game' => $BetGame, 'id' => $x['id']);
    }
    echo json_encode($arr);
    break;
case "send": $nickname = $_SESSION['username'];
    $content = $_POST['content'];
    $headimg = $_SESSION['headimg'];
    if($BetGame == 'pk10'){
        if(get_query_val('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'xyft'){
        if(get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'cqssc'){
        if(get_query_val('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'xy28'){
        if(get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jnd28'){
        if(get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jsmt'){
        if(get_query_val('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jssc'){
        if(get_query_val('fn_lottery7', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jsssc'){
        if(get_query_val('fn_lottery8', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
    }
    if($BetGame == 'pk10'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['roomid']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 1 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'xyft'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['roomid']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 2 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'cqssc'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['roomid']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 3 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'xy28'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 4 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery4', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 4 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'jnd28'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 5 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery5', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 5 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'jsmt'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 6 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery6', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 6 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'jssc'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 7 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery7', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 7 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'jsssc'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 8 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery8', 'fengtime');
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 8 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'feng'){
        $fengpan = true;
    }
    if(substr($content, 0, 1) == '@'){
        $type = "U1";
    }else{
        $type = "U3";
    }
    if(get_query_val("fn_ban", "id", array("roomid" => $_SESSION['roomid'], 'userid' => $_SESSION['userid'])) != ""){
        echo json_encode(array('success' => false, 'msg' => '您已经被该房间禁言！无法发言与投注！'));
        break;
    }elseif(!wordkeys($content)){
        echo json_encode(array('success' => false, 'msg' => '您发送的内容内含有屏蔽词汇,请删除后重试.'));
        break;
    }
    if($type == 'U3'){
        if(substr($content, 0, 6) == '上分'){
            $fenshuchange = true;
            $sfmoney = substr($content, 6);
            if((int)$sfmoney > 0)插入上分($_SESSION['username'], $_SESSION['userid'], $sfmoney);
        }elseif(substr($content, 0, 3) == '上'){
            $fenshuchange = true;
            $sfmoney = substr($content, 3);
            if((int)$sfmoney > 0)插入上分($_SESSION['username'], $_SESSION['userid'], $sfmoney);
        }elseif(substr($content, 0, 3) == '查'){
            $fenshuchange = true;
            $sfmoney = substr($content, 3);
            if((int)$sfmoney > 0)插入上分($_SESSION['username'], $_SESSION['userid'], $sfmoney);
        }elseif(substr($content, 0, 6) == '下分'){
            $fenshuchange = true;
            $xfmoney = substr($content, 6);
            if((int)$xfmoney > 0)插入下分($_SESSION['username'], $_SESSION['userid'], $xfmoney);
        }elseif(substr($content, 0, 3) == '下'){
            $fenshuchange = true;
            $xfmoney = substr($content, 3);
            if((int)$xfmoney > 0)插入下分($_SESSION['username'], $_SESSION['userid'], $xfmoney);
        }elseif(substr($content, 0, 3) == '回'){
            $fenshuchange = true;
            $xfmoney = substr($content, 3);
            if((int)$xfmoney > 0)插入下分($_SESSION['username'], $_SESSION['userid'], $xfmoney);
        }else{
            $fenshuchange = false;
        }
        if($content == "取消"){
            CancelBet($_SESSION['userid'], $BetTerm, $BetGame, $fengpan);
            echo json_encode(array("success" => true, "content" => $content));
            insert_query("fn_chat", array("username" => $nickname, 'content' => $content, 'addtime' => date('H:i:s'), 'game' => $_COOKIE['game'], 'headimg' => $headimg, 'type' => $type, 'userid' => $_SESSION['userid'], 'roomid' => $_SESSION['roomid']));
            break;
        }
    }
    if($type == 'U3' && $fenshuchange == false && ($BetGame == 'xy28' || $BetGame == 'jnd28')){
        $co = addPCBet($_SESSION['userid'], $_SESSION['username'], $_SESSION['headimg'], $content, $BetTerm, $fengpan);
    }elseif($type == 'U3' && $fenshuchange == false && ($BetGame == 'cqssc' || $BetGame == 'jsssc')){
        $co = addSSCBet($_SESSION['userid'], $_SESSION['username'], $_SESSION['headimg'], $content, $BetTerm, $fengpan);
    }elseif($type == 'U3' && $fenshuchange == false){
        $co = addBet($_SESSION['userid'], $_SESSION['username'], $_SESSION['headimg'], $content, $BetTerm, $fengpan);
    }
    if(get_query_val("fn_setting", "setting_ischat", array("roomid" => $_SESSION['roomid'])) == 'open' && !$co && !$fenshuchange){
        echo json_encode(array('success' => false, 'msg' => "温馨提示: 您的投注格式不正确!或游戏已经封盘!"));
        break;
    }else{
        echo json_encode(array("success" => true, "content" => $content));
        insert_query("fn_chat", array("username" => $nickname, 'content' => $content, 'addtime' => date('H:i:s'), 'game' => $_COOKIE['game'], 'headimg' => $headimg, 'type' => $type, 'userid' => $_SESSION['userid'], 'roomid' => $_SESSION['roomid']));
    }
    break;
}
function sum_betmoney($table, $mc, $cont, $user, $term){
$re = get_query_val($table, 'sum(`money`)', array('userid' => $user, 'term' => $term, 'mingci' => $mc, 'content' => $cont));
return (int)$re;
}
function str_replace_once($needle, $replace, $haystack){
$pos = strpos($haystack, $needle);
if ($pos === false){
    return $haystack;
}
return substr_replace($haystack, $replace, $pos, strlen($needle));
}
function runrobot($BetGame){
$open = get_query_val('fn_setting', 'setting_runrobot', array('roomid' => $_SESSION['roomid']));
if($open != 'true'){
    return;
}
if($BetGame == 'pk10'){
    if(get_query_val('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}elseif($BetGame == 'xyft'){
    if(get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}elseif($BetGame == 'cqssc'){
    if(get_query_val('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}elseif($BetGame == 'xy28'){
    if(get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}elseif($BetGame == 'jnd28'){
    if(get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}elseif($BetGame == 'jsmt'){
    if(get_query_val('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$BetGame = 'feng';
}
if($BetGame == 'pk10'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['roomid']));
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 1 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'xyft'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['roomid']));
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 2 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'cqssc'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['roomid']));
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 3 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'xy28'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 4 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery4', 'fengtime');
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 4 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'jnd28'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 5 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery5', 'fengtime');
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 5 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'jsmt'){
    $BetTerm = get_query_val('fn_open', 'next_term', "type = 6 order by term desc limit 1");
    $time = (int)get_query_val('fn_lottery6', 'fengtime');
    $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 6 order by term desc limit 1')) - time();
    if($djs < $time){
        $fengpan = true;
    }else{
        $fengpan = false;
    }
}elseif($BetGame == 'feng'){
    $fengpan = true;
}
if(!$fengpan){
    $robots = get_query_vals('fn_robots', '*', "roomid = {$_SESSION['roomid']} and game = '{$BetGame}' order by rand() desc limit 1");
    $headimg = $robots['headimg'];
    $name = $robots['name'];
    $plan = $robots['plan'];
    $plan = explode('|', $plan);
    if($headimg == '' || $name == '' || $plan == '')return;
    $use = rand(0, count($plan)-1);
    $plan = get_query_val('fn_robotplan', 'content', array('id' => $plan[$use]));
    if(preg_match("/{随机名次}/", $plan)){
        $i2 = substr_count($plan, '{随机名次}');
        for($i = 0;$i < $i2;$i++){
            $plan = str_replace_once("{随机名次}", rand(0, 9), $plan);
        }
    }
    if(preg_match("/{随机特码}/", $plan)){
        $i2 = substr_count($plan, '{随机特码}');
        for($i = 0;$i < $i2;$i++){
            $plan = str_replace_once("{随机特码}", rand(0, 9), $plan);
        }
    }
    if(preg_match("/{随机双面}/", $plan)){
        $val = rand(1, 4);
        if($val == 1){
            $val = '大';
        }elseif($val == 2){
            $val = '小';
        }elseif($val == 3){
            $val = '单';
        }elseif($val == 4){
            $val = '双';
        }
        $plan = str_replace('{随机双面}', $val, $plan);
    }
    if(preg_match("/{随机龙虎}/", $plan)){
        $val = rand(1, 2);
        if($val == 1){
            $val = '龙';
        }elseif($val == 2){
            $val = '虎';
        }
        $plan = str_replace('{随机龙虎}', $val, $plan);
    }
    if(preg_match("/{随机极值}/", $plan)){
        $val = rand(1, 2);
        if($val == 1){
            $val = '极大';
        }elseif($val == 2){
            $val = '极小';
        }
        $plan = str_replace('{随机极值}', $val, $plan);
    }
    if(preg_match("/{随机组合1}/", $plan)){
        $val = rand(1, 2);
        if($val == 1){
            $val = '大单';
        }elseif($val == 2){
            $val = '大双';
        }
        $plan = str_replace('{随机组合1}', $val, $plan);
    }
    if(preg_match("/{随机组合2}/", $plan)){
        $val = rand(1, 2);
        if($val == 1){
            $val = '小单';
        }elseif($val == 2){
            $val = '小双';
        }
        $plan = str_replace('{随机组合2}', $val, $plan);
    }
    if(preg_match("/{随机数字}/", $plan)){
        $i2 = substr_count($plan, '{随机数字}');
        for($i = 0;$i < $i2;$i++){
            $plan = str_replace_once("{随机数字}", rand(0, 27), $plan);
        }
    }
    if(preg_match("/{随机和值}/", $plan)){
        $i2 = substr_count($plan, '{随机和值}');
        for($i = 0;$i < $i2;$i++){
            $plan = str_replace_once("{随机和值}", rand(3, 19), $plan);
        }
    }
    if(preg_match("/{随机特殊}/", $plan)){
        $val = rand(1, 3);
        if($val == 1){
            $val = '豹子';
        }elseif($val == 2){
            $val = '对子';
        }elseif($val == 3){
            $val = '顺子';
        }
        $plan = str_replace('{随机特殊}', $val, $plan);
    }
    if(preg_match("/{随机金额1}/", $plan)){
        $plan = str_replace('{随机金额1}', rand(20, 300), $plan);
    }
    if(preg_match("/{随机金额2}/", $plan)){
        $plan = str_replace('{随机金额2}', rand(300, 1000), $plan);
    }
    if(preg_match("/{随机金额3}/", $plan)){
        $plan = str_replace('{随机金额3}', rand(1000, 3000), $plan);
    }
    insert_query("fn_chat", array("userid" => "robot", "username" => $name, 'headimg' => $headimg, 'content' => $plan, 'addtime' => date('H:i:s'), 'game' => $BetGame, 'roomid' => $_SESSION['roomid'], 'type' => 'U3'));
    if(get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['roomid'])) == 'open'){
        管理员喊话("@$name,投注成功！请选择左侧菜单核对投注！");
    }
}
}
function wordkeys($content){
$keys = get_query_val('fn_setting', 'setting_wordkeys', array('roomid' => $_SESSION['roomid']));
$arr = explode("|", $keys);
foreach($arr as $con){
    if($con == ""){
        continue;
    }
    if(preg_match("/$con/", $content)){
        return false;
    }
}
return true;
}
function 文本_逐字分割($str, $split_len = 1){
if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)return FALSE;
$len = mb_strlen($str, 'UTF-8');
if ($len <= $split_len)return array($str);
preg_match_all("/.{" . $split_len . '}|[^x00]{1,' . $split_len . '}$/us', $str, $ar);
return $ar[0];
}
function 前中后分割($str){
$arr = 文本_逐字分割($str);
$new = array();
foreach($arr as $ii){
    if($ii == "前"){
        $new[] = "前三";
        continue;
    }
    if($ii == "中"){
        $new[] = "中三";
        continue;
    }
    if($ii == "后"){
        $new[] = "后三";
        continue;
    }
    continue;
}
return $new;
}
function 和值分割($str){
$arr = 文本_逐字分割($str);
$new = array();
$ii_1_b = true;
$ii_1 = '';
foreach($arr as $ii){
    if(!$ii_1_b && $ii_1 == "1")$ii = "1" . $ii;
    $ii_1 = $ii;
    if($ii_1_b)$ii_1_b = false;
    if($ii == "1")continue;
    array_push($new, $ii);
}
return $new;
}
function 查询用户余额($Userid){
return (int)get_query_val('fn_user', 'money', array('userid' => $Userid, 'roomid' => $_SESSION['roomid']));
}
function 用户_下分($Userid, $Money){
update_query('fn_user', array('money' => '-=' . $Money), array('userid' => $Userid, 'roomid' => $_SESSION['roomid']));
insert_query("fn_marklog", array("userid" => $Userid, 'type' => '下分', 'content' => '彩票投注', 'money' => $Money, 'roomid' => $_SESSION['roomid'], 'addtime' => 'now()'));
}
function 用户_上分($Userid, $Money){
update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $_SESSION['roomid']));
insert_query("fn_marklog", array("userid" => $Userid, 'type' => '上分', 'content' => '投注撤单退还', 'money' => $Money, 'roomid' => $_SESSION['roomid'], 'addtime' => 'now()'));
}
function 管理员喊话($Content){
$headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $_SESSION['roomid']));
insert_query("fn_chat", array("userid" => "system", "username" => "机器人", "game" => $_COOKIE['game'], 'headimg' => $headimg, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S3', 'roomid' => $_SESSION['roomid']));
}
function 插入上分($username, $userid, $money){
$jia = get_query_val('fn_user', 'jia', array('userid' => $userid));
insert_query("fn_upmark", array("userid" => $userid, 'headimg' => $_SESSION['headimg'], 'username' => $username, 'type' => '上分', 'money' => $money, 'status' => '未处理', 'time' => 'now()', 'game' => $_COOKIE['game'], 'roomid' => $_SESSION['roomid'], 'jia' => $jia));
}
function 插入下分($username, $userid, $money){
$m = (int)get_query_val('fn_user', 'money', array('roomid' => $_SESSION['roomid'], 'userid' => $userid));
if(($m - (int)$money) < 0){
    管理员喊话("@$username,您的分数不够回这么多分!", $game);
    return;
}
$jia = get_query_val('fn_user', 'jia', array('userid' => $userid));
insert_query("fn_upmark", array("userid" => $userid, 'headimg' => $_SESSION['headimg'], 'username' => $username, 'type' => '下分', 'money' => $money, 'status' => '未处理', 'time' => 'now()', 'game' => $_COOKIE['game'], 'roomid' => $_SESSION['roomid'], 'jia' => $jia));
if(get_query_val("fn_setting", "setting_downmark", array("roomid" => $_SESSION['roomid'])) == 'true'){
    update_query('fn_user', array('money' => '-=' . $money), array('userid' => $userid, 'roomid' => $_SESSION['roomid']));
    insert_query("fn_marklog", array("roomid" => $_SESSION['roomid'], 'userid' => $userid, 'type' => '下分', 'content' => '系统自动同意下分' . $money, 'money' => $money, 'addtime' => 'now()'));
    $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['roomid']));
    insert_query("fn_chat", array("userid" => "system", "username" => "管理员", "game" => $_COOKIE['game'], 'headimg' => $headimg, 'content' => "@{$username}, 您的下分请求已接收,请稍后查账!", 'addtime' => date('H:i:s'), 'type' => 'S1', 'roomid' => $_SESSION['roomid']));
}
}
function CancelBet($userid, $term, $game, $fengpan){
$chedan = get_query_val('fn_setting', 'setting_cancelbet', array('roomid' => $_SESSION['roomid'])) == 'open' ? true : false;
if($chedan){
    return;
}else{
    if($fengpan){
        管理员喊话 ("@" . $_SESSION['username'] . " ,[$term]期已经停止投注！无法取消！");
        return false;
    }
    switch($game){
    case 'xy28': $table = "fn_pcorder";
        break;
    case "jnd28": $table = "fn_pcorder";
        break;
    case "jsmt": $table = "fn_jsmtorder";
        break;
    case "jssc": $table = "fn_jsscorder";
        break;
    case "jsssc": $table = "fn_jssscorder";
        break;
    case "cqssc": $table = "fn_sscorder";
        break;
    case "pk10": $table = "fn_order";
        break;
    case "xyft": $table = "fn_order";
        break;
    }
    $all = (int)get_query_val($table, 'sum(`money`)', "userid = '$userid' and term = '$term' and status = '未结算' and roomid = {$_SESSION['roomid']}");
    update_query($table, array('status' => '已撤单'), "userid = '$userid' and term = '$term' and roomid = {$_SESSION['roomid']}");
    用户_上分($userid, $all);
    管理员喊话("@{$_SESSION['username']} ,[$term]期投注已经退还!");
}
}
function addBet($userid, $nickname, $headimg, $content, $addQihao, $fengpan){
if($fengpan){
    管理员喊话 ("@" . $nickname . " ,[$addQihao]期已经停止投注！下注无效！");
    return false;
}
$content = str_replace("冠亚和", "和", $content);
$content = str_replace("冠亚", "和", $content);
$content = str_replace("冠军", "1/", $content);
$content = str_replace("亚军", "2/", $content);
$content = str_replace("冠", "1/", $content);
$content = str_replace("亚", "2/", $content);
$content = str_replace("一", "1/", $content);
$content = str_replace("二", "2/", $content);
$content = str_replace("三", "3/", $content);
$content = str_replace("四", "4/", $content);
$content = str_replace("五", "5/", $content);
$content = str_replace("六", "6/", $content);
$content = str_replace("七", "7/", $content);
$content = str_replace("八", "8/", $content);
$content = str_replace("九", "9/", $content);
$content = str_replace("十", "0/", $content);
$content = str_replace(".", "/", $content);
$content = preg_replace("/[位名各-]/u", "/", $content);
$content = preg_replace("/(和|合|H|h)\//u", "$1", $content);
$content = preg_replace("/[和合Hh]/u", "和/", $content);
$content = preg_replace("/(大单|小单|大双|小双|大|小|单|双|龙|虎)\//u", "$1", $content);
$content = preg_replace("/\/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u", "$1", $content);
$content = preg_replace("/(大单|小单|大双|小双|大|小|单|双|龙|虎)/u", "/$1/", $content);
if($_COOKIE['game'] == 'pk10'){
    $table = 'fn_lottery1';
}elseif($_COOKIE['game'] == 'xyft'){
}elseif($_COOKIE['game'] == 'jsmt'){
}elseif($_COOKIE['game'] == 'jssc'){
}
switch($_COOKIE['game']){
case 'pk10': $table = 'fn_lottery1';
    $ordertable = "fn_order";
    break;
case "xyft": $table = 'fn_lottery2';
    $ordertable = "fn_order";
    break;
case "jsmt": $table = 'fn_lottery6';
    $ordertable = "fn_mtorder";
    break;
case "jssc": $table = 'fn_lottery7';
    $ordertable = "fn_jsscorder";
    break;
}
$dx_min = get_query_val($table, 'daxiao_min', array('roomid' => $_SESSION['roomid']));
$dx_max = get_query_val($table, 'daxiao_max', array('roomid' => $_SESSION['roomid']));
$ds_min = get_query_val($table, 'danshuang_min', array('roomid' => $_SESSION['roomid']));
$ds_max = get_query_val($table, 'danshuang_max', array('roomid' => $_SESSION['roomid']));
$lh_min = get_query_val($table, 'longhu_min', array('roomid' => $_SESSION['roomid']));
$lh_max = get_query_val($table, 'longhu_max', array('roomid' => $_SESSION['roomid']));
$tm_min = get_query_val($table, 'tema_min', array('roomid' => $_SESSION['roomid']));
$tm_max = get_query_val($table, 'tema_max', array('roomid' => $_SESSION['roomid']));
$hz_min = get_query_val($table, 'he_min', array('roomid' => $_SESSION['roomid']));
$hz_max = get_query_val($table, 'he_max', array('roomid' => $_SESSION['roomid']));
$zh_min = get_query_val($table, 'zuhe_min', array('roomid' => $_SESSION['roomid']));
$zh_max = get_query_val($table, 'zuhe_max', array('roomid' => $_SESSION['roomid']));
$zym_8 = get_query_val('fn_user', 'jia', array('userid' => $userid, 'roomid' => $_SESSION['roomid'])) == 'true' ? 'true' : 'false';
$touzhu = false;
$A = explode(" ", $content);
$zym_2 = "";
foreach($A as $ai){
$ai = str_replace(" ", "", $ai);
if(empty($ai))continue;
if(substr($ai, 0, 1) == '/')$ai = '1' . $ai;
$b = explode("/", $ai);
if(count($b) == 2){
$ai = '1/' . $ai;
$b = explode("/", $ai);
}
if(count($b) != 3)continue;
if($b[0] == "" || $b[1] == "" || (int)$b[2] < 1)continue;
$zym_9 = 查询用户余额 ($userid);
$zym_10 = $b[0];
$zym_6 = $b[1];
$zym_5 = (int)$b[2];
if($zym_6 == '和'){
管理员喊话 ("@" . $nickname . " ,下注格式出错！冠亚和值下注格式为:和3/100");
continue;
}
if($zym_10 == '和'){
if($zym_6 == "大单" || $zym_6 == "大双" || $zym_6 == "小双" || $zym_6 == "小单"){
    管理员喊话 ("@" . $nickname . " ,下注格式出错！冠亚和值无此类型下注！");
    continue;
}
if($zym_6 == "大" || $zym_6 == "小" || $zym_6 == "单" || $zym_6 == "双"){
    if((int)$zym_9 < (int)$zym_5){
        $zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        continue;
    }elseif($zym_5 < $hz_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $hz_max){
        $zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }
    用户_下分($userid, $zym_5);
    insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
    $touzhu = true;
    continue;
}
$zym_6_分割 = 和值分割 ($zym_6);
foreach($zym_6_分割 as $ii){
    if($ii < 3 || $ii > 19){
        管理员喊话 ("@" . $nickname . " ,下注格式出错！冠亚和值为3 - 19！入单失败！");
        break;
    }
    if(!is_numeric($ii)){
        continue;
    }elseif((int)$zym_9 < count($zym_6_分割) * (int)$zym_5){
        $zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        break;
    }elseif($zym_5 < $hz_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $hz_max){
        $zym_2 .= $zym_10 . "/" . $ii . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }
    $touzhu = true;
    用户_下分 ($userid, $zym_5);
    insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $ii, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
    continue;
}
continue;
}
if($zym_6 == "大单" || $zym_6 == "大双" || $zym_6 == "小双" || $zym_6 == "小单"){
$zym_10_分割 = 文本_逐字分割 ($zym_10);
foreach($zym_10_分割 as $ii){
    if(!is_numeric($ii)){
        continue;
    }elseif($zym_9 < count($zym_10_分割) * (int)$zym_5){
        $zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        break;
    }elseif($zym_5 < $zh_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $zh_max){
        $zym_2 .= $zym_10 . "/" . $ii . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }
    $touzhu = true;
    用户_下分($userid, $zym_5);
    insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $ii, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}
continue;
}
if($zym_6 == "大" || $zym_6 == "小" || $zym_6 == "单" || $zym_6 == "双" || $zym_6 == "龙" || $zym_6 == "虎"){
$zym_10_分割 = 文本_逐字分割 ($zym_10);
foreach ($zym_10_分割 as $ii){
    if(!is_numeric($ii)){
        continue;
    }elseif($zym_9 < count($zym_10_分割) * (int)$zym_5){
        $zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        break;
    }elseif($zym_5 < $dx_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $dx_max && $zym_6 == "大"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }elseif($zym_5 < $dx_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $dx_max && $zym_6 == "小"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }elseif($zym_5 < $ds_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $ds_max && $zym_6 == "单"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }elseif($zym_5 < $ds_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $ds_max && $zym_6 == "双"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }elseif($zym_5 < $lh_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $lh_max && $zym_6 == "龙"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }elseif($zym_5 < $lh_min || sum_betmoney($ordertable, $ii, $zym_6, $userid, $addQihao) + $zym_5 > $lh_max && $zym_6 == "虎"){
        $zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }
    if((int)$ii > 5 && $zym_6 == '龙' || (int)$ii > 5 && $zym_6 == '虎'){
        管理员喊话("@{$nickname},龙虎投注仅限1~5名！");
        continue;
    }
    $touzhu = true;
    用户_下分($userid, $zym_5);
    insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $ii, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}
continue;
}
$zym_6_分割 = 文本_逐字分割 ($zym_6);
$zym_10_分割 = 文本_逐字分割 ($zym_10);
foreach ($zym_10_分割 as $ii){
if($zym_9 < count($zym_10_分割) * count($zym_6_分割) * (int)$zym_5){
    $zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
    break;
}else if(!is_numeric($ii)){
    continue;
}
foreach ($zym_6_分割 as $iii){
    if(!is_numeric($iii)){
        continue;
    }else if($zym_5 < $tm_min || sum_betmoney($ordertable, $ii, $iii, $userid, $addQihao) + $zym_5 > $tm_max){
        $zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
        $chaozhu = true;
        continue;
    }
    $touzhu = true;
    用户_下分($userid, $zym_5);
    insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $ii, 'content' => $iii, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}
}
}
if($zym_2 != ""){
if($chaozhu){
管理员喊话("@{$nickname},您的:{$zym_2}未接<br>您的投注已超出限制！<br>本房投注限制如下:<br>大小最低{$dx_min}起,最高{$dx_max}<br>单双最低{$ds_min}起,最高{$ds_max}<br>龙虎最低{$lh_min}起,最高{$lh_max}<br>特码最低{$tm_min}起,最高{$tm_max}<br>和值最低{$hz_min}起,最高{$hz_max}<br>------------<br>最高投注均为已下注总注");
return true;
}else{
管理员喊话("@{$nickname},您的:{$zym_2}未接，您的余额：" . 查询用户余额($userid));
return true;
}
}elseif(get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['roomid'])) == 'open' && $touzhu == true){
管理员喊话("@$nickname,投注成功！请选择左侧菜单核对投注！");
return true;
}elseif($touzhu){
return true;
}
return false;
}
function addPCBet($userid, $nickname, $headimg, $content, $addQihao, $fengpan){
if($fengpan){
管理员喊话 ("@" . $nickname . " ,[$addQihao]期已经停止投注！下注无效！");
return false;
}
$content = str_replace(".", "/", $content);
$content = preg_replace("/[点草艹操,-]/u", "/", $content);
$content = preg_replace("/(极大|极小|豹子|对子|顺子|大单|大双|小单|小双|大|小|单|双|哈)\//u", "$1", $content);
$content = preg_replace("/(极大|极小|豹子|对子|顺子|大单|大双|小单|小双|大|小|单|双|哈)/u", "$1/", $content);
switch($_COOKIE['game']){
case 'xy28': $table = 'fn_lottery4';
$ordertable = 'fn_pcorder';
break;
case "jnd28": $table = 'fn_lottery5';
$ordertable = 'fn_pcorder';
break;
}
$zym_17_min = (int)get_query_val($table, 'danzhu_min', array('roomid' => $_SESSION['roomid']));
$zym_16_max = (int)get_query_val($table, 'shuzi_max', array('roomid' => $_SESSION['roomid']));
$zym_15_max = (int)get_query_val($table, 'dxds_max', array('roomid' => $_SESSION['roomid']));
$zym_19_max = (int)get_query_val($table, 'zuhe_max', array('roomid' => $_SESSION['roomid']));
$zym_11_max = (int)get_query_val($table, 'jidx_max', array('roomid' => $_SESSION['roomid']));
$zym_20_max = (int)get_query_val($table, 'baozi_max', array('roomid' => $_SESSION['roomid']));
$zym_18_max = (int)get_query_val($table, 'duizi_max', array('roomid' => $_SESSION['roomid']));
$zym_13_max = (int)get_query_val($table, 'shunzi_max', array('roomid' => $_SESSION['roomid']));
$zym_12_max = (int)get_query_val($table, 'zongzhu_max', array('roomid' => $_SESSION['roomid']));
$zym_4 = get_query_val($table, 'setting_shazuhe', array('roomid' => $_SESSION['roomid']));
$zym_3 = get_query_val($table, 'setting_fanxiangzuhe', array('roomid' => $_SESSION['roomid']));
$zym_1 = get_query_val($table, 'setting_tongxiangzuhe', array('roomid' => $_SESSION['roomid']));
$zym_14_例外 = (int)get_query_val($table, 'setting_liwai', array('roomid' => $_SESSION['roomid']));
$touzhu = false;
$chaozhu = false;
$jinzhi = false;
$A = explode(' ', $content);
$zym_8 = get_query_val('fn_user', 'jia', array('userid' => $userid, 'roomid' => $_SESSION['roomid'])) == 'true' ? 'true' : 'false';
$zym_2 = "";
foreach($A as $i){
$i = str_replace(" ", "", $i);
if(empty($i))continue;
$b = explode('/', $i);
$zym_9 = 查询用户余额 ($userid);
if(count($b) == 3 && $b[0] == '哈'){
unset($b[2]);
$b[0] = $b[1];
$b[1] = $zym_9;
}
if(count($b) != 2)continue;
if($b[0] == "" || (int)$b[1] < 1)continue;
$zym_6 = $b[0];
$zym_5 = (int)$b[1];
if($zym_5 < $zym_17_min){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
$zym_7 = (int)get_query_val('fn_pcorder', 'sum(`money`)', "`userid` = '{$userid}' and `status` = '未结算' and `term` = '$addQihao'");
if($zym_7 + $zym_5 > $zym_12_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
if($zym_6 == '大' || $zym_6 == '小' || $zym_6 == '单' || $zym_6 == '双'){
if($zym_5 > $zym_15_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}else if($zym_9 < $zym_5){
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
if($zym_4 == 'true'){
switch($zym_6){
case '大': $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小单'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小双'));
break;
case "小": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大单'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大双'));
break;
case "单": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小双'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大双'));
break;
case "双": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小单'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大单'));
break;
}
if($betting1 != "" || $betting2 != ""){
if($zym_14_例外 > 0 && $zym_7 + $zym_5 > $zym_14_例外){
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}else{
$jinzhi = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
}
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}elseif($zym_6 == '大单' || $zym_6 == '小单' || $zym_6 == '大双' || $zym_6 == '小双'){
if($zym_5 > $zym_19_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}else if($zym_9 < $zym_5){
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
if($zym_4 == 'true'){
switch($zym_6){
case '大单': $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '双'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小'));
break;
case "小单": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '双'));
break;
case "大双": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '小'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '单'));
break;
case "小双": $betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '大'));
$betting2 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => '单'));
break;
}
if($betting1 != "" || $betting2 != ""){
if($zym_14_例外 > 0 && $zym_7 + $zym_5 > $zym_14_例外){
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}else{
$jinzhi = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
}
if($zym_1 == 'true'){
switch($zym_6){
case '大单':$sql = '小单';
break;
case "小单":$sql = '大单';
break;
case "大双":$sql = '小双';
break;
case "小双":$sql = '大双';
break;
}
$betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => $sql));
if($betting1 != ""){
if($zym_14_例外 > 0 && $zym_7 + $zym_5 > $zym_14_例外){
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}else{
$jinzhi = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
}
if($zym_3 == 'true'){
switch($zym_6){
case '大单':$sql = '小双';
break;
case "小单":$sql = '大双';
break;
case "大双":$sql = '小单';
break;
case "小双":$sql = '大单';
break;
}
$betting1 = get_query_val('fn_pcorder', 'content', array('userid' => $userid, 'term' => $addQihao, 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'content' => $sql));
if($betting1 != ""){
if($zym_14_例外 > 0 && $zym_7 + $zym_5 > $zym_14_例外){
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}else{
$jinzhi = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
}
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}elseif($zym_6 == '极大' || $zym_6 == '极小'){
if($zym_5 > $zym_11_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}else if($zym_9 < $zym_5){
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}elseif($zym_6 == '豹子' || $zym_6 == '对子' || $zym_6 == '顺子'){
switch($zym_6){
case '豹子': if($zym_20_max == 0){
continue;
}else{
if($zym_5 > $zym_20_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
break;
case "对子": if($zym_18_max == 0){
continue;
}else{
if($zym_5 > $zym_18_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
break;
case "顺子": if($zym_13_max == 0){
continue;
}else{
if($zym_5 > $zym_13_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
}
break;
}
if($zym_9 < $zym_5){
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}else{
if($zym_5 > $zym_16_max){
$chaozhu = true;
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}else if($zym_9 < $zym_5){
$zym_2 = $zym_6 . '/' . $zym_5;
continue;
}else if(!is_numeric($zym_6)){
continue;
}
$touzhu = true;
用户_下分 ($userid, $zym_5);
insert_query("fn_pcorder", array("term" => $addQihao, 'userid' => $userid, 'username' => $_SESSION['username'], 'headimg' => $_SESSION['headimg'], 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}
}
if($zym_2 != ""){
if($chaozhu){
管理员喊话("@$nickname,本次投注已超注！<br>本房投注限制如下:<br>单点数字最低{$zym_17_min},最高{$zym_16_max}<br>大小单双最低{$zym_17_min},最高{$zym_15_max}<br>组合最低{$zym_17_min},最高{$zym_19_max}<br>极大极小最低{$zym_17_min},最高{$zym_11_max}<br>豹子最低{$zym_17_min},最高{$zym_20_max}<br>对子最低{$zym_17_min},最高{$zym_18_max}<br>顺子最低{$zym_17_min},最高{$zym_13_max}<br>-----------<br>总注不得超过:{$zym_12_max},您已投注:{$zym_7}");
return true;
}elseif($jinzhi){
$nr = "";
if($zym_4 == 'true'){
$nr .= '[禁止杀组合]';
}
if($zym_1 == 'true'){
$nr .= '[禁止同向组合]';
}
if($zym_3 == 'true'){
$nr .= '[禁止反向组合]';
}
if($zym_14_例外 != 0){
$nr2 = '<br>当您的总注达到' . $zym_14_例外 . '时,方可投注此类玩法!';
}else{
$nr2 = "";
}
管理员喊话("@{$nickname},您的:{$zym_2}未接<br>本房$nr" . $nr2);
return true;
}else{
管理员喊话("@{$nickname},您的:{$zym_2}未接，您的余额：" . 查询用户余额($userid));
return true;
}
}elseif(get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['roomid'])) == 'open' && $touzhu == true){
管理员喊话("@{$nickname},投注成功！请选择左侧菜单核对投注！");
return true;
}elseif($touzhu){
return true;
}
return false;
}
function addSSCBet($userid, $nickname, $headimg, $content, $addQihao, $fengpan){
if($fengpan){
管理员喊话 ("@" . $nickname . " ,[$addQihao]期已经停止投注！下注无效！");
return false;
}
$content = str_replace(".", "/", $content);
$content = str_replace(",", "/", $content);
$content = str_replace("，", "/", $content);
$content = preg_replace("/[球号位名各-]/u", "/", $content);
$content = str_replace("总和", "总", $content);
$content = str_replace("合", "和", $content);
$content = str_replace("前三", "前", $content);
$content = str_replace("中三", "中", $content);
$content = str_replace("后三", "后", $content);
$content = str_replace("包/", "包", $content);
$content = preg_replace("/(万|一)\//u", "$1", $content);
$content = preg_replace("/(千|二)\//u", "$1", $content);
$content = preg_replace("/(百|三)\//u", "$1", $content);
$content = preg_replace("/(十|四)\//u", "$1", $content);
$content = preg_replace("/(个|五)\//u", "$1", $content);
$content = preg_replace("/(万|一)/u", "1/", $content);
$content = preg_replace("/(千|二)/u", "2/", $content);
$content = preg_replace("/(百|三)/u", "3/", $content);
$content = preg_replace("/(十|四)/u", "4/", $content);
$content = preg_replace("/(个|五)/u", "5/", $content);
$content = preg_replace("/(龙|虎|和)\//u", "$1", $content);
$content = preg_replace("/\/(龙|虎|和)/u", "$1", $content);
$content = preg_replace("/(龙|虎|和)/u", "总/$1/", $content);
$content = preg_replace("/[前Qq]/u", "前三/", $content);
$content = preg_replace("/[中Zz]/u", "中三/", $content);
$content = preg_replace("/[后Hh]/u", "后三/", $content);
$content = preg_replace("/[包]/u", "包/", $content);
$content = preg_replace("/(大|小|单|双|豹子|顺子|对子|半顺|杂六|前三|中三|后三)\//u", "$1", $content);
$content = preg_replace("/\/(大|小|单|双|豹子|顺子|对子|半顺|杂六)/u", "$1", $content);
$content = preg_replace("/(大|小|单|双|豹子|顺子|对子|半顺|杂六)/u", "/$1/", $content);
switch($_COOKIE['game']){
case 'cqssc': $table = 'fn_lottery3';
$ordertable = 'fn_sscorder';
break;
case "jsssc": $table = 'fn_lottery8';
$ordertable = 'fn_jssscorder';
break;
}
$dx_min = get_query_val($table, 'dx_min', array('roomid' => $_SESSION['roomid']));
$dx_max = get_query_val($table, 'dx_max', array('roomid' => $_SESSION['roomid']));
$ds_min = get_query_val($table, 'ds_min', array('roomid' => $_SESSION['roomid']));
$ds_max = get_query_val($table, 'ds_max', array('roomid' => $_SESSION['roomid']));
$lh_min = get_query_val($table, 'lh_min', array('roomid' => $_SESSION['roomid']));
$lh_max = get_query_val($table, 'lh_max', array('roomid' => $_SESSION['roomid']));
$tm_min = get_query_val($table, 'tm_min', array('roomid' => $_SESSION['roomid']));
$tm_max = get_query_val($table, 'tm_max', array('roomid' => $_SESSION['roomid']));
$zh_min = get_query_val($table, 'zh_min', array('roomid' => $_SESSION['roomid']));
$zh_max = get_query_val($table, 'zh_max', array('roomid' => $_SESSION['roomid']));
$bz_min = get_query_val($table, 'bz_min', array('roomid' => $_SESSION['roomid']));
$bz_max = get_query_val($table, 'bz_max', array('roomid' => $_SESSION['roomid']));
$dz_min = get_query_val($table, 'dz_min', array('roomid' => $_SESSION['roomid']));
$dz_max = get_query_val($table, 'dz_max', array('roomid' => $_SESSION['roomid']));
$sz_min = get_query_val($table, 'sz_min', array('roomid' => $_SESSION['roomid']));
$sz_max = get_query_val($table, 'sz_max', array('roomid' => $_SESSION['roomid']));
$bs_min = get_query_val($table, 'bs_min', array('roomid' => $_SESSION['roomid']));
$bs_max = get_query_val($table, 'bs_max', array('roomid' => $_SESSION['roomid']));
$zl_min = get_query_val($table, 'zl_min', array('roomid' => $_SESSION['roomid']));
$zl_max = get_query_val($table, 'zl_max', array('roomid' => $_SESSION['roomid']));
$zym_8 = get_query_val('fn_user', 'jia', array('userid' => $userid, 'roomid' => $_SESSION['roomid'])) == 'true' ? 'true' : 'false';
$touzhu = false;
$A = explode(" ", $content);
$zym_2 = "";
foreach($A as $ai){
$ai = str_replace(" ", "", $ai);
if(empty($ai))continue;
if(substr($ai, 0, 1) == '/')$ai = '1' . $ai;
$b = explode("/", $ai);
if(count($b) == 2){
if(preg_match("/(大|小|单|双)/u", $ai)){
$ai = '1/' . $ai;
}else{
$ai = '包/' . $ai;
}
$b = explode("/", $ai);
}
if(count($b) != 3)continue;
if($b[0] == "" || $b[1] == "" || (int)$b[2] < 1)continue;
$zym_9 = 查询用户余额 ($userid);
$zym_10 = $b[0];
$zym_6 = $b[1];
$zym_5 = (int)$b[2];
if($zym_10 == '总'){
if($zym_6 == '大' || $zym_6 == '小'){
if((int)$zym_9 < (int)$zym_5){
$zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
continue;
}elseif($zym_5 < $zh_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $zh_max){
$zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
$touzhu = true;
continue;
}elseif($zym_6 == '单' || $zym_6 == '双'){
if((int)$zym_9 < (int)$zym_5){
$zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
continue;
}elseif($zym_5 < $zh_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $zh_max){
$zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
$touzhu = true;
continue;
}elseif($zym_6 == '龙' || $zym_6 == '虎' || $zym_6 == '和'){
if((int)$zym_9 < (int)$zym_5){
$zym_2 .= $zym_6 . "/" . $zym_5 . " ";
continue;
}elseif($zym_5 < $lh_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $lh_max){
$zym_2 .= $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $zym_10, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
$touzhu = true;
continue;
}
continue;
}
if($zym_6 == "大" || $zym_6 == "小" || $zym_6 == "单" || $zym_6 == "双"){
$zym_10_分割 = 文本_逐字分割 ($zym_10);
foreach ($zym_10_分割 as $ii){
if((int)$ii > 5){
管理员喊话("时时彩没有5球以上!本次投注请自行核对与撤单!");
break;
}
if($zym_9 < count($zym_10_分割) * (int)$zym_5){
$zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
break;
}elseif($zym_5 < $dx_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $dx_max && $zym_6 == "大"){
$zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}elseif($zym_5 < $dx_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $dx_max && $zym_6 == "小"){
$zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}elseif($zym_5 < $ds_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $ds_max && $zym_6 == "单"){
$zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}elseif($zym_5 < $ds_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $ds_max && $zym_6 == "双"){
$zym_2 .= $ii . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
$touzhu = true;
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $ii, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}
continue;
}
if($zym_10 == '前三' || $zym_10 == '中三' || $zym_10 == '后三' || preg_match("/(前三|中三|后三)/u", $zym_10)){
$arr = 前中后分割($zym_10);
foreach($arr as $i){
if($zym_9 < (int)$zym_5){
$zym_2 = $zym_2 . $i . "/" . $zym_6 . "/" . $zym_5 . " ";
break;
}elseif($zym_5 < $bz_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $bz_max && $zym_6 == "豹子"){
$zym_2 .= $i . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
break;
}elseif($zym_5 < $dz_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $dz_max && $zym_6 == "对子"){
$zym_2 .= $i . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
break;
}elseif($zym_5 < $sz_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $sz_max && $zym_6 == "顺子"){
$zym_2 .= $i . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
break;
}elseif($zym_5 < $bs_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $bs_max && $zym_6 == "半顺"){
$zym_2 .= $i . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
break;
}elseif($zym_5 < $zl_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $zl_max && $zym_6 == "杂六"){
$zym_2 .= $i . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
break;
}
$touzhu = true;
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $i, 'content' => $zym_6, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
continue;
}
continue;
}
if($zym_10 == "包"){
$zym_6_分割 = 文本_逐字分割 ($zym_6);
foreach($zym_6_分割 as $ii){
if(!is_numeric($ii)){
continue;
}elseif((int)$zym_9 < (int)$zym_5 * 5 * count($zym_6_分割)){
$zym_2 .= $zym_10 . "/" . $ii . "/" . $zym_5 . " ";
continue;
}elseif($zym_5 < $tm_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $tm_max){
$zym_2 .= $zym_10 . "/" . $ii . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
用户_下分($userid, $zym_5 * 5);
db_query("INSERT INTO {$ordertable}(userid, username, headimg, term, mingci, content, `money`, addtime, `status`, jia, roomid) VALUES('$userid', '$nickname', '$headimg', '$addQihao', '1', '{$ii}', '{$zym_5}', now(), '未结算', '{$zym_8}', '{$_SESSION['roomid']}'), ('$userid', '$nickname', '$headimg', '$addQihao', '2', '{$ii}', '{$zym_5}', now(), '未结算', '{$zym_8}', '{$_SESSION['roomid']}'), ('$userid', '$nickname', '$headimg', '$addQihao', '3', '{$ii}', '{$zym_5}', now(), '未结算', '{$zym_8}', '{$_SESSION['roomid']}'), ('$userid', '$nickname', '$headimg', '$addQihao', '4', '{$ii}', '{$zym_5}', now(), '未结算', '{$zym_8}', '{$_SESSION['roomid']}'), ('$userid', '$nickname', '$headimg', '$addQihao', '5', '{$ii}', '{$zym_5}', now(), '未结算', '{$zym_8}', '{$_SESSION['roomid']}')");
$touzhu = true;
continue;
}
continue;
}
$zym_6_分割 = 文本_逐字分割 ($zym_6);
$zym_10_分割 = 文本_逐字分割 ($zym_10);
foreach ($zym_10_分割 as $ii){
if($zym_9 < count($zym_10_分割) * count($zym_6_分割) * (int)$zym_5){
$zym_2 = $zym_2 . $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
break;
}
if((int)$ii > 5){
管理员喊话("时时彩没有5球以上!本次投注请自行核对与撤单!");
break;
}
foreach ($zym_6_分割 as $iii){
if(!is_numeric($iii)){
continue;
}
if($zym_5 < $tm_min || sum_betmoney($ordertable, $zym_10, $zym_6, $userid, $addQihao) + $zym_5 > $tm_max){
$zym_2 .= $zym_10 . "/" . $zym_6 . "/" . $zym_5 . " ";
$chaozhu = true;
continue;
}
$touzhu = true;
用户_下分($userid, $zym_5);
insert_query($ordertable, array('term' => $addQihao, 'userid' => $userid, 'username' => $nickname, 'headimg' => $headimg, 'mingci' => $ii, 'content' => $iii, 'money' => $zym_5, 'addtime' => 'now()', 'roomid' => $_SESSION['roomid'], 'status' => '未结算', 'jia' => $zym_8));
}
}
continue;
}
if($zym_2 != ""){
if($chaozhu){
管理员喊话("@{$nickname},您的:{$zym_2}未接<br>您的投注已超出限制！<br>本房投注限制如下:<br>大小最低{$dx_min}起,最高{$dx_max}<br>单双最低{$ds_min}起,最高{$ds_max}<br>龙虎最低{$lh_min}起,最高{$lh_max}<br>特码最低{$tm_min}起,最高{$tm_max}<br>豹子最低{$bz_min}起,最高{$bz_max}<br>对子最低{$dz_min}起,最高{$dz_max}<br>顺子最低{$sz_min}起,最高{$sz_max}<br>半顺最低{$bs_min}起,最高{$bs_max}<br>杂六最低{$zl_min}起,最高{$zl_max}<br>总和大小单双最低{$zh_min}起,最高{$zh_max}");
return true;
}else{
管理员喊话("@{$nickname},您的:{$zym_2}未接，您的余额：" . 查询用户余额($userid));
return true;
}
}elseif(get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['roomid'])) == 'open' && $touzhu == true){
管理员喊话("@$nickname,投注成功！请选择左侧菜单核对投注！");
return true;
}elseif($touzhu){
return true;
}
return false;
}
?>