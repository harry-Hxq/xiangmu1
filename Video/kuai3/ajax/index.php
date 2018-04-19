<?php	
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
$url = "http://api.woaizy.com/chatkj.php";
$text = file_get_contents($url);
$json = json_decode($text,true);

$index = 'dd';
foreach ($json['data'] as $k => $v){
    if($json['data']["$k"]['code'] == 'jsk3'){
        $index = "$k";break;
    }
}
if($index == 'dd'){
    #todo 如果没有获取到结果，应当从数据库中取最新的数据
    echo "error";exit;
}

$code = $json['data'][$index]['open_result'];
$code = explode(',',$code);
$codes = $code[0].$code[1].$code[2];

$term = substr($json['data'][$index]['open_phase'],2);
$time = $json['data'][$index]['next_time'];
$count = strtotime($json['data'][$index]['next_time']) - time();
$sumNum = (int)$code[0] + (int)$code[1] + (int)$code[2];
$ds = $sumNum % 2 != 0 ? '单' : '双';
$dx = $sumNum > 10 ? '大':'小';

echo json_encode(array('code'=>$codes,'term'=>$term,'nexttime'=>$time,'count'=>$count,'sumNum'=>$sumNum, 'dx'=>$dx));
exit;

?>