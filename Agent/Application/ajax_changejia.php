<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$arr = array();
if($_POST['id'] == ''){
    $arr['success'] = false;
    $arr['msg'] = '参数错误..Err(-1203)';
}else{
    $jia = get_query_val('fn_user', 'jia', array('id' => $_POST['id'])) == 'true' ? 'false' : 'true';
    update_query("fn_user", array("jia" => $jia), array('id' => $_POST['id']));
    $arr['success'] = true;
    if($jia == 'true'){
        $arr['msg'] = '已设置为假人';
    }else{
        $arr['msg'] = '已取消假人身份';
    }
}
echo json_encode($arr);
?>