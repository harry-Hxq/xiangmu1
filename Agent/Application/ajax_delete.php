<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$time = $_POST['time'];
$pass = $_POST['pass'];
if($pass != 'hhh45688'){
    echo json_encode(array('success' => false, 'msg' => '验证安全码错误!请联系管理员进行该操作'));
    exit;
}
db_query("TRUNCATE fn_chat");
//客服表清理
delete_query('fn_custom',"`addtime` < '$time 23:59:59'");
//客户投注记录
delete_query('fn_order',"`addtime` < '$time 23:59:59'");
delete_query('fn_pcorder',"`addtime` < '$time 23:59:59'");
delete_query('fn_sscorder',"`addtime` < '$time 23:59:59'");
delete_query('fn_mtorder',"`addtime` < '$time 23:59:59'");
delete_query('fn_jsscorder',"`addtime` < '$time 23:59:59'");
delete_query('fn_jssscorder',"`addtime` < '$time 23:59:59'");
delete_query('fn_k3order',"`addtime` < '$time 23:59:59'");
//客户账变记录
delete_query('fn_marklog',"`addtime` < '$time 23:59:59'");
//开奖记录
delete_query('fn_open',"`time` < '$time 23:59:59'");
//上下分记录
delete_query('fn_upmark',"`time` < '$time 23:59:59'");
//飞单记录
delete_query('fn_flyorder',"`time` < '$time 23:59:59'");

echo json_encode(array("success" => true, "msg" => "删除成功"));
exit;
?>