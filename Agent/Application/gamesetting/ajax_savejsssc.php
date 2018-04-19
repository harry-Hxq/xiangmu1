<?php
include(dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php");
if($_GET['form'] == 'form1'){
    $da = $_POST['peilv_da'];
    $xiao = $_POST['peilv_xiao'];
    $dan = $_POST['peilv_dan'];
    $shuang = $_POST['peilv_shuang'];
    $tema = $_POST['peilv_tema'];
    $zongda = $_POST['peilv_zongda'];
    $zongxiao = $_POST['peilv_zongxiao'];
    $zongdan = $_POST['peilv_zongdan'];
    $zongshuang = $_POST['peilv_zongshuang'];
    $long = $_POST['peilv_long'];
    $hu = $_POST['peilv_hu'];
    $he = $_POST['peilv_he'];
    $qbaozi = $_POST['peilv_qbaozi'];
    $zbaozi = $_POST['peilv_zbaozi'];
    $hbaozi = $_POST['peilv_hbaozi'];
    $qduizi = $_POST['peilv_qduizi'];
    $zduizi = $_POST['peilv_zduizi'];
    $hduizi = $_POST['peilv_hduizi'];
    $qshunzi = $_POST['peilv_qshunzi'];
    $zshunzi = $_POST['peilv_zshunzi'];
    $hshunzi = $_POST['peilv_hshunzi'];
    $qbanshun = $_POST['peilv_qbanshun'];
    $zbanshun = $_POST['peilv_zbanshun'];
    $hbanshun = $_POST['peilv_hbanshun'];
    $qzaliu = $_POST['peilv_qzaliu'];
    $zzaliu = $_POST['peilv_zzaliu'];
    $hzaliu = $_POST['peilv_hzaliu'];
    update_query("fn_lottery8", array("da" => $da, 'xiao' => $xiao, 'dan' => $dan, 'shuang' => $shuang, 'tema' => $tema, 'zongda' => $zongda, 'zongxiao' => $zongxiao, 'zongdan' => $zongdan, 'zongshuang' => $zongshuang, 'long' => $long, 'hu' => $hu, 'he' => $he, 'q_baozi' => $qbaozi, 'z_baozi' => $zbaozi, 'h_baozi' => $hbaozi, 'q_shunzi' => $qshunzi, 'z_shunzi' => $zshunzi, 'h_shunzi' => $hshunzi, 'q_duizi' => $qduizi, 'z_duizi' => $zduizi, 'h_duizi' => $hduizi, 'q_banshun' => $qbanshun, 'z_banshun' => $zbanshun, 'h_banshun' => $hbanshun, 'q_zaliu' => $qzaliu, 'z_zaliu' => $zzaliu, 'h_zaliu' => $hzaliu), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsssc";</script>';
}elseif($_GET['form'] == 'form2'){
    $dx_min = $_POST['daxiao_min'];
    $dx_max = $_POST['daxiao_max'];
    $ds_min = $_POST['danshuang_min'];
    $ds_max = $_POST['danshuang_max'];
    $lh_min = $_POST['longhu_min'];
    $lh_max = $_POST['longhu_max'];
    $tm_min = $_POST['tema_min'];
    $tm_max = $_POST['tema_max'];
    $zh_min = $_POST['zonghe_min'];
    $zh_max = $_POST['zonghe_max'];
    $bz_min = $_POST['baozi_min'];
    $bz_max = $_POST['baozi_max'];
    $dz_min = $_POST['duizi_min'];
    $dz_max = $_POST['duizi_max'];
    $sz_min = $_POST['shunzi_min'];
    $sz_max = $_POST['shunzi_max'];
    $bs_min = $_POST['banshun_min'];
    $bs_max = $_POST['banshun_max'];
    $zl_min = $_POST['zaliu_min'];
    $zl_max = $_POST['zaliu_max'];
    update_query("fn_lottery8", array("dx_min" => $dx_min, 'dx_max' => $dx_max, 'ds_min' => $ds_min, 'ds_max' => $ds_max, 'lh_min' => $lh_min, 'lh_max' => $lh_max, 'tm_min' => $tm_min, 'tm_max' => $tm_max, 'zh_min' => $zh_min, 'zh_max' => $zh_max, 'bz_min' => $bz_min, 'bz_max' => $bz_max, 'dz_min' => $dz_min, 'dz_max' => $dz_max, 'sz_min' => $sz_min, 'sz_max' => $sz_max, 'bs_min' => $bs_min, 'bs_max' => $bs_max, 'zl_min' => $zl_min, 'zl_max' => $zl_max,), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsssc";</script>';
}elseif($_GET['form'] == 'form3'){
    $open = $_POST['opengame'] == 'on' ? 'true' : 'false';
    $fengtime = $_POST['fengtime'];
    update_query("fn_lottery8", array("fengtime" => $fengtime, 'gameopen' => $open), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsssc";</script>';
}elseif($_GET['form'] == 'form4'){
    $content = $_POST['customcont'];
    update_query("fn_lottery8", array("rules" => $content), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsssc";</script>';
}
?>