<?php	
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
date_default_timezone_set("Asia/Shanghai");
$url = "http://api.woaizy.com/chatkj.php";
$text = file_get_contents($url);
$json = json_decode($text,true);

$index = 'dd';
foreach ($json['data'] as $k => $v){
    if($json['data'][$k]['code'] == 'jssc'){
        $index = $k;break;
    }
}
if($index == 'dd'){
    echo "error";exit;
}

$code = $json['data'][$index]['open_result'];
$code = explode(',',$code);
$codes = (int)$code[0].','.(int)$code[1].','.(int)$code[2].','.(int)$code[3].','.(int)$code[4].','.(int)$code[5].','.(int)$code[6].','.(int)$code[7].','.(int)$code[8].','.(int)$code[9];

$term = $json['data'][$index]['open_phase'];
$nextterm = $json['data'][$index]['next_phase'];;
$count = strtotime($json['data'][$index]['next_time']) - time();
$sumNum = (int)$code[0] + (int)$code[1];
$ds = $sumNum % 2 != 0 ? '单' : '双';
$dx = $sumNum > 11 ? '大':'小';
if($code[0] > $code[9]){
	$lh1 = '龙';
}else{
	$lh1 = '虎';
}
if($code[1] > $code[8]){
	$lh2 = '龙';
}else{
	$lh2 = '虎';
}
if($code[2] > $code[7]){
	$lh3 = '龙';
}else{
	$lh3 = '虎';
}
if($code[3] > $code[6]){
	$lh4 = '龙';
}else{
	$lh4 = '虎';
}
if($code[4] > $code[5]){
	$lh5 = '龙';
}else{
	$lh5 = '虎';
}
echo json_encode(array('code'=>$codes,'term'=>$term,'nextterm'=>$nextterm,'count'=>$count,'sumNum'=>$sumNum,'hedx'=>$dx,'heds'=>$ds,'lh1'=>$lh1,'lh2'=>$lh2,'lh3'=>$lh3,'lh4'=>$lh4,'lh5'=>$lh5));
exit;

?>