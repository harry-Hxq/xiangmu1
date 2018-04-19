<?php include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$arr = array();
$time = time()-300;
select_query("fn_user", '*', "`roomid` = '{$_SESSION['agent_room']}' and `statustime` > $time");
while($con = db_fetch_array()){
    $cons[] = $con;
    $arr['data'][] = array($con['username'], $con['money'], $con['id']);
}
if(count($cons) == 0){
    $arr['data'][0] = 'null';
}
echo json_encode($arr);
?>