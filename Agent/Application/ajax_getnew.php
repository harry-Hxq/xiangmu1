<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$newsf = (int)get_query_val('fn_upmark', 'count(*)', "roomid = '{$_SESSION['agent_room']}' and status = '未处理' and type = '上分'");
$newxf = (int)get_query_val('fn_upmark', 'count(*)', "roomid = '{$_SESSION['agent_room']}' and status = '未处理' and type = '下分'");
$newmsg = (int)get_query_val('fn_custom', 'count(*)', "roomid = '{$_SESSION['agent_room']}' and status = '未读'");
$newpay = 0;
$sfdata = array();
select_query("fn_upmark", '*', "roomid = '{$_SESSION['agent_room']}' and status = '未处理' and type = '上分' order by id desc");
while($sfdata_ = db_fetch_array()){
    $sfdata[] = array('nickname' => $sfdata_['username'], 'headimg' => $sfdata_['headimg'], 'time' => $sfdata_['time'], 'money' => $sfdata_['money']);
}
select_query("fn_upmark", '*', "roomid = '{$_SESSION['agent_room']}' and status = '未处理' and type = '下分' order by id desc");
while($xfdata_ = db_fetch_array()){
    $xfdata[] = array('nickname' => $xfdata_['username'], 'headimg' => $xfdata_['headimg'], 'time' => $xfdata_['time'], 'money' => $xfdata_['money']);
}
echo json_encode(array("newsf" => $newsf, 'newxf' => $newxf, 'newpay' => $newpay, 'sfdata' => $sfdata, 'xfdata' => $xfdata, 'newmsg' => $newmsg));
exit;
?>