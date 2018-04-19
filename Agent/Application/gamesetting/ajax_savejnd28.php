<?php
include(dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php");
if($_GET['form'] == 'form1'){
    $p0027 = $_POST['0027'];
    $p0126 = $_POST['0126'];
    $p0225 = $_POST['0225'];
    $p0324 = $_POST['0324'];
    $p0423 = $_POST['0423'];
    $p0522 = $_POST['0522'];
    $p0621 = $_POST['0621'];
    $p0720 = $_POST['0720'];
    $p891819 = $_POST['891819'];
    $p10111617 = $_POST['10111617'];
    $p1215 = $_POST['1215'];
    $p1314 = $_POST['1314'];
    $jida = $_POST['jida'];
    $jixiao = $_POST['jixiao'];
    $baozi = $_POST['baozi'];
    $duizi = $_POST['duizi'];
    $shunzi = $_POST['shunzi'];
    $dxds = $_POST['dxds'];
    $dadan = $_POST['dadan'];
    $xiaodan = $_POST['xiaodan'];
    $dashuang = $_POST['dashuang'];
    $xiaoshuang = $_POST['xiaoshuang'];
    $dxds_zongzhu1 = $_POST['dxds_zongzhu1'];
    $dxds_1314_1 = $_POST['dxds_1314_1'];
    $dxds_zongzhu2 = $_POST['dxds_zongzhu2'];
    $dxds_1314_2 = $_POST['dxds_1314_2'];
    $dxds_zongzhu3 = $_POST['dxds_zongzhu3'];
    $dxds_1314_3 = $_POST['dxds_1314_3'];
    $zuhe_zongzhu1 = $_POST['zuhe_zongzhu1'];
    $zuhe_1314_1 = $_POST['zuhe_1314_1'];
    $zuhe_zongzhu2 = $_POST['zuhe_zongzhu2'];
    $zuhe_1314_2 = $_POST['zuhe_1314_2'];
    $zuhe_zongzhu3 = $_POST['zuhe_zongzhu3'];
    $zuhe_1314_3 = $_POST['zuhe_1314_3'];
    update_query("fn_lottery5", array("0027" => $p0027, '0126' => $p0126, '0225' => $p0225, '0324' => $p0324, '0423' => $p0423, '0522' => $p0522, '0621' => $p0621, '0720' => $p0720, '891819' => $p891819, '10111617' => $p10111617, '1215' => $p1215, '1314' => $p1314, 'jida' => $jida, 'jixiao' => $jixiao, 'baozi' => $baozi, 'duizi' => $duizi, 'shunzi' => $shunzi, 'dxds' => $dxds, 'dadan' => $dadan, 'xiaodan' => $xiaodan, 'dashuang' => $dashuang, 'xiaoshuang' => $xiaoshuang, 'dxds_zongzhu1' => $dxds_zongzhu1, 'dxds_zongzhu2' => $dxds_zongzhu2, 'dxds_zongzhu3' => $dxds_zongzhu3, 'dxds_1314_1' => $dxds_1314_1, 'dxds_1314_2' => $dxds_1314_2, 'dxds_1314_3' => $dxds_1314_3, 'zuhe_zongzhu1' => $zuhe_zongzhu1, 'zuhe_zongzhu2' => $zuhe_zongzhu2, 'zuhe_zongzhu3' => $zuhe_zongzhu3, 'zuhe_1314_1' => $zuhe_1314_1, 'zuhe_1314_2' => $zuhe_1314_2, 'zuhe_1314_3' => $zuhe_1314_3), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jnd28";</script>';
}elseif($_GET['form'] == 'form2'){
    $danzhu_min = $_POST['danzhu_min'];
    $zongzhu_max = $_POST['zongzhu_max'];
    $shuzi_max = $_POST['shuzi_max'];
    $zuhe_max = $_POST['zuhe_max'];
    $dxds_max = $_POST['dxds_max'];
    $jidx_max = $_POST['jidx_max'];
    $baozi_max = $_POST['baozi_max'];
    $shunzi_max = $_POST['shunzi_max'];
    $duizi_max = $_POST['duizi_max'];
    $setting_shazuhe = $_POST['setting_shazuhe'] == 'on' ? 'true' : 'false';
    $setting_tongxiangzuhe = $_POST['setting_tongxiangzuhe'] == 'on' ? 'true' : 'false';
    $setting_fanxiangzuhe = $_POST['setting_fanxiangzuhe'] == 'on' ? 'true' : 'false';
    $setting_liwai = $_POST['setting_liwai'];
    update_query("fn_lottery5", array("danzhu_min" => $danzhu_min, 'zongzhu_max' => $zongzhu_max, 'shuzi_max' => $shuzi_max, 'zuhe_max' => $zuhe_max, 'dxds_max' => $dxds_max, 'jidx_max' => $jidx_max, 'baozi_max' => $baozi_max, 'shunzi_max' => $shunzi_max, 'duizi_max' => $duizi_max, 'setting_shazuhe' => $setting_shazuhe, 'setting_tongxiangzuhe' => $setting_tongxiangzuhe, 'setting_fanxiangzuhe' => $setting_fanxiangzuhe, 'setting_liwai' => $setting_liwai), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jnd28";</script>';
}elseif($_GET['form'] == 'form3'){
    $gameopen = $_POST['gameopen'] == 'on' ? 'true' : 'false';
    $fengtime = $_POST['fengtime'];
    update_query("fn_lottery5", array("fengtime" => $fengtime, 'gameopen' => $gameopen), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jnd28";</script>';
}elseif($_GET['form'] == 'form4'){
    $content = $_POST['customcont'];
    update_query("fn_lottery5", array("rules" => $content), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jnd28";</script>';
}
?>