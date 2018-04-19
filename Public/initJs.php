<?php
include "../Public/config.php";
$Appid = 'wxf5f6852960c17ca7';
$Appkey = 'a883ac324d946527a5d4627e1791e89a';
$sql = get_query_vals('fn_system', '*', array('type' => 1));
$sql_time = $sql['content2'];
if(time() - (int)$sql_time >= 7200){
    $token = getToken($Appid, $Appkey);
    update_query("fn_system", array("content1" => $token, 'content2' => time()), array('type' => 1));
}else{
    $token = $sql['content1'];
}
$sql = get_query_vals('fn_system', '*', array('type' => 2));
$sql_time = $sql['content2'];
if(time() - (int)$sql_time >= 7200){
    $jsapi = getJsapi($token);
    update_query("fn_system", array("content1" => $jsapi, 'content2' => time()), array('type' => 2));
}else{
    $jsapi = $sql['content1'];
}
$noncestr = getRandStr(15);
$nowtime = time();
$str = "jsapi_ticket={$jsapi}&noncestr={$noncestr}&timestamp={$nowtime}&url={$_POST['url']}";
$strr = sha1($str);
echo json_encode(array("appId" => $Appid, 'timestamp' => "$nowtime", 'noncestr' => $noncestr, 'signature' => $strr));
function getRandStr($length){
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-';
    $randString = '';
    $len = strlen($str)-1;
    for($i = 0;$i < $length;$i ++){
        $num = mt_rand(0, $len);
        $randString .= $str[$num];
    }
    return $randString ;
}
function getToken($Appid, $Appkey){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$Appid}&secret={$Appkey}";
    $url = file_get_contents($url);
    $json = json_decode($url, 1);
    $token = $json['access_token'];
    return $token;
}
function getJsapi($token){
    $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
    $url = file_get_contents($url);
    $json = json_decode($url, 1);
    $jsapi = $json['ticket'];
    return $jsapi;
}
?>