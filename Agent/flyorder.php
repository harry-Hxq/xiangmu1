<?php
include_once("../Public/config.php");
require "Application/class/SGWin1.php";
require "Application/class/SGWin2.php";
require "Application/class/dafeng.php";
require "Application/class/ali.php";
require "Application/class/esc.php";
require "Application/class/IDC1.php";

switch($_GET['t']){
case 'start': $roomid = $_GET['roomid'];
    $db = get_query_vals('fn_setting', '*', array('roomid' => $roomid));
    $pantype = $db['flyorder_type'];
    $site = $db['flyorder_site'];
    $user = $db['flyorder_user'];
    $pass = $db['flyorder_pass'];
    $term = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $roomid)) != ""){
        exit;
    }
    if($pantype == 'jinfu' || $pantype == 'jufu' || $pantype == 'dafa' || $pantype == '88cp'){
        $cookies = SG_Login($site, $user, $pass);
        SG_goBet($site, $cookies['cookies'], $roomid, $user);
    }elseif($pantype == 'esc'){
        ESC_GoBet($site, $user, $db['flyorder_session'], $roomid);
    }elseif($pantype == 'SGold'){
        SG_goBet($site, $db['flyorder_session'], $roomid, $user);
    }elseif($pantype == 'IDC1'){
        IDC1_goBet($site, $db['flyorder_session'], $roomid, $user, 'pk10');
    }elseif($pantype == 'ali' || $pantype == 'guangda'){
        $uid = ali_Login($site, $user, $pass);
        ali_GoBet($site, $user, $uid['uid'], $roomid);
    }elseif($pantype == '188cp' || $pantype == 'dafeng'){
        $cookies = y88_login($site, $user, $pass);
        y88_GoBet($site, $user, $cookies['cookies'], $roomid);
    }elseif($pantype == 'SG2'){
        SG2_goBet($site, $db['flyorder_session'], $roomid, $user, 'pk10');
    }
    elseif($pantype == 'IDC2'){
        IDC2_goBet($site, $db['flyorder_session'], $roomid, $user, 'pk10');
    }
    echo "成功";
    exit;
case "xyftstart": $roomid = $_GET['roomid'];
    $db = get_query_vals('fn_setting', '*', array('roomid' => $roomid));
    $pantype = $db['flyorder_type'];
    $site = $db['flyorder_site'];
    $user = $db['flyorder_user'];
    $pass = $db['flyorder_pass'];
    $term = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $roomid)) != ""){
        exit;
    }
    if($pantype == 'ali' || $pantype == 'guangda'){
        $uid = ali_Login($site, $user, $pass);
        ali_GoBet($site, $user, $uid['uid'], $roomid, 'xyft');
    }elseif($pantype == '188cp' || $pantype == 'dafeng'){
        $cookies = y88_login($site, $user, $pass);
        y88_GoBet($site, $user, $cookies['cookies'], $roomid, 'xyft');
    }elseif($pantype == 'SG2'){
        SG2_goBet($site, $db['flyorder_session'], $roomid, $user, 'xyft');;
    }elseif($pantype == 'IDC1'){
        IDC1_goBet($site, $db['flyorder_session'], $roomid, $user, 'xyft');;
    }
    echo "成功";
    exit;
case "sscstart": $roomid = $_GET['roomid'];
    $db = get_query_vals('fn_setting', '*', array('roomid' => $roomid));
    $pantype = $db['flyorder_type'];
    $site = $db['flyorder_site'];
    $user = $db['flyorder_user'];
    $pass = $db['flyorder_pass'];
    $term = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $roomid)) != ""){
        exit;
    }
    if($pantype == 'jinfu' || $pantype == 'jufu' || $pantype == 'dafa' || $pantype == '88cp'){
        $cookies = SG_Login($site, $user, $pass);
        SG_goBetSSC($site, $cookies['cookies'], $roomid, $user);
    }elseif($pantype == 'esc'){
    }elseif($pantype == 'SGold'){
        SG_goBetSSC($site, $db['flyorder_session'], $roomid, $user);
    }elseif($pantype == '188cp' || $pantype == 'dafeng'){
        $cookies = y88_login($site, $user, $pass);
        y88_GoBetSSC($site, $user, $cookies['cookies'], $roomid);
    }elseif($pantype == 'SG2'){
        SG2_goBetSSC($site, $db['flyorder_session'], $roomid, $user);
    }elseif($pantype == 'IDC2'){
        IDC2_GoBetSSC($site, $db['flyorder_session'], $roomid, $user);
    }
    echo "成功";
    exit;
