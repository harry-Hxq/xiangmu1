<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$room = $_SESSION['agent_room'];
$duichong = $_GET['dui'] == '' ? 'false' : 'true';
if($_GET['t'] == 'getterm'){
    if($_GET['code'] == 'pk10'){
        $term = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
        $money = (int)get_query_val('fn_order', 'sum(`money`)', "length(`term`) < 8 and status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'xyft'){
        $term = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
        $money = (int)get_query_val('fn_order', 'sum(`money`)', "length(`term`) > 8 and status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'cqssc'){
        $term = get_query_val('fn_open', 'next_term', "type = 3 order by term desc limit 1");
        $money = (int)get_query_val('fn_sscorder', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'xy28'){
        $term = get_query_val('fn_open', 'next_term', "type = 4 order by term desc limit 1");
        $money = (int)get_query_val('fn_pcorder', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'jnd28'){
        $term = get_query_val('fn_open', 'next_term', "type = 5 order by term desc limit 1");
        $money = (int)get_query_val('fn_pcorder', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'jsmt'){
        $term = get_query_val('fn_open', 'next_term', "type = 6 order by term desc limit 1");
        $money = (int)get_query_val('fn_mtorder', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'jssc'){
        $term = get_query_val('fn_open', 'next_term', "type = 7 order by term desc limit 1");
        $money = (int)get_query_val('fn_order', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'jsssc'){
        $term = get_query_val('fn_open', 'next_term', "type = 8 order by term desc limit 1");
        $money = (int)get_query_val('fn_sscorder', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }elseif($_GET['code'] == 'kuai3'){
        $term = get_query_val('fn_open', 'next_term', "type = 9 order by term desc limit 1");
        $money = (int)get_query_val('fn_k3order', 'sum(`money`)', "status = '未结算' and roomid = '$room' and term = '$term'");
    }
	
    echo $term . '|' . $money;
    exit();
}
if($_GET['code'] == 'pk10' || $_GET['code'] == 'xyft' || $_GET['code'] == 'jsmt' || $_GET['code'] == 'jssc'){
    switch($_GET['code']){
    case 'pk10': $type = 1;
        break;
    case "xyft": $type = 2;
        break;
    case "jsmt": $type = 6;
        break;
    case "jssc": $type = 7;
        break;
    }
    $term = get_query_val('fn_open', 'next_term', "type = $type order by term desc limit 1");
    $yi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $er = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $san = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $si = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $wu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $liu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $qi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $ba = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $jiu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $shi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '大单' => 0, '小单' => 0, '大双' => 0, '小双' => 0);
    $he = array('3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    if($_GET['code'] == 'jsmt'){
    select_query('fn_mtorder', '*', "roomid = $room and `status` = '未结算' and term = '$term' and jia = 'false'");
}elseif($_GET['code'] == 'jssc'){
    select_query('fn_jsscorder', '*', "roomid = $room and `status` = '未结算' and term = '$term' and jia = 'false'");
}else{
    select_query("fn_order", '*', "roomid = $room and `status` = '未结算' and term = '$term' and jia = 'false'");
}while($con = db_fetch_array()){
    if($con['mingci'] == '1'){
        switch($con['content']){
        case '1': $yi['1'] += $con['money'];
            break;
        case '2': $yi['2'] += $con['money'];
            break;
        case '3': $yi['3'] += $con['money'];
            break;
        case '4': $yi['4'] += $con['money'];
            break;
        case '5': $yi['5'] += $con['money'];
            break;
        case '6': $yi['6'] += $con['money'];
            break;
        case '7': $yi['7'] += $con['money'];
            break;
        case '8': $yi['8'] += $con['money'];
            break;
        case '9': $yi['9'] += $con['money'];
            break;
        case '0': $yi['0'] += $con['money'];
            break;
        case "大": $yi['大'] += $con['money'];
            break;
        case "小": $yi['小'] += $con['money'];
            break;
        case "单": $yi['单'] += $con['money'];
            break;
        case "双": $yi['双'] += $con['money'];
            break;
        case "龙": $yi['龙'] += $con['money'];
            break;
        case "虎": $yi['虎'] += $con['money'];
            break;
        case "小单": $yi['大单'] += $con['money'];
            break;
        case "大单": $yi['小单'] += $con['money'];
            break;
        case "小双": $yi['大双'] += $con['money'];
            break;
        case "大双": $yi['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '2'){
        switch($con['content']){
        case '1': $er['1'] += $con['money'];
            break;
        case '2': $er['2'] += $con['money'];
            break;
        case '3': $er['3'] += $con['money'];
            break;
        case '4': $er['4'] += $con['money'];
            break;
        case '5': $er['5'] += $con['money'];
            break;
        case '6': $er['6'] += $con['money'];
            break;
        case '7': $er['7'] += $con['money'];
            break;
        case '8': $er['8'] += $con['money'];
            break;
        case '9': $er['9'] += $con['money'];
            break;
        case '0': $er['0'] += $con['money'];
            break;
        case "大": $er['大'] += $con['money'];
            break;
        case "小": $er['小'] += $con['money'];
            break;
        case "单": $er['单'] += $con['money'];
            break;
        case "双": $er['双'] += $con['money'];
            break;
        case "龙": $er['龙'] += $con['money'];
            break;
        case "虎": $er['虎'] += $con['money'];
            break;
        case "小单": $er['大单'] += $con['money'];
            break;
        case "大单": $er['小单'] += $con['money'];
            break;
        case "小双": $er['大双'] += $con['money'];
            break;
        case "大双": $er['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '3'){
        switch($con['content']){
        case '1': $san['1'] += $con['money'];
            break;
        case '2': $san['2'] += $con['money'];
            break;
        case '3': $san['3'] += $con['money'];
            break;
        case '4': $san['4'] += $con['money'];
            break;
        case '5': $san['5'] += $con['money'];
            break;
        case '6': $san['6'] += $con['money'];
            break;
        case '7': $san['7'] += $con['money'];
            break;
        case '8': $san['8'] += $con['money'];
            break;
        case '9': $san['9'] += $con['money'];
            break;
        case '0': $san['0'] += $con['money'];
            break;
        case "大": $san['大'] += $con['money'];
            break;
        case "小": $san['小'] += $con['money'];
            break;
        case "单": $san['单'] += $con['money'];
            break;
        case "双": $san['双'] += $con['money'];
            break;
        case "龙": $san['龙'] += $con['money'];
            break;
        case "虎": $san['虎'] += $con['money'];
            break;
        case "小单": $san['大单'] += $con['money'];
            break;
        case "大单": $san['小单'] += $con['money'];
            break;
        case "小双": $san['大双'] += $con['money'];
            break;
        case "大双": $san['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '4'){
        switch($con['content']){
        case '1': $si['1'] += $con['money'];
            break;
        case '2': $si['2'] += $con['money'];
            break;
        case '3': $si['3'] += $con['money'];
            break;
        case '4': $si['4'] += $con['money'];
            break;
        case '5': $si['5'] += $con['money'];
            break;
        case '6': $si['6'] += $con['money'];
            break;
        case '7': $si['7'] += $con['money'];
            break;
        case '8': $si['8'] += $con['money'];
            break;
        case '9': $si['9'] += $con['money'];
            break;
        case '0': $si['0'] += $con['money'];
            break;
        case "大": $si['大'] += $con['money'];
            break;
        case "小": $si['小'] += $con['money'];
            break;
        case "单": $si['单'] += $con['money'];
            break;
        case "双": $si['双'] += $con['money'];
            break;
        case "龙": $si['龙'] += $con['money'];
            break;
        case "虎": $si['虎'] += $con['money'];
            break;
        case "小单": $si['大单'] += $con['money'];
            break;
        case "大单": $si['小单'] += $con['money'];
            break;
        case "小双": $si['大双'] += $con['money'];
            break;
        case "大双": $si['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '5'){
        switch($con['content']){
        case '1': $wu['1'] += $con['money'];
            break;
        case '2': $wu['2'] += $con['money'];
            break;
        case '3': $wu['3'] += $con['money'];
            break;
        case '4': $wu['4'] += $con['money'];
            break;
        case '5': $wu['5'] += $con['money'];
            break;
        case '6': $wu['6'] += $con['money'];
            break;
        case '7': $wu['7'] += $con['money'];
            break;
        case '8': $wu['8'] += $con['money'];
            break;
        case '9': $wu['9'] += $con['money'];
            break;
        case '0': $wu['0'] += $con['money'];
            break;
        case "大": $wu['大'] += $con['money'];
            break;
        case "小": $wu['小'] += $con['money'];
            break;
        case "单": $wu['单'] += $con['money'];
            break;
        case "双": $wu['双'] += $con['money'];
            break;
        case "龙": $wu['龙'] += $con['money'];
            break;
        case "虎": $wu['虎'] += $con['money'];
            break;
        case "小单": $wu['大单'] += $con['money'];
            break;
        case "大单": $wu['小单'] += $con['money'];
            break;
        case "小双": $wu['大双'] += $con['money'];
            break;
        case "大双": $wu['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '6'){
        switch($con['content']){
        case '1': $liu['1'] += $con['money'];
            break;
        case '2': $liu['2'] += $con['money'];
            break;
        case '3': $liu['3'] += $con['money'];
            break;
        case '4': $liu['4'] += $con['money'];
            break;
        case '5': $liu['5'] += $con['money'];
            break;
        case '6': $liu['6'] += $con['money'];
            break;
        case '7': $liu['7'] += $con['money'];
            break;
        case '8': $liu['8'] += $con['money'];
            break;
        case '9': $liu['9'] += $con['money'];
            break;
        case '0': $liu['0'] += $con['money'];
            break;
        case "大": $liu['大'] += $con['money'];
            break;
        case "小": $liu['小'] += $con['money'];
            break;
        case "单": $liu['单'] += $con['money'];
            break;
        case "双": $liu['双'] += $con['money'];
            break;
        case "小单": $liu['大单'] += $con['money'];
            break;
        case "大单": $liu['小单'] += $con['money'];
            break;
        case "小双": $liu['大双'] += $con['money'];
            break;
        case "大双": $liu['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '7'){
        switch($con['content']){
        case '1': $qi['1'] += $con['money'];
            break;
        case '2': $qi['2'] += $con['money'];
            break;
        case '3': $qi['3'] += $con['money'];
            break;
        case '4': $qi['4'] += $con['money'];
            break;
        case '5': $qi['5'] += $con['money'];
            break;
        case '6': $qi['6'] += $con['money'];
            break;
        case '7': $qi['7'] += $con['money'];
            break;
        case '8': $qi['8'] += $con['money'];
            break;
        case '9': $qi['9'] += $con['money'];
            break;
        case '0': $qi['0'] += $con['money'];
            break;
        case "大": $qi['大'] += $con['money'];
            break;
        case "小": $qi['小'] += $con['money'];
            break;
        case "单": $qi['单'] += $con['money'];
            break;
        case "双": $qi['双'] += $con['money'];
            break;
        case "小单": $qi['大单'] += $con['money'];
            break;
        case "大单": $qi['小单'] += $con['money'];
            break;
        case "小双": $qi['大双'] += $con['money'];
            break;
        case "大双": $qi['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '8'){
        switch($con['content']){
        case '1': $ba['1'] += $con['money'];
            break;
        case '2': $ba['2'] += $con['money'];
            break;
        case '3': $ba['3'] += $con['money'];
            break;
        case '4': $ba['4'] += $con['money'];
            break;
        case '5': $ba['5'] += $con['money'];
            break;
        case '6': $ba['6'] += $con['money'];
            break;
        case '7': $ba['7'] += $con['money'];
            break;
        case '8': $ba['8'] += $con['money'];
            break;
        case '9': $ba['9'] += $con['money'];
            break;
        case '0': $ba['0'] += $con['money'];
            break;
        case "大": $ba['大'] += $con['money'];
            break;
        case "小": $ba['小'] += $con['money'];
            break;
        case "单": $ba['单'] += $con['money'];
            break;
        case "双": $ba['双'] += $con['money'];
            break;
        case "小单": $ba['大单'] += $con['money'];
            break;
        case "大单": $ba['小单'] += $con['money'];
            break;
        case "小双": $ba['大双'] += $con['money'];
            break;
        case "大双": $ba['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '9'){
        switch($con['content']){
        case '1': $jiu['1'] += $con['money'];
            break;
        case '2': $jiu['2'] += $con['money'];
            break;
        case '3': $jiu['3'] += $con['money'];
            break;
        case '4': $jiu['4'] += $con['money'];
            break;
        case '5': $jiu['5'] += $con['money'];
            break;
        case '6': $jiu['6'] += $con['money'];
            break;
        case '7': $jiu['7'] += $con['money'];
            break;
        case '8': $jiu['8'] += $con['money'];
            break;
        case '9': $jiu['9'] += $con['money'];
            break;
        case '0': $jiu['0'] += $con['money'];
            break;
        case "大": $jiu['大'] += $con['money'];
            break;
        case "小": $jiu['小'] += $con['money'];
            break;
        case "单": $jiu['单'] += $con['money'];
            break;
        case "双": $jiu['双'] += $con['money'];
            break;
        case "小单": $jiu['大单'] += $con['money'];
            break;
        case "大单": $jiu['小单'] += $con['money'];
            break;
        case "小双": $jiu['大双'] += $con['money'];
            break;
        case "大双": $jiu['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '0'){
        switch($con['content']){
        case '1': $shi['1'] += $con['money'];
            break;
        case '2': $shi['2'] += $con['money'];
            break;
        case '3': $shi['3'] += $con['money'];
            break;
        case '4': $shi['4'] += $con['money'];
            break;
        case '5': $shi['5'] += $con['money'];
            break;
        case '6': $shi['6'] += $con['money'];
            break;
        case '7': $shi['7'] += $con['money'];
            break;
        case '8': $shi['8'] += $con['money'];
            break;
        case '9': $shi['9'] += $con['money'];
            break;
        case '0': $shi['0'] += $con['money'];
            break;
        case "大": $shi['大'] += $con['money'];
            break;
        case "小": $shi['小'] += $con['money'];
            break;
        case "单": $shi['单'] += $con['money'];
            break;
        case "双": $shi['双'] += $con['money'];
            break;
        case "小单": $shi['大单'] += $con['money'];
            break;
        case "大单": $shi['小单'] += $con['money'];
            break;
        case "小双": $shi['大双'] += $con['money'];
            break;
        case "大双": $shi['小双'] += $con['money'];
            break;
        }
        continue;
    }elseif($con['mingci'] == '和'){
        switch($con['content']){
        case '3': $he['3'] += $con['money'];
            break;
        case '4': $he['4'] += $con['money'];
            break;
        case '5': $he['5'] += $con['money'];
            break;
        case '6': $he['6'] += $con['money'];
            break;
        case '7': $he['7'] += $con['money'];
            break;
        case '8': $he['8'] += $con['money'];
            break;
        case '9': $he['9'] += $con['money'];
            break;
        case "10": $he['10'] += $con['money'];
            break;
        case "11": $he['11'] += $con['money'];
            break;
        case "12": $he['12'] += $con['money'];
            break;
        case "13": $he['13'] += $con['money'];
            break;
        case "14": $he['14'] += $con['money'];
            break;
        case "15": $he['15'] += $con['money'];
            break;
        case "16": $he['16'] += $con['money'];
            break;
        case "17": $he['17'] += $con['money'];
            break;
        case "18": $he['18'] += $con['money'];
            break;
        case "19": $he['19'] += $con['money'];
            break;
        case "大": $he['大'] += $con['money'];
            break;
        case "小": $he['小'] += $con['money'];
            break;
        case "单": $he['单'] += $con['money'];
            break;
        case "双": $he['双'] += $con['money'];
            break;
        }
        continue;
    }
}
if($duichong == 'true'){
    $arr = array($yi['1'], $yi['2'], $yi['3'], $yi['4'], $yi['5'], $yi['6'], $yi['7'], $yi['8'], $yi['9'], $yi['0']);
    sort($arr);
    $yi['1'] = $yi['1'] - $arr[0];
    $yi['2'] = $yi['2'] - $arr[0];
    $yi['3'] = $yi['3'] - $arr[0];
    $yi['4'] = $yi['4'] - $arr[0];
    $yi['5'] = $yi['5'] - $arr[0];
    $yi['6'] = $yi['6'] - $arr[0];
    $yi['7'] = $yi['7'] - $arr[0];
    $yi['8'] = $yi['8'] - $arr[0];
    $yi['9'] = $yi['9'] - $arr[0];
    $yi['0'] = $yi['0'] - $arr[0];
    $arr = array($er['1'], $er['2'], $er['3'], $er['4'], $er['5'], $er['6'], $er['7'], $er['8'], $er['9'], $er['0']);
    sort($arr);
    $er['1'] = $er['1'] - $arr[0];
    $er['2'] = $er['2'] - $arr[0];
    $er['3'] = $er['3'] - $arr[0];
    $er['4'] = $er['4'] - $arr[0];
    $er['5'] = $er['5'] - $arr[0];
    $er['6'] = $er['6'] - $arr[0];
    $er['7'] = $er['7'] - $arr[0];
    $er['8'] = $er['8'] - $arr[0];
    $er['9'] = $er['9'] - $arr[0];
    $er['0'] = $er['0'] - $arr[0];
    $arr = array($san['1'], $san['2'], $san['3'], $san['4'], $san['5'], $san['6'], $san['7'], $san['8'], $san['9'], $san['0']);
    sort($arr);
    $san['1'] = $san['1'] - $arr[0];
    $san['2'] = $san['2'] - $arr[0];
    $san['3'] = $san['3'] - $arr[0];
    $san['4'] = $san['4'] - $arr[0];
    $san['5'] = $san['5'] - $arr[0];
    $san['6'] = $san['6'] - $arr[0];
    $san['7'] = $san['7'] - $arr[0];
    $san['8'] = $san['8'] - $arr[0];
    $san['9'] = $san['9'] - $arr[0];
    $san['0'] = $san['0'] - $arr[0];
    $arr = array($si['1'], $si['2'], $si['3'], $si['4'], $si['5'], $si['6'], $si['7'], $si['8'], $si['9'], $si['0']);
    sort($arr);
    $si['1'] = $si['1'] - $arr[0];
    $si['2'] = $si['2'] - $arr[0];
    $si['3'] = $si['3'] - $arr[0];
    $si['4'] = $si['4'] - $arr[0];
    $si['5'] = $si['5'] - $arr[0];
    $si['6'] = $si['6'] - $arr[0];
    $si['7'] = $si['7'] - $arr[0];
    $si['8'] = $si['8'] - $arr[0];
    $si['9'] = $si['9'] - $arr[0];
    $si['0'] = $si['0'] - $arr[0];
    $arr = array($wu['1'], $wu['2'], $wu['3'], $wu['4'], $wu['5'], $wu['6'], $wu['7'], $wu['8'], $wu['9'], $wu['0']);
    sort($arr);
    $wu['1'] = $wu['1'] - $arr[0];
    $wu['2'] = $wu['2'] - $arr[0];
    $wu['3'] = $wu['3'] - $arr[0];
    $wu['4'] = $wu['4'] - $arr[0];
    $wu['5'] = $wu['5'] - $arr[0];
    $wu['6'] = $wu['6'] - $arr[0];
    $wu['7'] = $wu['7'] - $arr[0];
    $wu['8'] = $wu['8'] - $arr[0];
    $wu['9'] = $wu['9'] - $arr[0];
    $wu['0'] = $wu['0'] - $arr[0];
    $arr = array($liu['1'], $liu['2'], $liu['3'], $liu['4'], $liu['5'], $liu['6'], $liu['7'], $liu['8'], $liu['9'], $liu['0']);
    sort($arr);
    $liu['1'] = $liu['1'] - $arr[0];
    $liu['2'] = $liu['2'] - $arr[0];
    $liu['3'] = $liu['3'] - $arr[0];
    $liu['4'] = $liu['4'] - $arr[0];
    $liu['5'] = $liu['5'] - $arr[0];
    $liu['6'] = $liu['6'] - $arr[0];
    $liu['7'] = $liu['7'] - $arr[0];
    $liu['8'] = $liu['8'] - $arr[0];
    $liu['9'] = $liu['9'] - $arr[0];
    $liu['0'] = $liu['0'] - $arr[0];
    $arr = array($qi['1'], $qi['2'], $qi['3'], $qi['4'], $qi['5'], $qi['6'], $qi['7'], $qi['8'], $qi['9'], $qi['0']);
    sort($arr);
    $qi['1'] = $qi['1'] - $arr[0];
    $qi['2'] = $qi['2'] - $arr[0];
    $qi['3'] = $qi['3'] - $arr[0];
    $qi['4'] = $qi['4'] - $arr[0];
    $qi['5'] = $qi['5'] - $arr[0];
    $qi['6'] = $qi['6'] - $arr[0];
    $qi['7'] = $qi['7'] - $arr[0];
    $qi['8'] = $qi['8'] - $arr[0];
    $qi['9'] = $qi['9'] - $arr[0];
    $qi['0'] = $qi['0'] - $arr[0];
    $arr = array($ba['1'], $ba['2'], $ba['3'], $ba['4'], $ba['5'], $ba['6'], $ba['7'], $ba['8'], $ba['9'], $ba['0']);
    sort($arr);
    $ba['1'] = $ba['1'] - $arr[0];
    $ba['2'] = $ba['2'] - $arr[0];
    $ba['3'] = $ba['3'] - $arr[0];
    $ba['4'] = $ba['4'] - $arr[0];
    $ba['5'] = $ba['5'] - $arr[0];
    $ba['6'] = $ba['6'] - $arr[0];
    $ba['7'] = $ba['7'] - $arr[0];
    $ba['8'] = $ba['8'] - $arr[0];
    $ba['9'] = $ba['9'] - $arr[0];
    $ba['0'] = $ba['0'] - $arr[0];
    $arr = array($jiu['1'], $jiu['2'], $jiu['3'], $jiu['4'], $jiu['5'], $jiu['6'], $jiu['7'], $jiu['8'], $jiu['9'], $jiu['0']);
    sort($arr);
    $jiu['1'] = $jiu['1'] - $arr[0];
    $jiu['2'] = $jiu['2'] - $arr[0];
    $jiu['3'] = $jiu['3'] - $arr[0];
    $jiu['4'] = $jiu['4'] - $arr[0];
    $jiu['5'] = $jiu['5'] - $arr[0];
    $jiu['6'] = $jiu['6'] - $arr[0];
    $jiu['7'] = $jiu['7'] - $arr[0];
    $jiu['8'] = $jiu['8'] - $arr[0];
    $jiu['9'] = $jiu['9'] - $arr[0];
    $jiu['0'] = $jiu['0'] - $arr[0];
    $arr = array($shi['1'], $shi['2'], $shi['3'], $shi['4'], $shi['5'], $shi['6'], $shi['7'], $shi['8'], $shi['9'], $shi['0']);
    sort($arr);
    $shi['1'] = $shi['1'] - $arr[0];
    $shi['2'] = $shi['2'] - $arr[0];
    $shi['3'] = $shi['3'] - $arr[0];
    $shi['4'] = $shi['4'] - $arr[0];
    $shi['5'] = $shi['5'] - $arr[0];
    $shi['6'] = $shi['6'] - $arr[0];
    $shi['7'] = $shi['7'] - $arr[0];
    $shi['8'] = $shi['8'] - $arr[0];
    $shi['9'] = $shi['9'] - $arr[0];
    $shi['0'] = $shi['0'] - $arr[0];
    if($yi['大'] > $yi['小']){
        $yi['大'] = $yi['大'] - $yi['小'];
        $yi['小'] = 0;
    }elseif($yi['小'] > $yi['大']){
        $yi['小'] = $yi['小'] - $yi['大'];
        $yi['大'] = 0;
    }elseif($yi['大'] == $yi['小']){
        $yi['大'] = 0;
        $yi['小'] = 0;
    }
    if($yi['单'] > $yi['双']){
        $yi['单'] = $yi['单'] - $yi['双'];
        $yi['双'] = 0;
    }elseif($yi['双'] > $yi['单']){
        $yi['双'] = $yi['双'] - $yi['单'];
        $yi['单'] = 0;
    }elseif($yi['单'] == $yi['双']){
        $yi['单'] = 0;
        $yi['双'] = 0;
    }
    if($er['大'] > $er['小']){
        $er['大'] = $er['大'] - $er['小'];
        $er['小'] = 0;
    }elseif($er['小'] > $er['大']){
        $er['小'] = $er['小'] - $er['大'];
        $er['大'] = 0;
    }elseif($er['大'] == $er['小']){
        $er['大'] = 0;
        $er['小'] = 0;
    }
    if($er['单'] > $er['双']){
        $er['单'] = $er['单'] - $er['双'];
        $er['双'] = 0;
    }elseif($er['双'] > $er['单']){
        $er['双'] = $er['双'] - $er['单'];
        $er['单'] = 0;
    }elseif($er['单'] == $er['双']){
        $er['单'] = 0;
        $er['双'] = 0;
    }
    if($san['大'] > $san['小']){
        $san['大'] = $san['大'] - $san['小'];
        $san['小'] = 0;
    }elseif($san['小'] > $san['大']){
        $san['小'] = $san['小'] - $san['大'];
        $san['大'] = 0;
    }elseif($san['大'] == $san['小']){
        $san['大'] = 0;
        $san['小'] = 0;
    }
    if($san['单'] > $san['双']){
        $san['单'] = $san['单'] - $san['双'];
        $san['双'] = 0;
    }elseif($san['双'] > $san['单']){
        $san['双'] = $san['双'] - $san['单'];
        $san['单'] = 0;
    }elseif($san['单'] == $san['双']){
        $san['单'] = 0;
        $san['双'] = 0;
    }
    if($si['大'] > $si['小']){
        $si['大'] = $si['大'] - $si['小'];
        $si['小'] = 0;
    }elseif($si['小'] > $si['大']){
        $si['小'] = $si['小'] - $si['大'];
        $si['大'] = 0;
    }elseif($si['大'] == $si['小']){
        $si['大'] = 0;
        $si['小'] = 0;
    }
    if($si['单'] > $si['双']){
        $si['单'] = $si['单'] - $si['双'];
        $si['双'] = 0;
    }elseif($si['双'] > $si['单']){
        $si['双'] = $si['双'] - $si['单'];
        $si['单'] = 0;
    }elseif($si['单'] == $si['双']){
        $si['单'] = 0;
        $si['双'] = 0;
    }
    if($wu['大'] > $wu['小']){
        $wu['大'] = $wu['大'] - $wu['小'];
        $wu['小'] = 0;
    }elseif($wu['小'] > $wu['大']){
        $wu['小'] = $wu['小'] - $wu['大'];
        $wu['大'] = 0;
    }elseif($wu['大'] == $wu['小']){
        $wu['大'] = 0;
        $wu['小'] = 0;
    }
    if($wu['单'] > $wu['双']){
        $wu['单'] = $wu['单'] - $wu['双'];
        $wu['双'] = 0;
    }elseif($wu['双'] > $wu['单']){
        $wu['双'] = $wu['双'] - $wu['单'];
        $wu['单'] = 0;
    }elseif($wu['单'] == $wu['双']){
        $wu['单'] = 0;
        $wu['双'] = 0;
    }
    if($liu['大'] > $liu['小']){
        $liu['大'] = $liu['大'] - $liu['小'];
        $liu['小'] = 0;
    }elseif($liu['小'] > $liu['大']){
        $liu['小'] = $liu['小'] - $liu['大'];
        $liu['大'] = 0;
    }elseif($liu['大'] == $liu['小']){
        $liu['大'] = 0;
        $liu['小'] = 0;
    }
    if($liu['单'] > $liu['双']){
        $liu['单'] = $liu['单'] - $liu['双'];
        $liu['双'] = 0;
    }elseif($liu['双'] > $liu['单']){
        $liu['双'] = $liu['双'] - $liu['单'];
        $liu['单'] = 0;
    }elseif($liu['单'] == $liu['双']){
        $liu['单'] = 0;
        $liu['双'] = 0;
    }
    if($qi['大'] > $qi['小']){
        $qi['大'] = $qi['大'] - $qi['小'];
        $qi['小'] = 0;
    }elseif($qi['小'] > $qi['大']){
        $qi['小'] = $qi['小'] - $qi['大'];
        $qi['大'] = 0;
    }elseif($qi['大'] == $qi['小']){
        $qi['大'] = 0;
        $qi['小'] = 0;
    }
    if($qi['单'] > $qi['双']){
        $qi['单'] = $qi['单'] - $qi['双'];
        $qi['双'] = 0;
    }elseif($qi['双'] > $qi['单']){
        $qi['双'] = $qi['双'] - $qi['单'];
        $qi['单'] = 0;
    }elseif($qi['单'] == $qi['双']){
        $qi['单'] = 0;
        $qi['双'] = 0;
    }
    if($ba['大'] > $ba['小']){
        $ba['大'] = $ba['大'] - $ba['小'];
        $ba['小'] = 0;
    }elseif($ba['小'] > $ba['大']){
        $ba['小'] = $ba['小'] - $ba['大'];
        $ba['大'] = 0;
    }elseif($ba['大'] == $ba['小']){
        $ba['大'] = 0;
        $ba['小'] = 0;
    }
    if($ba['单'] > $ba['双']){
        $ba['单'] = $ba['单'] - $ba['双'];
        $ba['双'] = 0;
    }elseif($ba['双'] > $ba['单']){
        $ba['双'] = $ba['双'] - $ba['单'];
        $ba['单'] = 0;
    }elseif($ba['单'] == $ba['双']){
        $ba['单'] = 0;
        $ba['双'] = 0;
    }
    if($jiu['大'] > $jiu['小']){
        $jiu['大'] = $jiu['大'] - $jiu['小'];
        $jiu['小'] = 0;
    }elseif($jiu['小'] > $jiu['大']){
        $jiu['小'] = $jiu['小'] - $jiu['大'];
        $jiu['大'] = 0;
    }elseif($jiu['大'] == $jiu['小']){
        $jiu['大'] = 0;
        $jiu['小'] = 0;
    }
    if($jiu['单'] > $jiu['双']){
        $jiu['单'] = $jiu['单'] - $jiu['双'];
        $jiu['双'] = 0;
    }elseif($jiu['双'] > $jiu['单']){
        $jiu['双'] = $jiu['双'] - $jiu['单'];
        $jiu['单'] = 0;
    }elseif($jiu['单'] == $jiu['双']){
        $jiu['单'] = 0;
        $jiu['双'] = 0;
    }
    if($shi['大'] > $shi['小']){
        $shi['大'] = $shi['大'] - $shi['小'];
        $shi['小'] = 0;
    }elseif($shi['小'] > $shi['大']){
        $shi['小'] = $shi['小'] - $shi['大'];
        $shi['大'] = 0;
    }elseif($shi['大'] == $shi['小']){
        $shi['大'] = 0;
        $shi['小'] = 0;
    }
    if($shi['单'] > $shi['双']){
        $shi['单'] = $shi['单'] - $shi['双'];
        $shi['双'] = 0;
    }elseif($shi['双'] > $shi['单']){
        $shi['双'] = $shi['双'] - $shi['单'];
        $shi['单'] = 0;
    }elseif($shi['单'] == $shi['双']){
        $shi['单'] = 0;
        $shi['双'] = 0;
    }
    if($yi['龙'] > $yi['虎']){
        $yi['龙'] = $yi['龙'] - $yi['虎'];
        $yi['虎'] = 0;
    }elseif($yi['虎'] > $yi['龙']){
        $yi['虎'] = $yi['虎'] - $yi['龙'];
        $yi['龙'] = 0;
    }elseif($yi['龙'] == $yi['虎']){
        $yi['龙'] = 0;
        $yi['虎'] = 0;
    }
    if($er['龙'] > $er['虎']){
        $er['龙'] = $er['龙'] - $er['虎'];
        $er['虎'] = 0;
    }elseif($er['虎'] > $er['龙']){
        $er['虎'] = $er['虎'] - $er['龙'];
        $er['龙'] = 0;
    }elseif($er['龙'] == $er['虎']){
        $er['龙'] = 0;
        $er['虎'] = 0;
    }
    if($san['龙'] > $san['虎']){
        $san['龙'] = $san['龙'] - $san['虎'];
        $san['虎'] = 0;
    }elseif($san['虎'] > $san['龙']){
        $san['虎'] = $san['虎'] - $san['龙'];
        $san['龙'] = 0;
    }elseif($san['龙'] == $san['虎']){
        $san['龙'] = 0;
        $san['虎'] = 0;
    }
    if($si['龙'] > $si['虎']){
        $si['龙'] = $si['龙'] - $si['虎'];
        $si['虎'] = 0;
    }elseif($si['虎'] > $si['龙']){
        $si['虎'] = $si['虎'] - $si['龙'];
        $si['龙'] = 0;
    }elseif($si['龙'] == $si['虎']){
        $si['龙'] = 0;
        $si['虎'] = 0;
    }
    if($wu['龙'] > $wu['虎']){
        $wu['龙'] = $wu['龙'] - $wu['虎'];
        $wu['虎'] = 0;
    }elseif($wu['虎'] > $wu['龙']){
        $wu['虎'] = $wu['虎'] - $wu['龙'];
        $wu['龙'] = 0;
    }elseif($wu['龙'] == $wu['虎']){
        $wu['龙'] = 0;
        $wu['虎'] = 0;
    }
}
?>
<tr>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $yi['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $er['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $san['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $si['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $wu['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $liu['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $qi['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $ba['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $jiu['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_1">1</span></td>
  <td>
    <?php $m = $shi['1'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和3</span></td>
  <td>
    <?php $m = $he['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $yi['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $er['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $san['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $si['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $wu['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $liu['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $qi['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $ba['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $jiu['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_2">2</span></td>
  <td>
    <?php $m = $shi['2'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和4</span></td>
  <td>
    <?php $m = $he['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $yi['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $er['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $san['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $si['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $wu['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $liu['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $qi['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>   
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $ba['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $jiu['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_3">3</span></td>
  <td>
    <?php $m = $shi['3'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和5</span></td>
  <td>
    <?php $m = $he['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $yi['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $er['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $san['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $si['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $wu['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $liu['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $qi['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $ba['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $jiu['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_4">4</span></td>
  <td>
    <?php $m = $shi['4'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和6</span></td>
  <td>
    <?php $m = $he['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $yi['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $er['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $san['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $si['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $wu['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $liu['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $qi['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $ba['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $jiu['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_5">5</span></td>
  <td>
    <?php $m = $shi['5'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和7</span></td>
  <td>
    <?php $m = $he['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $yi['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $er['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $san['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $si['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $wu['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $liu['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $qi['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $ba['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $jiu['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_6">6</span></td>
  <td>
    <?php $m = $shi['6'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和8</span></td>
  <td>
    <?php $m = $he['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $yi['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $er['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $san['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $si['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $wu['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $liu['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $qi['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $ba['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $jiu['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_7">7</span></td>
  <td>
    <?php $m = $shi['7'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和9</span></td>
  <td>
    <?php $m = $he['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $yi['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $er['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $san['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $si['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $wu['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $liu['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $qi['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $ba['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $jiu['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_8">8</span></td>
  <td>
    <?php $m = $shi['8'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和10</span></td>
  <td>
    <?php $m = $he['10'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $yi['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $er['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $san['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $si['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $wu['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $liu['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $qi['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $ba['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $jiu['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_9">9</span></td>
  <td>
    <?php $m = $shi['9'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和11</span></td>
  <td>
    <?php $m = $he['11'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $yi['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $er['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $san['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $si['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $wu['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $liu['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $qi['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $ba['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $jiu['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td style="width:20px"><span class="pk_10">10</span></td>
  <td>
    <?php $m = $shi['0'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和12</span></td>
  <td>
    <?php $m = $he['12'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $yi['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $er['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $san['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $si['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $wu['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $liu['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $qi['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $ba['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $jiu['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大</span></td>
  <td>
    <?php $m = $shi['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和13</span></td>
  <td>
    <?php $m = $he['13'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $yi['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $er['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $san['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $si['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $wu['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $liu['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $qi['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $ba['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $jiu['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小</span></td>
  <td>
    <?php $m = $shi['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和14</span></td>
  <td>
    <?php $m = $he['14'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $yi['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $er['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $san['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $si['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $wu['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $liu['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $qi['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $ba['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $jiu['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">单</span></td>
  <td>
    <?php $m = $shi['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和15</span></td>
  <td>
    <?php $m = $he['15'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $yi['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $er['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $san['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $si['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $wu['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $liu['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>

    <?php $m = $qi['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $ba['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $jiu['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">双</span></td>
  <td>
    <?php $m = $shi['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和16</span></td>
  <td>
    <?php $m = $he['16'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $yi['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $er['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $san['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $si['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $wu['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $liu['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $qi['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $ba['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $jiu['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大单</span></td>
  <td>
    <?php $m = $shi['大单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和17</span></td>
  <td>
    <?php $m = $he['17'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $yi['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $er['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $san['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $si['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $wu['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $liu['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $qi['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $ba['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $jiu['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小单</span></td>
  <td>
    <?php $m = $shi['小单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和18</span></td>
  <td>
    <?php $m = $he['18'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $yi['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $er['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $san['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $si['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $wu['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $liu['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $qi['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $ba['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $jiu['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">大双</span></td>
  <td>
    <?php $m = $shi['大双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_he">和19</span></td>
  <td>
    <?php $m = $he['19'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $yi['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $er['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $san['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $si['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $wu['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $liu['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $qi['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $ba['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $jiu['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">小双</span></td>
  <td>
    <?php $m = $shi['小双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">和大</span></td>
  <td>
    <?php $m = $he['大'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_pink">龙</span></td>
  <td>
    <?php $m = $yi['龙'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">龙</span></td>
  <td>
    <?php $m = $er['龙'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">龙</span></td>
  <td>
    <?php $m = $san['龙'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">龙</span></td>
  <td>
    <?php $m = $si['龙'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_pink">龙</span></td>
  <td>
    <?php $m = $wu['龙'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><span class="pk_blue">和小</span></td>
  <td>
    <?php $m = $he['小'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td><span class="pk_blue">虎</span></td>
  <td>
    <?php $m = $yi['虎'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">虎</span></td>
  <td>
    <?php $m = $er['虎'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">虎</span></td>
  <td>
    <?php $m = $san['虎'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">虎</span></td>
  <td>
    <?php $m = $si['虎'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td><span class="pk_blue">虎</span></td>
  <td>
    <?php $m = $wu['虎'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><span class="pk_pink">和单</span></td>
  <td>
    <?php $m = $he['单'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><span class="pk_blue">和双</span></td>
  <td>
    <?php $m = $he['双'];
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
  </td>
</tr>
<?php }elseif($_GET['code'] == 'xy28' || $_GET['code'] == 'jnd28'){
$type = $_GET['code'] == 'xy28' ? 4 : 5;
$term = get_query_val('fn_open', 'next_term', "type = $type order by term desc limit 1");
?>
              <tr>
                <td style="width:30px"><span class="pcdd">0</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '0', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">1</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '1', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_pink">大</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '大', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_pink">极大</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '极大', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_pink">大单</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '大单', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_he">对子</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '对子', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">2</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '2', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">3</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '3', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_blue">小</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '小', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_blue">极小</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '极小', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_blue">小单</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '小单', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_he">豹子</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '豹子', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">4</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '4', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">5</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '5', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_pink">单</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '单', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td class="pk_pink">大双</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '大双', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_he">顺子</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '顺子', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">6</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '6', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">7</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '7', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td class="pk_blue">双</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '双', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td class="pk_blue">小双</td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '小双', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">8</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '8', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">9</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '9', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">10</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '10', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">11</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '11', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">12</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '12', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">13</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '13', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">14</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '14', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">15</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '15', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">16</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '16', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">17</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '17', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">18</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '18', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">19</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '19', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">20</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '20', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">21</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '21', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">22</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '22', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">23</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '23', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">24</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '24', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">25</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '25', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td style="width:30px"><span class="pcdd">26</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '26', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td style="width:30px"><span class="pcdd">27</span></td>
                <td>
                  <?php $m = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('content' => '27', 'roomid' => $room, 'term' => $term, 'jia' => 'false'));
if($m > 0){
    echo '<span class="money_y">' . $m . '</span>';
}else{
    echo '<span class="money_n">0</span>';
}
?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
<?php }elseif($_GET['code'] == 'cqssc' || $_GET['code'] == 'jsssc'){
$wan = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
$qian = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
$bai = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
$shi = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
$ge = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
$zong = array('大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '合' => 0);
$q3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
$z3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
$h3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
switch($_GET['code']){
case 'cqssc': $type = 3;
    break;
case "jsssc": $type = 8;
    break;
}
$term = get_query_val('fn_open', 'next_term', "type = '{$type}' order by term desc limit 1");
if($_GET['code'] == 'cqssc'){
select_query('fn_sscorder', '*', "roomid = $room and `status` = '未结算' and term = '$term' and jia = 'false'");
}elseif($_GET['code'] == 'jsssc'){
select_query('fn_jssscorder', '*', "roomid = $room and `status` = '未结算' and term = '$term' and jia = 'false'");
}while($con = db_fetch_array()){
if($con['mingci'] == '1'){
    switch($con['content']){
    case '0': $wan['0'] += $con['money'];
        break;
    case '1': $wan['1'] += $con['money'];
        break;
    case '2': $wan['2'] += $con['money'];
        break;
    case '3': $wan['3'] += $con['money'];
        break;
    case '4': $wan['4'] += $con['money'];
        break;
    case '5': $wan['5'] += $con['money'];
        break;
    case '6': $wan['6'] += $con['money'];
        break;
    case '7': $wan['7'] += $con['money'];
        break;
    case '8': $wan['8'] += $con['money'];
        break;
    case '9': $wan['9'] += $con['money'];
        break;
    case '0': $wan['0'] += $con['money'];
        break;
    case "大": $wan['大'] += $con['money'];
        break;
    case "小": $wan['小'] += $con['money'];
        break;
    case "单": $wan['单'] += $con['money'];
        break;
    case "双": $wan['双'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '2'){
    switch($con['content']){
    case '0': $qian['0'] += $con['money'];
        break;
    case '1': $qian['1'] += $con['money'];
        break;
    case '2': $qian['2'] += $con['money'];
        break;
    case '3': $qian['3'] += $con['money'];
        break;
    case '4': $qian['4'] += $con['money'];
        break;
    case '5': $qian['5'] += $con['money'];
        break;
    case '6': $qian['6'] += $con['money'];
        break;
    case '7': $qian['7'] += $con['money'];
        break;
    case '8': $qian['8'] += $con['money'];
        break;
    case '9': $qian['9'] += $con['money'];
        break;
    case '0': $qian['0'] += $con['money'];
        break;
    case "大": $qian['大'] += $con['money'];
        break;
    case "小": $qian['小'] += $con['money'];
        break;
    case "单": $qian['单'] += $con['money'];
        break;
    case "双": $qian['双'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '3'){
    switch($con['content']){
    case '0': $bai['0'] += $con['money'];
        break;
    case '1': $bai['1'] += $con['money'];
        break;
    case '2': $bai['2'] += $con['money'];
        break;
    case '3': $bai['3'] += $con['money'];
        break;
    case '4': $bai['4'] += $con['money'];
        break;
    case '5': $bai['5'] += $con['money'];
        break;
    case '6': $bai['6'] += $con['money'];
        break;
    case '7': $bai['7'] += $con['money'];
        break;
    case '8': $bai['8'] += $con['money'];
        break;
    case '9': $bai['9'] += $con['money'];
        break;
    case '0': $bai['0'] += $con['money'];
        break;
    case "大": $bai['大'] += $con['money'];
        break;
    case "小": $bai['小'] += $con['money'];
        break;
    case "单": $bai['单'] += $con['money'];
        break;
    case "双": $bai['双'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '4'){
    switch($con['content']){
    case '0': $shi['0'] += $con['money'];
        break;
    case '1': $shi['1'] += $con['money'];
        break;
    case '2': $shi['2'] += $con['money'];
        break;
    case '3': $shi['3'] += $con['money'];
        break;
    case '4': $shi['4'] += $con['money'];
        break;
    case '5': $shi['5'] += $con['money'];
        break;
    case '6': $shi['6'] += $con['money'];
        break;
    case '7': $shi['7'] += $con['money'];
        break;
    case '8': $shi['8'] += $con['money'];
        break;
    case '9': $shi['9'] += $con['money'];
        break;
    case '0': $shi['0'] += $con['money'];
        break;
    case "大": $shi['大'] += $con['money'];
        break;
    case "小": $shi['小'] += $con['money'];
        break;
    case "单": $shi['单'] += $con['money'];
        break;
    case "双": $shi['双'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '5'){
    switch($con['content']){
    case '0': $ge['0'] += $con['money'];
        break;
    case '1': $ge['1'] += $con['money'];
        break;
    case '2': $ge['2'] += $con['money'];
        break;
    case '3': $ge['3'] += $con['money'];
        break;
    case '4': $ge['4'] += $con['money'];
        break;
    case '5': $ge['5'] += $con['money'];
        break;
    case '6': $ge['6'] += $con['money'];
        break;
    case '7': $ge['7'] += $con['money'];
        break;
    case '8': $ge['8'] += $con['money'];
        break;
    case '9': $ge['9'] += $con['money'];
        break;
    case '0': $ge['0'] += $con['money'];
        break;
    case "大": $ge['大'] += $con['money'];
        break;
    case "小": $ge['小'] += $con['money'];
        break;
    case "单": $ge['单'] += $con['money'];
        break;
    case "双": $ge['双'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '总'){
    switch($con['content']){
    case '大': $zong['大'] += $con['money'];
        break;
    case "小": $zong['小'] += $con['money'];
        break;
    case "单": $zong['单'] += $con['money'];
        break;
    case "双": $zong['双'] += $con['money'];
        break;
    case "龙": $zong['龙'] += $con['money'];
        break;
    case "虎": $zong['虎'] += $con['money'];
        break;
    case "合": $zong['合'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '前三'){
    switch($con['content']){
    case '豹子': $q3['豹子'] += $con['money'];
        break;
    case "对子": $q3['对子'] += $con['money'];
        break;
    case "顺子": $q3['顺子'] += $con['money'];
        break;
    case "半顺": $q3['半顺'] += $con['money'];
        break;
    case "杂六": $q3['杂六'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '中三'){
    switch($con['content']){
    case '豹子': $z3['豹子'] += $con['money'];
        break;
    case "对子": $z3['对子'] += $con['money'];
        break;
    case "顺子": $z3['顺子'] += $con['money'];
        break;
    case "半顺": $z3['半顺'] += $con['money'];
        break;
    case "杂六": $z3['杂六'] += $con['money'];
        break;
    }
    continue;
}elseif($con['mingci'] == '后三'){
    switch($con['content']){
    case '豹子': $h3['豹子'] += $con['money'];
        break;
    case "对子": $h3['对子'] += $con['money'];
        break;
    case "顺子": $h3['顺子'] += $con['money'];
        break;
    case "半顺": $h3['半顺'] += $con['money'];
        break;
    case "杂六": $h3['杂六'] += $con['money'];
        break;
    }
    continue;
}
}
if($duichong == 'true'){
$arr = array($wan['1'], $wan['2'], $wan['3'], $wan['4'], $wan['5'], $wan['6'], $wan['7'], $wan['8'], $wan['9'], $wan['0']);
sort($arr);
$wan['1'] = $wan['1'] - $arr[0];
$wan['2'] = $wan['2'] - $arr[0];
$wan['3'] = $wan['3'] - $arr[0];
$wan['4'] = $wan['4'] - $arr[0];
$wan['5'] = $wan['5'] - $arr[0];
$wan['6'] = $wan['6'] - $arr[0];
$wan['7'] = $wan['7'] - $arr[0];
$wan['8'] = $wan['8'] - $arr[0];
$wan['9'] = $wan['9'] - $arr[0];
$wan['0'] = $wan['0'] - $arr[0];
if($wan['大'] > $wan['小']){
    $wan['大'] = $wan['大'] - $wan['小'];
    $wan['小'] = 0;
}elseif($wan['小'] > $wan['大']){
    $wan['小'] = $wan['小'] - $wan['大'];
    $wan['大'] = 0;
}elseif($wan['大'] == $wan['小']){
    $wan['大'] = 0;
    $wan['小'] = 0;
}
if($wan['单'] > $wan['双']){
    $wan['单'] = $wan['单'] - $wan['双'];
    $wan['双'] = 0;
}elseif($wan['双'] > $wan['单']){
    $wan['双'] = $wan['双'] - $wan['单'];
    $wan['单'] = 0;
}elseif($wan['单'] == $wan['双']){
    $wan['单'] = 0;
    $wan['双'] = 0;
}
$arr = array($qian['1'], $qian['2'], $qian['3'], $qian['4'], $qian['5'], $qian['6'], $qian['7'], $qian['8'], $qian['9'], $qian['0']);
sort($arr);
$qian['1'] = $qian['1'] - $arr[0];
$qian['2'] = $qian['2'] - $arr[0];
$qian['3'] = $qian['3'] - $arr[0];
$qian['4'] = $qian['4'] - $arr[0];
$qian['5'] = $qian['5'] - $arr[0];
$qian['6'] = $qian['6'] - $arr[0];
$qian['7'] = $qian['7'] - $arr[0];
$qian['8'] = $qian['8'] - $arr[0];
$qian['9'] = $qian['9'] - $arr[0];
$qian['0'] = $qian['0'] - $arr[0];
if($qian['大'] > $qian['小']){
    $qian['大'] = $qian['大'] - $qian['小'];
    $qian['小'] = 0;
}elseif($qian['小'] > $qian['大']){
    $qian['小'] = $qian['小'] - $qian['大'];
    $qian['大'] = 0;
}elseif($qian['大'] == $qian['小']){
    $qian['大'] = 0;
    $qian['小'] = 0;
}
if($qian['单'] > $qian['双']){
    $qian['单'] = $qian['单'] - $qian['双'];
    $qian['双'] = 0;
}elseif($qian['双'] > $qian['单']){
    $qian['双'] = $qian['双'] - $qian['单'];
    $qian['单'] = 0;
}elseif($qian['单'] == $qian['双']){
    $qian['单'] = 0;
    $qian['双'] = 0;
}
$arr = array($bai['1'], $bai['2'], $bai['3'], $bai['4'], $bai['5'], $bai['6'], $bai['7'], $bai['8'], $bai['9'], $bai['0']);
sort($arr);
$bai['1'] = $bai['1'] - $arr[0];
$bai['2'] = $bai['2'] - $arr[0];
$bai['3'] = $bai['3'] - $arr[0];
$bai['4'] = $bai['4'] - $arr[0];
$bai['5'] = $bai['5'] - $arr[0];
$bai['6'] = $bai['6'] - $arr[0];
$bai['7'] = $bai['7'] - $arr[0];
$bai['8'] = $bai['8'] - $arr[0];
$bai['9'] = $bai['9'] - $arr[0];
$bai['0'] = $bai['0'] - $arr[0];
if($bai['大'] > $bai['小']){
    $bai['大'] = $bai['大'] - $bai['小'];
    $bai['小'] = 0;
}elseif($bai['小'] > $bai['大']){
    $bai['小'] = $bai['小'] - $bai['大'];
    $bai['大'] = 0;
}elseif($bai['大'] == $bai['小']){
    $bai['大'] = 0;
    $bai['小'] = 0;
}
if($bai['单'] > $bai['双']){
    $bai['单'] = $bai['单'] - $bai['双'];
    $bai['双'] = 0;
}elseif($bai['双'] > $bai['单']){
    $bai['双'] = $bai['双'] - $bai['单'];
    $bai['单'] = 0;
}elseif($bai['单'] == $bai['双']){
    $bai['单'] = 0;
    $bai['双'] = 0;
}
$arr = array($shi['1'], $shi['2'], $shi['3'], $shi['4'], $shi['5'], $shi['6'], $shi['7'], $shi['8'], $shi['9'], $shi['0']);
sort($arr);
$shi['1'] = $shi['1'] - $arr[0];
$shi['2'] = $shi['2'] - $arr[0];
$shi['3'] = $shi['3'] - $arr[0];
$shi['4'] = $shi['4'] - $arr[0];
$shi['5'] = $shi['5'] - $arr[0];
$shi['6'] = $shi['6'] - $arr[0];
$shi['7'] = $shi['7'] - $arr[0];
$shi['8'] = $shi['8'] - $arr[0];
$shi['9'] = $shi['9'] - $arr[0];
$shi['0'] = $shi['0'] - $arr[0];
if($shi['大'] > $shi['小']){
    $shi['大'] = $shi['大'] - $shi['小'];
    $shi['小'] = 0;
}elseif($shi['小'] > $shi['大']){
    $shi['小'] = $shi['小'] - $shi['大'];
    $shi['大'] = 0;
}elseif($shi['大'] == $shi['小']){
    $shi['大'] = 0;
    $shi['小'] = 0;
}
if($shi['单'] > $shi['双']){
    $shi['单'] = $shi['单'] - $shi['双'];
    $shi['双'] = 0;
}elseif($shi['双'] > $shi['单']){
    $shi['双'] = $shi['双'] - $shi['单'];
    $shi['单'] = 0;
}elseif($shi['单'] == $shi['双']){
    $shi['单'] = 0;
    $shi['双'] = 0;
}
$arr = array($ge['1'], $ge['2'], $ge['3'], $ge['4'], $ge['5'], $ge['6'], $ge['7'], $ge['8'], $ge['9'], $ge['0']);
sort($arr);
$ge['1'] = $ge['1'] - $arr[0];
$ge['2'] = $ge['2'] - $arr[0];
$ge['3'] = $ge['3'] - $arr[0];
$ge['4'] = $ge['4'] - $arr[0];
$ge['5'] = $ge['5'] - $arr[0];
$ge['6'] = $ge['6'] - $arr[0];
$ge['7'] = $ge['7'] - $arr[0];
$ge['8'] = $ge['8'] - $arr[0];
$ge['9'] = $ge['9'] - $arr[0];
$ge['0'] = $ge['0'] - $arr[0];
if($ge['大'] > $ge['小']){
    $ge['大'] = $ge['大'] - $ge['小'];
    $ge['小'] = 0;
}elseif($ge['小'] > $ge['大']){
    $ge['小'] = $ge['小'] - $ge['大'];
    $ge['大'] = 0;
}elseif($ge['大'] == $ge['小']){
    $ge['大'] = 0;
    $ge['小'] = 0;
}
if($ge['单'] > $ge['双']){
    $ge['单'] = $ge['单'] - $ge['双'];
    $ge['双'] = 0;
}elseif($ge['双'] > $ge['单']){
    $ge['双'] = $ge['双'] - $ge['单'];
    $ge['单'] = 0;
}elseif($ge['单'] == $ge['双']){
    $ge['单'] = 0;
    $ge['双'] = 0;
}
if($zong['大'] > $zong['小']){
    $zong['大'] = $zong['大'] - $zong['小'];
    $zong['小'] = 0;
}elseif($zong['小'] > $zong['大']){
    $zong['小'] = $zong['小'] - $zong['大'];
    $zong['大'] = 0;
}elseif($zong['大'] == $zong['小']){
    $zong['大'] = 0;
    $zong['小'] = 0;
}
if($zong['单'] > $zong['双']){
    $zong['单'] = $zong['单'] - $zong['双'];
    $zong['双'] = 0;
}elseif($zong['双'] > $zong['单']){
    $zong['双'] = $zong['双'] - $zong['单'];
    $zong['单'] = 0;
}elseif($zong['单'] == $zong['双']){
    $zong['单'] = 0;
    $zong['双'] = 0;
}
}
?>
        <tr>
          <td style="width:30px"><span class="pcdd">0</span></td>
          <td>
            <?php $m = $wan['0'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">0</span></td>
          <td>
            <?php $m = $qian['0'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">0</span></td>
          <td>
            <?php $m = $bai['0'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">0</span></td>
          <td>
            <?php $m = $shi['0'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">0</span></td>
          <td>
            <?php $m = $ge['0'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $zong['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">豹子</span></td>
          <td>
            <?php $m = $q3['豹子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">豹子</span></td>
          <td>
            <?php $m = $z3['豹子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">豹子</span></td>
          <td>
            <?php $m = $h3['豹子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">1</span></td>
          <td>
            <?php $m = $wan['1'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">1</span></td>
          <td>
            <?php $m = $qian['1'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">1</span></td>
          <td>
            <?php $m = $bai['1'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">1</span></td>
          <td>
            <?php $m = $shi['1'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">1</span></td>
          <td>
            <?php $m = $ge['1'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $zong['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">顺子</span></td>
          <td>
            <?php $m = $q3['顺子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">顺子</span></td>
          <td>
            <?php $m = $z3['顺子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">顺子</span></td>
          <td>
            <?php $m = $h3['顺子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">2</span></td>
          <td>
            <?php $m = $wan['2'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">2</span></td>
          <td>
            <?php $m = $qian['2'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">2</span></td>
          <td>
            <?php $m = $bai['2'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">2</span></td>
          <td>
            <?php $m = $shi['2'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">2</span></td>
          <td>
            <?php $m = $ge['2'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $zong['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">对子</span></td>
          <td>
            <?php $m = $q3['对子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">对子</span></td>
          <td>
            <?php $m = $z3['对子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">对子</span></td>
          <td>
            <?php $m = $h3['对子'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">3</span></td>
          <td>
            <?php $m = $wan['3'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">3</span></td>
          <td>
            <?php $m = $qian['3'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">3</span></td>
          <td>
            <?php $m = $bai['3'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">3</span></td>
          <td>
            <?php $m = $shi['3'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">3</span></td>
          <td>
            <?php $m = $ge['3'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $zong['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">半顺</span></td>
          <td>
            <?php $m = $q3['半顺'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">半顺</span></td>
          <td>
            <?php $m = $z3['半顺'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_blue">半顺</span></td>
          <td>
            <?php $m = $h3['半顺'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">4</span></td>
          <td>
            <?php $m = $wan['4'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">4</span></td>
          <td>
            <?php $m = $qian['4'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">4</span></td>
          <td>
            <?php $m = $bai['4'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">4</span></td>
          <td>
            <?php $m = $shi['4'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">4</span></td>
          <td>
            <?php $m = $ge['4'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">龙</span></td>
          <td>
            <?php $m = $zong['龙'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">杂六</span></td>
          <td>
            <?php $m = $q3['杂六'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">杂六</span></td>
          <td>
            <?php $m = $z3['杂六'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td><span class="pk_pink">杂六</span></td>
          <td>
            <?php $m = $h3['杂六'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">5</span></td>
          <td>
            <?php $m = $wan['5'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">5</span></td>
          <td>
            <?php $m = $qian['5'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">5</span></td>
          <td>
            <?php $m = $bai['5'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">5</span></td>
          <td>
            <?php $m = $shi['5'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">5</span></td>
          <td>
            <?php $m = $ge['5'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">虎</span></td>
          <td>
            <?php $m = $zong['虎'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">6</span></td>
          <td>
            <?php $m = $wan['6'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">6</span></td>
          <td>
            <?php $m = $qian['6'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">6</span></td>
          <td>
            <?php $m = $bai['6'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">6</span></td>
          <td>
            <?php $m = $shi['6'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">6</span></td>
          <td>
            <?php $m = $ge['6'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">合</span></td>
          <td>
            <?php $m = $zong['合'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">7</span></td>
          <td>
            <?php $m = $wan['7'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">7</span></td>
          <td>
            <?php $m = $qian['7'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">7</span></td>
          <td>
            <?php $m = $bai['7'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">7</span></td>
          <td>
            <?php $m = $shi['7'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">7</span></td>
          <td>
            <?php $m = $ge['7'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">8</span></td>
          <td>
            <?php $m = $wan['8'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">8</span></td>
          <td>
            <?php $m = $qian['8'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">8</span></td>
          <td>
            <?php $m = $bai['8'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">8</span></td>
          <td>
            <?php $m = $shi['8'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">8</span></td>
          <td>
            <?php $m = $ge['8'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pcdd">9</span></td>
          <td>
            <?php $m = $wan['9'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">9</span></td>
          <td>
            <?php $m = $qian['9'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">9</span></td>
          <td>
            <?php $m = $bai['9'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">9</span></td>
          <td>
            <?php $m = $shi['9'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pcdd">9</span></td>
          <td>
            <?php $m = $ge['9'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $wan['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $qian['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $bai['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $shi['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">大</span></td>
          <td>
            <?php $m = $ge['大'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $wan['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $qian['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $bai['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $shi['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">小</span></td>
          <td>
            <?php $m = $ge['小'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $wan['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $qian['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $bai['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $shi['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_pink">单</span></td>
          <td>
            <?php $m = $ge['单'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $wan['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $qian['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $bai['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $shi['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td style="width:30px"><span class="pk_blue">双</span></td>
          <td>
            <?php $m = $ge['双'];
if($m > 0){
echo '<span class="money_y">' . $m . '</span>';
}else{
echo '<span class="money_n">0</span>';
}
?>
          </td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
<?php }
?>