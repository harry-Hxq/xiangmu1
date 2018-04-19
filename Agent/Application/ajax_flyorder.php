<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == 'save'){
    $open = $_POST['true'];
    $flytype = $_POST['flytype'];
    $flysite = $_POST['flysite'] == "" ? $_POST['flysite_select'] : $_POST['flysite'];
    $flyuser = $_POST['flyuser'];
    $flypass = $_POST['flypass'];
    $game_pk10 = $_POST['flypk10'] == 'on' ? 'true' : 'false';
    $game_xyft = $_POST['flyxyft'] == 'on' ? 'true' : 'false';
    $game_cqssc = $_POST['flycqssc'] == 'on' ? 'true' : 'false';
    $duichong = $_POST['flyduichong'] == 'on' ? 'true' : 'false';
    $version = get_query_val('fn_room', 'version', array('roomid' => $_SESSION['agent_room']));
    if($version != "飞单版" && $version != "尊享版"){
        echo '<script>alert("您的房间权限不足,不能够使用此功能")</script>';
        exit();
    }else{
        update_query("fn_setting", array("setting_flyorder" => $open, 'flyorder_type' => $flytype, 'flyorder_user' => $flyuser, 'flyorder_pass' => $flypass, 'flyorder_site' => $flysite, 'flyorder_duichong' => $duichong, 'flyorder_pk10' => $game_pk10, 'flyorder_xyft' => $game_xyft, 'flyorder_cqssc' => $game_cqssc), array('roomid' => $_SESSION['agent_room']));
        echo '<script>alert("保存成功,感谢使用~");window.location.href="/Agent/index.php?m=flyorder&f=fly";</script>';
        exit();
    }
}elseif($type == 'test'){
    require '../flyorder.php';
    $type = $_POST['type'];
    $site = $_POST['site'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $code = $_POST['code'];
    if($type == 'dafa' || $type == '88cp' || $type == 'jufu' || $type == 'jinfu'){
        $money = SG_Login($site, $user, $pass);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }elseif($type == 'esc'){
        $money = ESC_Login($site, $user, $pass, $code, $_COOKIE['esccookie']);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常'));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }elseif($type == 'SGold'){
        $money = SGold_Login($site, $user, $pass, $code, $_COOKIE['esccookie']);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
     }elseif($type == 'IDC1'){
        $money = IDC1_Login($site, $user, $pass, $code, $_COOKIE['esccookie']);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }elseif($type == 'ali' || $type == 'guangda'){
        $money = ali_Login($site, $user, $pass);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }elseif($type == '188cp' || $type == 'dafeng'){
        $money = y88_Login($site, $user, $pass);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }elseif($type == 'SG2'){
        $money = SG2_login($site, $user, $pass, $code, $_COOKIE['esccookie']);
        if(!is_numeric($money['money'])){
            echo json_encode(array('success' => false, 'msg' => '测试登陆失败,请检查地址、账号、密码是否正确/正常', 'err' => $money['err']));
            exit;
        }else{
            echo json_encode(array("success" => true, "money" => $money['money'], 'weijie' => $money['weijie']));
            exit;
        }
    }
}elseif($type == 'getcode'){
    require '../flyorder.php';
    $type = $_POST['type'];
    $site = $_POST['site'];
    if($type == 'esc'){
        $arr = ESC_getcode($site);
        setcookie("esccookie", $arr['cookie'], time() + 3600);
        $_COOKIE['esccookie'] = $arr['cookie'];
        echo json_encode(array("success" => true, "code" => $arr['code']));
        exit;
    }elseif($type == 'SGold'){
        $arr = SG_getcode($site);
        setcookie("esccookie", $arr['cookie'], time() + 3600);
        $_COOKIE['esccookie'] = $arr['cookie'];
        echo json_encode(array("success" => true, "code" => $arr['code']));
        exit;
    }elseif($type == 'SG2'){
        $arr = SG2_getcode($site);
        setcookie("esccookie", $arr['cookie'], time() + 3600);
        $_COOKIE['esccookie'] = $arr['cookie'];
        echo json_encode(array("success" => true, "code" => $arr['code']));
        exit;
    }elseif($type == 'IDC1'){
        $arr = IDC1_getcode($site);
        setcookie("esccookie", $arr['cookie'], time() + 3600);
        $_COOKIE['esccookie'] = $arr['cookie'];
        echo json_encode(array("success" => true, "code" => $arr['code']));
        exit;
    }
}
?>