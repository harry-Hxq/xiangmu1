<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = get_query_vals('fn_setting', '*', array('roomid' => $_SESSION['agent_room']));
$refreshset = rand((int)$time['setting_robot_min'], (int)$time['setting_robot_max']);
if($time['setting_robot_min'] == ""){
    $refreshset = rand(3, 8);
}
$BetGame = $_GET['g'];
runrobot($BetGame, $name, $headimg, $content);
function str_replace_once($needle, $replace, $haystack){
    $pos = strpos($haystack, $needle);
    if ($pos === false){
        return $haystack;
    }
    return substr_replace($haystack, $replace, $pos, strlen($needle));
}
function 管理员喊话($Content, $game){
    $headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $_SESSION['agent_room']));
    insert_query("fn_chat", array("userid" => "system", "username" => "机器人", "game" => $game, 'headimg' => $headimg, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S3', 'roomid' => $_SESSION['agent_room']));
}
function runrobot($BetGame, & $name, & $headimg, & $plan){
    if($BetGame == 'pk10'){
        if(get_query_val('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'xyft'){
        if(get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'cqssc'){
        if(get_query_val('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'xy28'){
        if(get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jnd28'){
        if(get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jsmt'){
        if(get_query_val('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jssc'){
        if(get_query_val('fn_lottery7', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }elseif($BetGame == 'jsssc'){
        if(get_query_val('fn_lottery8', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'false')$BetGame = 'feng';
    }
    if($BetGame == 'pk10'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['agent_room']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 1 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'xyft'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['agent_room']));
        $djs = strtotime(get_query_val('fn_open', 'next_time', 'type = 2 order by term desc limit 1')) - time();
        if($djs < $time){
            $fengpan = true;
        }else{
            $fengpan = false;
        }
    }elseif($BetGame == 'cqssc'){
        $BetTerm = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
        $time = (int)get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['agent_room']));
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
    if(!$fengpan){
        $robots = get_query_vals('fn_robots', '*', "roomid = {$_SESSION['agent_room']} and game = '{$BetGame}' order by rand() desc limit 1");
        $headimg = $robots['headimg'];
        $name = $robots['name'];
        $plan = $robots['plan'];
        $plan = explode('|', $plan);
        if($headimg == ''){
            exit;
        }
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
        insert_query("fn_chat", array("userid" => "robot", "username" => $name, 'headimg' => $headimg, 'content' => $plan, 'addtime' => date('H:i:s'), 'game' => $BetGame, 'roomid' => $_SESSION['agent_room'], 'type' => 'U3'));
        if(get_query_val("fn_setting", "setting_tishi", array("roomid" => $_SESSION['agent_room'])) == 'open'){
            管理员喊话("@$name,投注成功！请选择左侧菜单核对投注！", $BetGame);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="refresh" content="<?=$refreshset?>" />
	<title>聊天下注机器人</title>
</head>
<body>
<?php
 echo $BetGame . "已自动发言: <img src=\"" . $headimg . "\" alt=\"\" width=\"28\" height=\"28\" /> " . $name . "[$content]";
?>
</body>
</html>