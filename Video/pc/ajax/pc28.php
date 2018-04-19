<?php
/*function _GET($URL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不验证证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不验证证书
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 7Star/2.0.56.2 Safari/537.3)');
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$cookieFile='cook.txt';
	curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieFile);
	curl_setopt ($ch, CURLOPT_COOKIESESSION,true);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
    $data = curl_exec($ch);
	$cinfo=curl_getinfo($ch);
	if($cinfo['http_code']==302){
		$data = curl_exec($ch);//echo time();
	}
	//print_r($cinfo);
    curl_close($ch);
    return $data;
}
echo _GET('http://t6a.c360.net.cn/chatbet/game/ajax_video_pc28_1.php?ajaxHandler=GetPC28AwardData');*/
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
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
if($_COOKIE['game'] == 'xy28'){
	$gametype = 'pc28a';
	$url = "http://api.woaizy.com/pc28.php";
	$json = json_decode(file_get_contents($url),1);
}elseif($_COOKIE['game'] == 'jnd28'){
	$gametype = 'pc28b';
	$url = "http://api.woaizy.com/jnd28.php";
	$json = json_decode(file_get_contents($url),1);
	$nextterm = (int)$json['data'][0]['open_phase'] + 1;
	$yy = explode(" ",$json['data'][0]['open_time']);
	$yy = $yy[0];
	$tt = date('H:i:s',strtotime($json['data'][0]['open_time']));
	$tt2 = explode(":",$tt);
	$ss = (int)$tt2[2];
	if($ss != 0 || $ss != 30){
		if($ss < 30){
			$ss2 = '00';
			$sstime = $yy." ".str_replace($ss,$ss2 ,$tt);
			$nexttime = date('Y-m-d H:i:s',strtotime("$sstime +3 minute +30 seconds"));
		}elseif($ss > 30){
			$ss2 = '30';
			$sstime = $yy." ".str_replace($ss,$ss2 ,$tt);
			$nexttime = date('Y-m-d H:i:s',strtotime("$sstime +3 minute +30 seconds"));
		}
	}
	$json['data'][0]['next_phase'] = $nextterm;
	$json['data'][0]['next_time'] = $nexttime;
}

$term = $json['data'][0]['open_phase'];
$time = $json['data'][0]['load_time'];
$code = $json['data'][0]['open_result'];
$codes = explode(',',$code);
if(count($codes) != 20){
	echo 'ERROR!';
	exit();
}else{
	if($gametype == "pc28a"){
		$number1 = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4] + (int)$codes[5];
		$number2 = (int)$codes[6] + (int)$codes[7] + (int)$codes[8] + (int)$codes[9] + (int)$codes[10] + (int)$codes[11];
		$number3 = (int)$codes[12] + (int)$codes[13] + (int)$codes[14] + (int)$codes[15] + (int)$codes[16] + (int)$codes[17];
		
		$number1 = substr($number1,-1);
		$number2 = substr($number2,-1);
		$number3 = substr($number3,-1);
		$hz = (int)$number1 + (int)$number2 + (int)$number3;
	}elseif($gametype == 'pc28b'){
		$number1 = (int)$codes[1] + (int)$codes[4] + (int)$codes[7] + (int)$codes[10] + (int)$codes[13] + (int)$codes[16];
		$number2 = (int)$codes[2] + (int)$codes[5] + (int)$codes[8] + (int)$codes[11] + (int)$codes[14] + (int)$codes[17];
		$number3 = (int)$codes[3] + (int)$codes[6] + (int)$codes[9] + (int)$codes[12] + (int)$codes[15] + (int)$codes[18];
		
		$number1 = substr($number1,-1);
		$number2 = substr($number2,-1);
		$number3 = substr($number3,-1);
		$hz = (int)$number1 + (int)$number2 + (int)$number3;
	}

}
$next_term = $json['data'][0]['next_phase'];
$next_time = $json['data'][0]['next_time'];
$next_ss = (int)strtotime($next_time).'000' - getTimestamp(13);
$next_game = $gametype;

echo "
{
	\"time\":".getTimestamp(13).",
	\"current\": {
		\"game\":\"".$gametype."\",
		\"periodNumber\":\"".$term."\",
		\"awardTime\":\"".$time."\",
		\"awardNumbers\":\"".$number1.','.$number2.','.$number3."\"
	},
	\"next\":{
		\"game\":\"".$next_game."\",
		\"periodNumber\":\"".$next_term."\",
		\"awardTime\":\"".$next_time."\",
		\"awardTimeInterval\":".$next_ss.",
		\"delayTimeInterval\": 0 
	}
}

";
?>