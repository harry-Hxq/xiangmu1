<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = get_query_vals('fn_setting', '*', array('roomid' => $_SESSION['agent_room']));
$refreshset = rand((int)$time['setting_robot_pointmin'], (int)$time['setting_robot_pointmax']);
if($time['setting_robot_min'] == ""){
    $refreshset = rand(90, 180);
}
$BetGame = $_GET['g'];
function 管理员喊话($Content, $game){
    $headimg = get_query_val('fn_setting', 'setting_sysimg', array('roomid' => $_SESSION['agent_room']));
    insert_query("fn_chat", array("userid" => "system", "username" => "管理员", "headimg" => $headimg, 'game' => $game, 'content' => $Content, 'addtime' => date('H:i:s'), 'type' => 'S1', 'roomid' => $_SESSION['agent_room']));
}
$t = rand(1, 2);
$mm = rand(1, 2);
if($mm == 1){
    $m = rand(1, 9) * 100;
}else{
    $m = rand(1, 3) * 1000;
}
$robots = get_query_vals('fn_robots', '*', "roomid = {$_SESSION['agent_room']} and game = '{$BetGame}' order by rand() desc limit 1");
$headimg = $robots['headimg'];
$name = $robots['name'];
if($headimg == ''){
    exit;
}
switch($t){
case 1: $content = '查' . $m;
    break;
case 2: $content = '回' . $m;
    break;
}
insert_query("fn_chat", array("userid" => "robot", "username" => $name, 'headimg' => $headimg, 'content' => $content, 'addtime' => date('H:i:s'), 'game' => $BetGame, 'roomid' => $_SESSION['agent_room'], 'type' => 'U3'));
sleep(3);
switch($t){
case 1: 管理员喊话("@$name,您的上分请求已同意!", $BetGame);
    break;
case 2: 管理员喊话("@$name,您的下分请求已同意!", $BetGame);
    break;
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
 echo "已自动发言: <img src=\"" . $headimg . "\" alt=\"\" width=\"28\" height=\"28\" /> " . $name . "[$content]";
?>
</body>
</html>