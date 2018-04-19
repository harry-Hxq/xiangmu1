<?php
include(dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php");
if($_GET['form'] == 'form1'){
    $da = $_POST['peilv_da'];
    $xiao = $_POST['peilv_xiao'];
    $dan = $_POST['peilv_dan'];
    $shuang = $_POST['peilv_shuang'];
    $dadan = $_POST['peilv_dadan'];
    $xiaodan = $_POST['peilv_xiaodan'];
    $dashuang = $_POST['peilv_dashuang'];
    $xiaoshuang = $_POST['peilv_xiaoshuang'];
	$setting_10shazuhe = $_POST['setting_10shazuhe'] == 'on' ? 'true' : 'false';
	$setting_baozitongsha = $_POST['setting_baozitongsha'] == 'on' ? 'true' : 'false';
    $tong_baozi = $_POST['peilv_baozi_tong'];
    $tong_shunzi = $_POST['peilv_shunzi_tong'];
    $tong_duizi = $_POST['peilv_duizi_tong'];
    $tong_sanza = $_POST['peilv_sanza_tong'];
    $tong_erza = $_POST['peilv_erza_tong'];
    $zhi_baozi = $_POST['peilv_baozi_zhi'];
    $zhi_shunzi = $_POST['peilv_shunzi_zhi'];
    $zhi_duizi = $_POST['peilv_duizi_zhi'];
    $zhi_sanza = $_POST['peilv_sanza_zhi'];
    $zhi_erza = $_POST['peilv_erza_zhi'];
    $zhi_sanjun = $_POST['peilv_sanjun_zhi'];
    
    update_query("fn_lottery9", array("da" => $da, 'xiao' => $xiao, 'dan' => $shuang, 'dadan' => $dadan, 'xiaodan' => $xiaodan, 'dashuang' => $dashuang, 'xiaoshuang' => $xiaoshuang, 'setting_10shazuhe' => $setting_10shazuhe, 'setting_baozitongsha' => $setting_baozitongsha, 'tong_baozi' => $tong_baozi, 'tong_shunzi' => $tong_shunzi, 'tong_duizi' => $tong_duizi, 'tong_sanza' => $tong_sanza, 'tong_erza' => $tong_erza, 'zhi_baozi' => $zhi_baozi, 'zhi_shunzi' => $zhi_shunzi, 'zhi_duizi' => $zhi_duizi, 'zhi_sanza' => $zhi_sanza, 'zhi_erza' => $zhi_erza, 'zhi_sanjun' => $zhi_sanjun), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=kuai3";</script>';
}elseif($_GET['form'] == 'form2'){
    $dx_min = $_POST['daxiao_min'];
    $dx_max = $_POST['daxiao_max'];
    $ds_min = $_POST['danshuang_min'];
    $ds_max = $_POST['danshuang_max'];
    $dadan_min = $_POST['dadan_min'];
    $dadan_max = $_POST['dadan_max'];
    $xiaodan_min = $_POST['xiaodan_min'];
    $xiaodan_max = $_POST['xiaodan_max'];
    $dashuang_min = $_POST['dashuang_min'];
	$dashuang_max = $_POST['dashuang_max'];
	$xiaoshuang_min = $_POST['xiaoshuang_min'];
	$xiaoshuang_max = $_POST['xiaoshuang_max'];
	$tong_baozi_min = $_POST['tong_baozi_min'];
	$tong_baozi_max = $_POST['tong_baozi_max'];
	$tong_shunzi_min = $_POST['tong_shunzi_min'];
	$tong_shunzi_max = $_POST['tong_shunzi_max'];
	$tong_duizi_min = $_POST['tong_duizi_min'];
	$tong_duizi_max = $_POST['tong_duizi_max'];
	$tong_sanza_min = $_POST['tong_sanza_min'];
	$tong_sanza_max = $_POST['tong_sanza_max'];
	$tong_erza_min = $_POST['tong_erza_min'];
	$tong_erza_max = $_POST['tong_erza_max'];
	$zhi_baozi_min = $_POST['zhi_baozi_min'];
	$zhi_baozi_max = $_POST['zhi_baozi_max'];
	$zhi_shunzi_min = $_POST['zhi_shunzi_min'];
	$zhi_shunzi_max = $_POST['zhi_shunzi_max'];
	$zhi_duizi_min = $_POST['zhi_duizi_min'];
	$zhi_duizi_max = $_POST['zhi_duizi_max'];
	$zhi_sanza_min = $_POST['zhi_sanza_min'];
	$zhi_sanza_max = $_POST['zhi_sanza_max'];
	$zhi_erza_min = $_POST['zhi_erza_min'];
	$zhi_erza_max = $_POST['zhi_erza_max'];
	$zhi_sanjun_min = $_POST['zhi_sanjun_min'];
	$zhi_sanjun_max = $_POST['zhi_sanjun_max'];

    update_query("fn_lottery9", array("dx_min" => $dx_min, 'dx_max' => $dx_max, 'ds_min' => $ds_min, 'ds_max' => $ds_max, 'dadan_min' => $dadan_min, 'dadan_max' => $dadan_max, 'xiaodan_min' => $xiaodan_min, 'xiaodan_max' => $xiaodan_max, 'dashuang_min' => $dashuang_min, 'dashuang_max' => $dashuang_max, 'xiaoshuang_min' => $xiaoshuang_min, 'xiaoshuang_max' => $xiaoshuang_max, 'tong_baozi_min' => $tong_baozi_min, 'tong_baozi_max' => $tong_baozi_max, 'tong_shunzi_min' => $tong_shunzi_min, 'tong_shunzi_max' => $tong_shunzi_max, 'tong_duizi_min' => $tong_duizi_min, 'tong_duizi_max' => $tong_duizi_max, 'tong_sanza_min' => $tong_sanza_min, 'tong_sanza_max' => $tong_sanza_max, 'tong_erza_min' => $tong_erza_min, 'tong_erza_max' => $tong_erza_max, 'zhi_baozi_min' => $zhi_baozi_min, 'zhi_baozi_max' => $zhi_baozi_max, 'zhi_shunzi_min' => $zhi_shunzi_min, 'zhi_shunzi_max' => $zhi_shunzi_max, 'zhi_duizi_min' => $zhi_duizi_min, 'zhi_duizi_max' => $zhi_duizi_max, 'zhi_sanza_min' => $zhi_sanza_min, 'zhi_sanza_max' => $zhi_sanza_max, 'zhi_erza_min' => $zhi_erza_min, 'zhi_erza_max' => $zhi_erza_max, 'zhi_sanjun_min' => $zhi_sanjun_min, 'zhi_sanjun_max' => $zhi_sanjun_max), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=kuai3";</script>';
}elseif($_GET['form'] == 'form3'){
    $gameopen = $_POST['opengame'] == 'on' ? 'true' : 'false';
    $fengtime = $_POST['fengtime'];
	$setting_open = $_POST['setting_open'];
    update_query("fn_lottery9", array("setting_open" => $setting_open, "fengtime" => $fengtime, 'gameopen' => $gameopen), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=kuai3";</script>';
}elseif($_GET['form'] == 'form4'){
    $content = $_POST['customcont'];
    update_query("fn_lottery9", array("rules" => $content), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=kuai3";</script>';
}
?>