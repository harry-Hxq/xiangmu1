<?php
 function ESC_getcode($url){
    $name = $_SESSION['agent_room'] . time() . '.png';
    $code = dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . '/flyorder/' . $name;
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/yzm.php');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $result = curl_exec($ch);
    curl_close($ch);
    list($header, $body) = explode("\r\n\r\n", $result);
    preg_match("/^Set-Cookie: (.*?);/m", $header, $cookies);
    $cookies = $cookies[1];
    $fp = fopen($code, "w");
    fwrite($fp, $body);
    fclose($fp);
    return array("code" => "flyorder/" . $name, 'cookie' => $cookies);
}
function ESC_Login($url, $user, $pass, $code, $cookies){
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/index.php/webcenter/Login/login_do');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $post = array('action' => 'login', 'username' => $user, 'password' => $pass, 'vlcodes' => $code);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $cookies = str_replace('%3D', '=', $cookies);
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $result = curl_exec($ch);
    curl_close($ch);
    list($header, $body) = explode("\r\n\r\n", $result);
    preg_match("/^Set-Cookie: (.*?);/m", $header, $cookies);
    switch($result){
    case '1': break;
    case '4': echo "<script>alert('账号或密码错误!')</script>";
        break;
    case '5': echo "<script>alert('验证码错误!!')</script>";
        break;
    }
    update_query("fn_setting", array("flyorder_session" => $cookies[1]), array('roomid' => $_SESSION['agent_room']));
    return ESC_getmoney($url, $cookies[1]);
}
function ESC_getmoney($url, $cookies){
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/index.php/lottery/lottery/get_json');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_POST, true);
    $post = array('lotteryId' => 'bj_10', 'numberPostion' => 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result, 1);
    return array("money" => $json['Obj']['Balance'], 'weijie' => $json['Obj']['NotCountSum']);
}
function ESC_GoBet($url, $user, $cookies, $roomid){
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/index.php/lottery/lottery/bet?');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $post = ESC_getBet($roomid, $content, $term);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result, 1);
    var_dump($json);
    if($json['result'] == 1){
        $true = '成功';
    }else{
        $true = $json['msg'];
    }
    if($content == "")return;
    $money = ESC_getmoney($url, $cookies);
    insert_query("fn_flyorder", array("game" => "北京赛车", "term" => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
}
function ESC_getBet($roomid, & $contents, & $term){
    $yi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0);
    $er = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0);
    $san = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0);
    $si = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0);
    $wu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0);
    $liu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $qi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $ba = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $jiu = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $shi = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '0' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $he = array('3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $term = get_query_val('fn_open', 'next_term', "type = 1 order by term desc limit 1");
    $duichong = get_query_val('fn_setting', 'flyorder_duichong', array('roomid' => $roomid));
    select_query("fn_order", '*', "roomid = $roomid and `status` = '未结算' and term = '$term' and jia = 'false'");
    while($con = db_fetch_array()){
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
            case "小单": $money = $con['money'] / 3;
                $yi['1'] += (int)$money;
                $yi['3'] += (int)$money;
                $yi['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $yi['7'] += (int)$money;
                $yi['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $yi['2'] += (int)$money;
                $yi['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $yi['6'] += (int)$money;
                $yi['8'] += (int)$money;
                $yi['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $er['1'] += (int)$money;
                $er['3'] += (int)$money;
                $er['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $er['7'] += (int)$money;
                $er['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $er['2'] += (int)$money;
                $er['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $er['6'] += (int)$money;
                $er['8'] += (int)$money;
                $er['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $san['1'] += (int)$money;
                $san['3'] += (int)$money;
                $san['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $san['7'] += (int)$money;
                $san['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $san['2'] += (int)$money;
                $san['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $san['6'] += (int)$money;
                $san['8'] += (int)$money;
                $san['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $si['1'] += (int)$money;
                $si['3'] += (int)$money;
                $si['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $si['7'] += (int)$money;
                $si['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $si['2'] += (int)$money;
                $si['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $si['6'] += (int)$money;
                $si['8'] += (int)$money;
                $si['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $wu['1'] += (int)$money;
                $wu['3'] += (int)$money;
                $wu['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $wu['7'] += (int)$money;
                $wu['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $wu['2'] += (int)$money;
                $wu['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $wu['6'] += (int)$money;
                $wu['8'] += (int)$money;
                $wu['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $liu['1'] += (int)$money;
                $liu['3'] += (int)$money;
                $liu['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $liu['7'] += (int)$money;
                $liu['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $liu['2'] += (int)$money;
                $liu['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $liu['6'] += (int)$money;
                $liu['8'] += (int)$money;
                $liu['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $qi['1'] += (int)$money;
                $qi['3'] += (int)$money;
                $qi['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $qi['7'] += (int)$money;
                $qi['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $qi['2'] += (int)$money;
                $qi['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $qi['6'] += (int)$money;
                $qi['8'] += (int)$money;
                $qi['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $ba['1'] += (int)$money;
                $ba['3'] += (int)$money;
                $ba['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $ba['7'] += (int)$money;
                $ba['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $ba['2'] += (int)$money;
                $ba['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $ba['6'] += (int)$money;
                $ba['8'] += (int)$money;
                $ba['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $jiu['1'] += (int)$money;
                $jiu['3'] += (int)$money;
                $jiu['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $jiu['7'] += (int)$money;
                $jiu['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $jiu['2'] += (int)$money;
                $jiu['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $jiu['6'] += (int)$money;
                $jiu['8'] += (int)$money;
                $jiu['0'] += (int)$money;
                break;
            }
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
            case "小单": $money = $con['money'] / 3;
                $shi['1'] += (int)$money;
                $shi['3'] += (int)$money;
                $shi['5'] += (int)$money;
                break;
            case "大单": $money = $con['money'] / 2;
                $shi['7'] += (int)$money;
                $shi['9'] += (int)$money;
                break;
            case "小双": $money = $con['money'] / 2;
                $shi['2'] += (int)$money;
                $shi['4'] += (int)$money;
                break;
            case "大双": $money = $con['money'] / 3;
                $shi['6'] += (int)$money;
                $shi['8'] += (int)$money;
                $shi['0'] += (int)$money;
                break;
            }
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
        }
    }
    if($duichong == 'true'){
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
    $bets = array();
    foreach($yi as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 861, 'gname' => '冠军', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 862, 'gname' => '冠军', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 863, 'gname' => '冠军', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 864, 'gname' => '冠军', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 865, 'gname' => '冠军', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 866, 'gname' => '冠军', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 867, 'gname' => '冠军', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 868, 'gname' => '冠军', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 869, 'gname' => '冠军', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 870, 'gname' => '冠军', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 871, 'gname' => '冠军', 'BetContext' => '大', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 872, 'gname' => '冠军', 'BetContext' => '小', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 873, 'gname' => '冠军', 'BetContext' => '单', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 874, 'gname' => '冠军', 'BetContext' => '双', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $bets[] = array('id' => 875, 'gname' => '冠军', 'BetContext' => '龙', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $bets[] = array('id' => 876, 'gname' => '冠军', 'BetContext' => '虎', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($er as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 877, 'gname' => '亚军', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 878, 'gname' => '亚军', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 879, 'gname' => '亚军', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 880, 'gname' => '亚军', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 881, 'gname' => '亚军', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 882, 'gname' => '亚军', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 883, 'gname' => '亚军', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 884, 'gname' => '亚军', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 885, 'gname' => '亚军', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 886, 'gname' => '亚军', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 887, 'gname' => '亚军', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 888, 'gname' => '亚军', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 889, 'gname' => '亚军', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 890, 'gname' => '亚军', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $bets[] = array('id' => 891, 'gname' => '亚军', 'BetContext' => '龙', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $bets[] = array('id' => 892, 'gname' => '亚军', 'BetContext' => '虎', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($san as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 893, 'gname' => '第三名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 894, 'gname' => '第三名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 895, 'gname' => '第三名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 896, 'gname' => '第三名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 897, 'gname' => '第三名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 898, 'gname' => '第三名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 899, 'gname' => '第三名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 900, 'gname' => '第三名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 901, 'gname' => '第三名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 902, 'gname' => '第三名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 903, 'gname' => '第三名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 904, 'gname' => '第三名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 905, 'gname' => '第三名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 906, 'gname' => '第三名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $bets[] = array('id' => 907, 'gname' => '第三名', 'BetContext' => '龙', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $bets[] = array('id' => 908, 'gname' => '第三名', 'BetContext' => '虎', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($si as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 909, 'gname' => '第四名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 910, 'gname' => '第四名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 911, 'gname' => '第四名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 912, 'gname' => '第四名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 913, 'gname' => '第四名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 914, 'gname' => '第四名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 915, 'gname' => '第四名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 916, 'gname' => '第四名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 917, 'gname' => '第四名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 918, 'gname' => '第四名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 919, 'gname' => '第四名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 920, 'gname' => '第四名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 921, 'gname' => '第四名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 922, 'gname' => '第四名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $bets[] = array('id' => 923, 'gname' => '第四名', 'BetContext' => '龙', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $bets[] = array('id' => 924, 'gname' => '第四名', 'BetContext' => '虎', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($wu as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 925, 'gname' => '第五名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 926, 'gname' => '第五名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 927, 'gname' => '第五名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 928, 'gname' => '第五名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 929, 'gname' => '第五名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 930, 'gname' => '第五名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 931, 'gname' => '第五名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 932, 'gname' => '第五名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 933, 'gname' => '第五名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 934, 'gname' => '第五名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 935, 'gname' => '第五名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 936, 'gname' => '第五名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 937, 'gname' => '第五名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 938, 'gname' => '第五名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $bets[] = array('id' => 939, 'gname' => '第五名', 'BetContext' => '龙', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $bets[] = array('id' => 940, 'gname' => '第五名', 'BetContext' => '虎', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($liu as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 941, 'gname' => '第六名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 942, 'gname' => '第六名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 943, 'gname' => '第六名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 944, 'gname' => '第六名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 945, 'gname' => '第六名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 946, 'gname' => '第六名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 947, 'gname' => '第六名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 948, 'gname' => '第六名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 949, 'gname' => '第六名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 950, 'gname' => '第六名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 951, 'gname' => '第六名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 952, 'gname' => '第六名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 953, 'gname' => '第六名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 954, 'gname' => '第六名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($qi as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 955, 'gname' => '第七名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 956, 'gname' => '第七名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 957, 'gname' => '第七名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 958, 'gname' => '第七名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 959, 'gname' => '第七名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 960, 'gname' => '第七名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 961, 'gname' => '第七名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 962, 'gname' => '第七名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 963, 'gname' => '第七名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 964, 'gname' => '第七名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 965, 'gname' => '第七名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 966, 'gname' => '第七名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 967, 'gname' => '第七名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 968, 'gname' => '第七名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($ba as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 969, 'gname' => '第八名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 970, 'gname' => '第八名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 971, 'gname' => '第八名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 972, 'gname' => '第八名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 973, 'gname' => '第八名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 974, 'gname' => '第八名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 975, 'gname' => '第八名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 976, 'gname' => '第八名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 977, 'gname' => '第八名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 978, 'gname' => '第八名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 979, 'gname' => '第八名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 980, 'gname' => '第八名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 981, 'gname' => '第八名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 982, 'gname' => '第八名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($jiu as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 983, 'gname' => '第九名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 984, 'gname' => '第九名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 985, 'gname' => '第九名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 986, 'gname' => '第九名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 987, 'gname' => '第九名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 988, 'gname' => '第九名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 989, 'gname' => '第九名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 990, 'gname' => '第九名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 991, 'gname' => '第九名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 992, 'gname' => '第九名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 993, 'gname' => '第九名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 994, 'gname' => '第九名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 995, 'gname' => '第九名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 996, 'gname' => '第九名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($shi as $key => $money){
        if($key == '1' && $money > 0){
            $bets[] = array('id' => 997, 'gname' => '第十名', 'BetContext' => '1', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $bets[] = array('id' => 998, 'gname' => '第十名', 'BetContext' => '2', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 999, 'gname' => '第十名', 'BetContext' => '3', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 1000, 'gname' => '第十名', 'BetContext' => '4', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 1001, 'gname' => '第十名', 'BetContext' => '5', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 1002, 'gname' => '第十名', 'BetContext' => '6', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 1003, 'gname' => '第十名', 'BetContext' => '7', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 1004, 'gname' => '第十名', 'BetContext' => '8', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 1005, 'gname' => '第十名', 'BetContext' => '9', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $bets[] = array('id' => 1006, 'gname' => '第十名', 'BetContext' => '10', 'Lines' => '9.95', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 1007, 'gname' => '第十名', 'BetContext' => '大', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 1008, 'gname' => '第十名', 'BetContext' => '小', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 1009, 'gname' => '第十名', 'BetContext' => '单', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 1010, 'gname' => '第十名', 'BetContext' => '双', 'Lines' => '1.995', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($he as $key => $money){
        if($key == '3' && $money > 0){
            $bets[] = array('id' => 4179, 'gname' => '冠、亚军和', 'BetContext' => '3', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $bets[] = array('id' => 4180, 'gname' => '冠、亚军和', 'BetContext' => '4', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $bets[] = array('id' => 4181, 'gname' => '冠、亚军和', 'BetContext' => '5', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $bets[] = array('id' => 4182, 'gname' => '冠、亚军和', 'BetContext' => '6', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $bets[] = array('id' => 4183, 'gname' => '冠、亚军和', 'BetContext' => '7', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $bets[] = array('id' => 4184, 'gname' => '冠、亚军和', 'BetContext' => '8', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $bets[] = array('id' => 4185, 'gname' => '冠、亚军和', 'BetContext' => '9', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '10' && $money > 0){
            $bets[] = array('id' => 4186, 'gname' => '冠、亚军和', 'BetContext' => '10', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '11' && $money > 0){
            $bets[] = array('id' => 4187, 'gname' => '冠、亚军和', 'BetContext' => '11', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '12' && $money > 0){
            $bets[] = array('id' => 4188, 'gname' => '冠、亚军和', 'BetContext' => '12', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '13' && $money > 0){
            $bets[] = array('id' => 4189, 'gname' => '冠、亚军和', 'BetContext' => '13', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '14' && $money > 0){
            $bets[] = array('id' => 4190, 'gname' => '冠、亚军和', 'BetContext' => '14', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '15' && $money > 0){
            $bets[] = array('id' => 4191, 'gname' => '冠、亚军和', 'BetContext' => '15', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '16' && $money > 0){
            $bets[] = array('id' => 4192, 'gname' => '冠、亚军和', 'BetContext' => '16', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '17' && $money > 0){
            $bets[] = array('id' => 4193, 'gname' => '冠、亚军和', 'BetContext' => '17', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '18' && $money > 0){
            $bets[] = array('id' => 4194, 'gname' => '冠、亚军和', 'BetContext' => '18', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '19' && $money > 0){
            $bets[] = array('id' => 4195, 'gname' => '冠、亚军和', 'BetContext' => '19', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $bets[] = array('id' => 4196, 'gname' => '冠、亚军和', 'BetContext' => '大', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $bets[] = array('id' => 4197, 'gname' => '冠、亚军和', 'BetContext' => '小', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $bets[] = array('id' => 4198, 'gname' => '冠、亚军和', 'BetContext' => '单', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $bets[] = array('id' => 4199, 'gname' => '冠、亚军和', 'BetContext' => '双', 'Lines' => '42.6', 'BetType' => 1, 'Money' => $money, 'IsTeMa' => false, 'IsForNumber' => false, 'mingxi_1' => 0);
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
    }
    $json = array('lotteryId' => "bj_10", 'betParameters' => $bets);
    return $json;
}
?>