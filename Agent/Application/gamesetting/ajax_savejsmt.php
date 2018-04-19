<?php
include(dirname(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__))))) . "/Public/config.php");
if($_GET['form'] == 'form1'){
    $da = $_POST['peilv_da'];
    $xiao = $_POST['peilv_xiao'];
    $dan = $_POST['peilv_dan'];
    $shuang = $_POST['peilv_shuang'];
    $long = $_POST['peilv_long'];
    $hu = $_POST['peilv_hu'];
    $dadan = $_POST['peilv_dadan'];
    $xiaodan = $_POST['peilv_xiaodan'];
    $dashuang = $_POST['peilv_dashuang'];
    $xiaoshuang = $_POST['peilv_xiaoshuang'];
    $heda = $_POST['peilv_heda'];
    $hexiao = $_POST['peilv_hexiao'];
    $hedan = $_POST['peilv_hedan'];
    $heshuang = $_POST['peilv_heshuang'];
    $he341819 = $_POST['peilv_341819'];
    $he561617 = $_POST['peilv_561617'];
    $he781415 = $_POST['peilv_781415'];
    $he9101213 = $_POST['peilv_9101213'];
    $he11 = $_POST['peilv_11'];
    $tema = $_POST['peilv_tema'];
    update_query("fn_lottery6", array("da" => $da, 'xiao' => $xiao, 'dan' => $dan, 'shuang' => $shuang, 'long' => $long, 'hu' => $hu, 'dadan' => $dadan, 'xiaodan' => $xiaodan, 'dashuang' => $dashuang, 'xiaoshuang' => $xiaoshuang, 'heda' => $heda, 'hexiao' => $hexiao, 'hedan' => $hedan, 'heshuang' => $heshuang, 'he341819' => $he341819, 'he561617' => $he561617, 'he781415' => $he781415, 'he9101213' => $he9101213, 'he11' => $he11, 'tema' => $tema), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsmt";</script>';
}elseif($_GET['form'] == 'form2'){
    $daxiao_min = $_POST['daxiao_min'];
    $daxiao_max = $_POST['daxiao_max'];
    $danshuang_min = $_POST['danshuang_min'];
    $danshuang_max = $_POST['danshuang_max'];
    $longhu_min = $_POST['longhu_min'];
    $longhu_max = $_POST['longhu_max'];
    $tema_min = $_POST['tema_min'];
    $tema_max = $_POST['tema_max'];
    $he_min = $_POST['he_min'];
    $he_max = $_POST['he_max'];
    $zuhe_min = $_POST['zuhe_min'];
    $zuhe_max = $_POST['zuhe_max'];
    update_query("fn_lottery6", array("daxiao_min" => $daxiao_min, 'daxiao_max' => $daxiao_max, 'danshuang_min' => $danshuang_min, 'danshuang_max' => $danshuang_max, 'longhu_min' => $longhu_min, 'longhu_max' => $longhu_max, 'tema_min' => $tema_min, 'tema_max' => $tema_max, 'he_min' => $he_min, 'he_max' => $he_max, 'zuhe_min' => $zuhe_min, 'zuhe_max' => $zuhe_max), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsmt";</script>';
}elseif($_GET['form'] == 'form3'){
    $open = $_POST['opengame'] == 'on' ? 'true' : 'false';
    $fengtime = $_POST['fengtime'];
    update_query("fn_lottery6", array("fengtime" => $fengtime, 'gameopen' => $open), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsmt";</script>';
}elseif($_GET['form'] == 'form4'){
    $content = $_POST['customcont'];
    update_query("fn_lottery6", array("rules" => $content), array('roomid' => $_SESSION['agent_room']));
    echo '<script>alert("保存成功~感谢使用!"); window.location.href="/Agent/index.php?m=g_setting&g=jsmt";</script>';
}
?>