case "isfly": $term = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
    if(get_query_val("fn_order", "money", array("term" => $term, 'roomid' => $_GET['roomid'], 'status' => '未结算')) == ''){
        echo json_encode(array('isfly' => true));
        exit;
    }
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $_GET['roomid'])) != ""){
        echo json_encode(array('isfly' => true));
        exit;
    }
    echo json_encode(array("isfly" => false));
    exit;
case "xyftisfly": $term = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
    if(get_query_val("fn_order", "money", array("term" => $term, 'roomid' => $_GET['roomid'], 'status' => '未结算')) == ''){
        echo json_encode(array('isfly' => true));
        exit;
    }
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $_GET['roomid'])) != ""){
        echo json_encode(array('isfly' => true));
        exit;
    }
    echo json_encode(array("isfly" => false));
    exit;
case "sscisfly": $term = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
    $term = substr($term, 0, 8) . '-' . substr($term, 8, 3);
    if(get_query_val("fn_sscorder", "money", array("term" => $term, 'roomid' => $_GET['roomid'], 'status' => '未结算')) == ''){
        echo json_encode(array('isfly' => true));
        exit;
    }
    if(get_query_val("fn_flyorder", "term", array("term" => $term, 'roomid' => $_GET['roomid'])) != ""){
        echo json_encode(array('isfly' => true));
        exit;
    }
    echo json_encode(array("isfly" => false));
    exit;
case "heart":
	$db = get_query_vals('fn_setting', "*", array("roomid"=>$_SESSION['agent_room']));
	if($db['flyorder_type'] == "IDC1"){
		$result = IDC1_getMoney($db['flyorder_site'], $db['flyorder_session']);
		if(is_numeric($result['money'])){
			echo json_encode(array("success"=>true));
			exit;
		}else{
			echo json_encode(array('success'=>false));
			exit;
		}
	}else{
		echo json_encode(array('success'=>true));
		exit;
	}
}
function ext_json_decode($str, $mode = false){
$str = preg_replace('/([{,])(\s*)([A-Za-z0-9_\-]+?)\s*:/', '$1"$3":', $str);
$str = str_replace('\'', '"', $str);
$str = str_replace(" ", "", $str);
$str = str_replace('\t', "", $str);
$str = str_replace('\r', "", $str);
$str = str_replace("\l", "", $str);
$str = preg_replace('/s+/', '', $str);
$str = trim($str, chr(239) . chr(187) . chr(191) .chr(48) . chr(49) . chr(50) . chr(51) . chr(52) . chr(54) . chr(54) . chr(55) . chr(56) . chr(57));
return json_decode($str, $mode);
}
function prepareJSON($input){
$imput = mb_convert_encoding($input, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
if(substr($input, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF))$input = substr($input, 3);
return $input;
}
function getSubstr($str, $leftStr, $rightStr){
$left = strpos($str, $leftStr);
$right = strpos($str, $rightStr, $left);
if($left < 0 or $right < $left)return '';
return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}
function loadingdomain($type){
$dafa = array('http://www.f1122.com', 'http://www.f8899.com', 'http://www.f6677.com');
$cp88 = array('https://678789.com', 'http://660088.com', 'http://888678.com', 'https://789888.com', 'https://vip007.com', 'http://www.888789.com',);
$jufu = array('https://jufu44.com', 'https://mm888.com', 'https://jufu33.com', 'https://nn888.com');
$jinfu = array('https://www.jf8811.com', 'https://www.jf8800.com');
}
?>