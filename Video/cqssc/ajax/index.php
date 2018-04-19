<?php	
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
$url = "http://api.woaizy.com/chatkj.php";
$text = file_get_contents($url);
$json = json_decode($text,true);

$index = 'dd';
foreach ($json['data'] as $k => $v){
    if($json['data'][$k]['code'] == 'cqssc'){
        $index = $k;break;
    }
}
if($index == 'dd'){
    echo "error";exit;
}

$code = $json['data'][$index]['open_result'];
$code = explode(',',$code);
$codes = $code[0].$code[1].$code[2].$code[3].$code[4];

$term = $json['data'][$index]['open_phase'];
$time = date('H:i:s',strtotime($json['data'][$index]['next_time']));
$count = strtotime($json['data'][$index]['next_time']) - time();
$sumNum = (int)$code[0] + (int)$code[1] + (int)$code[2] + (int)$code[3] + (int)$code[4];
$ds = $sumNum % 2 != 0 ? '单' : '双';
$dx = $sumNum > 22 ? '大':'小';
if($code[0] > $code[4]){
	$lh = '龙';
}elseif($code[0] < $code[4]){
	$lh = '虎';
}elseif($code[0] == $code[4]){
	$lh = '和';
}

echo json_encode(array('code'=>$codes,'term'=>$term,'nexttime'=>$time,'count'=>$count,'sumNum'=>$sumNum,'hedx'=>$dx,'heds'=>$ds,'lh'=>$lh));
exit;

?>