<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
switch($_GET['g']){
case 'pk10': $game = '1';
    $feng = get_query_val('fn_lottery1', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "xyft": $game = '2';
    $feng = get_query_val('fn_lottery2', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "cqssc": $game = '3';
    $feng = get_query_val('fn_lottery3', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "xy28": $game = '4';
	$feng = get_query_val('fn_lottery4', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jnd28": $game = '5';
	$feng = get_query_val('fn_lottery5', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jsmt": $game = '6';
	$feng = get_query_val('fn_lottery6', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jssc": $game = '7';
	$feng = get_query_val('fn_lottery7', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "jsssc": $game = '8';
	$feng = get_query_val('fn_lottery8', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
case "kuai3": $game = '9';
	$feng = get_query_val('fn_lottery9', 'fengtime', array('roomid' => $_SESSION['agent_room']));
    break;
}
$term = get_query_vals('fn_open', '*', "type = '$game' order by term desc limit 1");
$code = $term['code'];
$time = strtotime($term['next_time']) - time();
$term = $term['term'];
$fly = get_query_val('fn_setting', 'setting_flyorder', array('roomid' => $_SESSION['agent_room']));
if($game<4){
$flys = get_query_val('fn_setting', 'flyorder_' . $_GET['g'], array('roomid' => $_SESSION['agent_room']));
}
else{
$flys = "false";
}
echo json_encode(array("code" => $code, 'term' => $term, 'time' => $time, 'feng' => $feng, 'flyorder' => $fly, 'flys' => $flys, 'roomid' => $_SESSION['agent_room']));
?>