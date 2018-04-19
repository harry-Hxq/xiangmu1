<?php 
//echo file_get_contents('http://www.1391c.com/xyft/ajax?ajaxhandler=GetXyftAwardData');
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
    function getTimestamp($digits = false) {  
        $digits = $digits > 10 ? $digits : 10;  
        $digits = $digits - 10;
        if ((!$digits) || ($digits == 10))  
        {  
            return time();  
        }  
        else  
        {  
            return number_format(microtime(true),$digits,'','');  
        }  
    }  

$json = file_get_contents('http://jsmt.woaizy.com/api.php');
$json = json_decode($json,1);

$index = 'dd';
foreach ($json['data'] as $k => $v){
    if($json['data'][$k]['code'] == 'jsmt'){
        $index = $k;break;
    }
}
if($index == 'dd'){
    echo "error";exit;
}

$aa = $json['open'][$index]['opencode'];
$arr = explode(",",$aa);
$opencode.= (int)$arr[0].",";
$opencode.= (int)$arr[1].",";
$opencode.= (int)$arr[2].",";
$opencode.= (int)$arr[3].",";
$opencode.= (int)$arr[4].",";
$opencode.= (int)$arr[5].",";
$opencode.= (int)$arr[6].",";
$opencode.= (int)$arr[7].",";
$opencode.= (int)$arr[8].",";
$opencode.= (int)$arr[9];

$IntervalTimeMs=strtotime($json['next'][$index]['opentime'])."000";
$IntervalTimeMs = $IntervalTimeMs - getTimestamp(13);
$periodNumber1 = (int)$json['open'][$index]['expect'];
$periodNumber2 = (int)$json['next'][$index]['expect'];
echo "
{
	\"time\":".getTimestamp(13).",
	\"current\":{
		\"periodNumber\":" . $periodNumber1 . ",
		\"awardTime\":\"" . $json['open'][$index]['opentime'] . "\",
		\"awardNumbers\":\"". $opencode ."\"
	},\"next\":{
		\"periodNumber\":".$periodNumber2.",
		\"awardTime\":\"". $json['next'][$index]['opentime'] ."\",
		\"awardTimeInterval\": ". $IntervalTimeMs  .",
		\"delayTimeInterval\":-6
	}
}

";
?>