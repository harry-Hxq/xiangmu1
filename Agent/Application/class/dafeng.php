<?php
 function y88_login($url, $user, $pass){
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/login/login_ok');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $post = array('loginName' => $user, 'loginPwd' => $pass);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $result = curl_exec($ch);
    curl_close($ch);
    list($header, $body) = explode("\r\n\r\n", $result);
    preg_match_all("/^Set-Cookie: (.*?);/m", $header, $cookies);
    if(count($cookies[1]) > 1){
        $cookies = $cookies[1][1];
    }else{
        $cookies = $cookies[1][0];
    }
    $json = json_decode($body, true);
    if($json['success'] != '1'){
        echo '<script>alert("' . $json['msg'] . '")</script>';
        return;
    }
    update_query("fn_setting", array("flyorder_session" => $cookies), array('roomid' => $_SESSION['agent_room']));
    return y88_getmoney($url, $cookies);
}
function y88_getmoney($url, $cookies){
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/main/leftinfo');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_POST, true);
    $post = array('optype' => 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result, 1);
    return array("money" => $json['money'], 'weijie' => '该平台不支持查询', 'err' => $result, 'cookies' => $cookies);
}
function y88_getskey($url, $cookies, $gamecode){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/gamessc/getseckey');
    if($SSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('game_code' => $gamecode, 'type' => 2)));
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $result = curl_exec($ch);
    curl_close($ch);
    $result = prepareJSON($result);
    $json = json_decode($result, true);
    return $json['newkey'];
}
function y88_GoBet($url, $user, $cookies, $roomid, $game = 'pk10'){
    if($game == 'pk10'){
        $post = y88_getBet($roomid, $content, $term);
        $gametitle = '北京赛车';
        $gamecode = 51;
    }elseif($game == 'xyft'){
        $post = y88_getBetXYFT($roomid, $content, $term);
        $gametitle = '幸运飞艇';
        $gamecode = 159;
    }
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    if(count($post['liangmian']) == 0 && count($post['danhao']) == 0 && count($post['hezhi']) == 0){
        return;
    }
    if(count($post['liangmian']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['liangmian']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet_saiche');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['liangmian']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true = '两面失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true = '两面成功;';
        }
    }
    if(count($post['danhao']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['danhao']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet_saiche');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['danhao']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true .= '单号失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true .= '单号成功;';
        }
    }
    if(count($post['hezhi']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['hezhi']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet_saiche');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['hezhi']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true .= '和值失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true .= '和值成功;';
        }
    }
    $money = y88_getmoney($url, $cookies);
    insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
}
function y88_GoBetSSC($url, $user, $cookies, $roomid){
    $post = y88_getBetSSC($roomid, $content, $term);
    $gametitle = '重庆时时彩';
    $gamecode = 2;
    $SSL = substr($url, 0, 8) == "https://" ? true : false;
    if(count($post['shuzi']) == 0 && count($post['liangmian']) == 0 && count($post['qzhsan']) == 0){
        return;
    }
    if(count($post['liangmian']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['liangmian']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['liangmian']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true = '两面失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true = '两面成功;';
        }
    }
    if(count($post['shuzi']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['shuzi']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['shuzi']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true .= '单号失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true .= '单号成功;';
        }
    }
    if(count($post['qzhsan']) > 0){
        $seckey = y88_getskey($url, $cookies, $gamecode);
        $post['qzhsan']['seckey'] = $seckey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '/bill/postbet');
        if($SSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post['qzhsan']));
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        $result = curl_exec($ch);
        curl_close($ch);
        if(strpos($result, '注单号') === false){
            $money = y88_getmoney($url, $cookies);
            $true .= '特殊失败';
            insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
            exit;
        }else{
            $true .= '特殊成功;';
        }
    }
    $money = y88_getmoney($url, $cookies);
    insert_query("fn_flyorder", array("game" => $gametitle, 'term' => $term, 'content' => substr($content, 0, strlen($content)-1), 'pan' => $url, 'panuser' => $user, 'money' => $money['money'], 'time' => 'now()', 'status' => $true, 'roomid' => $roomid));
}
function y88_getBet($roomid, & $contents, & $term){
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
    $danhao = array();
    $liangmian = array();
    $hezhi = array();
    foreach($yi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3001-1'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3001-2'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3001-3'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3001-4'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3001-5'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3001-6'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3001-7'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3001-8'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3001-9'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3001-10'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3001-3011'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3001-3012'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3001-3013'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3001-3014'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3001-3015'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3001-3016'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($er as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3002-1'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3002-2'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3002-3'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3002-4'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3002-5'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3002-6'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3002-7'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3002-8'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3002-9'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3002-10'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3002-3011'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3002-3012'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3002-3013'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3002-3014'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3002-3015'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3002-3016'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($san as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3003-1'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3003-2'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3003-3'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3003-4'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3003-5'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3003-6'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3003-7'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3003-8'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3003-9'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3003-10'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3003-3011'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3003-3012'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3003-3013'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3003-3014'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3003-3015'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3003-3016'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($si as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3004-1'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3004-2'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3004-3'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3004-4'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3004-5'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3004-6'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3004-7'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3004-8'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3004-9'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3004-10'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3004-3011'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3004-3012'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3004-3013'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3004-3014'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3004-3015'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3004-3016'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($wu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3005-1'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3005-2'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3005-3'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3005-4'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3005-5'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3005-6'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3005-7'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3005-8'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3005-9'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3005-10'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3005-3011'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3005-3012'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3005-3013'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3005-3014'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3005-3015'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3005-3016'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($liu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3006-1'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3006-2'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3006-3'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3006-4'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3006-5'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3006-6'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3006-7'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3006-8'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3006-9'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3006-10'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3006-3011'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3006-3012'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3006-3013'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3006-3014'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($qi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3007-1'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3007-2'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3007-3'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3007-4'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3007-5'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3007-6'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3007-7'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3007-8'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3007-9'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3007-10'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3007-3011'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3007-3012'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3007-3013'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3007-3014'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($ba as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3008-1'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3008-2'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3008-3'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3008-4'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3008-5'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3008-6'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3008-7'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3008-8'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3008-9'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3008-10'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3008-3011'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3008-3012'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3008-3013'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3008-3014'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($jiu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3009-1'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3009-2'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3009-3'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3009-4'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3009-5'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3009-6'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3009-7'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3009-8'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3009-9'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3009-10'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3009-3011'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3009-3012'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3009-3013'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3009-3014'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($shi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3010-1'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3010-2'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3010-3'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3010-4'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3010-5'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3010-6'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3010-7'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3010-8'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3010-9'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3010-10'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3010-3011'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3010-3012'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3010-3013'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3010-3014'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($he as $key => $money){
        if($key == '3' && $money > 0){
            $hezhi['ip_3021-3'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $hezhi['ip_3021-4'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $hezhi['ip_3021-5'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $hezhi['ip_3021-6'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $hezhi['ip_3021-7'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $hezhi['ip_3021-8'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $hezhi['ip_3021-9'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '10' && $money > 0){
            $hezhi['ip_3021-10'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '11' && $money > 0){
            $hezhi['ip_3021-11'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '12' && $money > 0){
            $hezhi['ip_3021-12'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '13' && $money > 0){
            $hezhi['ip_3021-13'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '14' && $money > 0){
            $hezhi['ip_3021-14'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '15' && $money > 0){
            $hezhi['ip_3021-15'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '16' && $money > 0){
            $hezhi['ip_3021-16'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '17' && $money > 0){
            $hezhi['ip_3021-17'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '18' && $money > 0){
            $hezhi['ip_3021-18'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '19' && $money > 0){
            $hezhi['ip_3021-19'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $hezhi['ip_3017'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $hezhi['ip_3018'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $hezhi['ip_3019'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $hezhi['ip_3020'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
    }
    if(count($danhao) > 0){
        $danhao['game_code'] = 51;
        $danhao['typecode'] = 2;
        $danhao['round'] = $term;
        $danhao['confirm'] = '下 注';
    }
    if(count($liangmian) > 0){
        $liangmian['game_code'] = 51;
        $liangmian['typecode'] = 0;
        $liangmian['round'] = $term;
        $liangmian['confirm'] = '下 注';
    }
    if(count($hezhi) > 0){
        $hezhi['game_code'] = 51;
        $hezhi['typecode'] = 1;
        $hezhi['round'] = $term;
        $hezhi['confirm'] = '下 注';
    }
    $json = array('danhao' => $danhao, 'liangmian' => $liangmian, 'hezhi' => $hezhi);
    return $json;
}
function y88_getBetXYFT($roomid, & $contents, & $term){
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
    $term = get_query_val('fn_open', 'next_term', "type = 2 order by term desc limit 1");
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
    $danhao = array();
    $liangmian = array();
    $hezhi = array();
    foreach($yi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3001-1'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3001-2'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3001-3'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3001-4'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3001-5'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3001-6'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3001-7'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3001-8'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3001-9'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3001-10'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3001-3011'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3001-3012'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3001-3013'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3001-3014'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3001-3015'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3001-3016'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($er as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3002-1'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3002-2'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3002-3'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3002-4'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3002-5'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3002-6'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3002-7'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3002-8'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3002-9'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3002-10'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3002-3011'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3002-3012'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3002-3013'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3002-3014'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3002-3015'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3002-3016'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($san as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3003-1'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3003-2'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3003-3'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3003-4'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3003-5'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3003-6'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3003-7'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3003-8'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3003-9'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3003-10'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3003-3011'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3003-3012'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3003-3013'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3003-3014'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3003-3015'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3003-3016'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($si as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3004-1'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3004-2'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3004-3'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3004-4'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3004-5'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3004-6'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3004-7'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3004-8'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3004-9'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3004-10'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3004-3011'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3004-3012'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3004-3013'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3004-3014'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3004-3015'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3004-3016'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($wu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3005-1'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3005-2'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3005-3'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3005-4'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3005-5'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3005-6'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3005-7'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3005-8'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3005-9'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3005-10'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3005-3011'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3005-3012'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3005-3013'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3005-3014'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_3005-3015'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_3005-3016'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($liu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3006-1'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3006-2'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3006-3'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3006-4'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3006-5'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3006-6'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3006-7'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3006-8'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3006-9'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3006-10'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3006-3011'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3006-3012'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3006-3013'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3006-3014'] = $money;
            $contents .= '6/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($qi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3007-1'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3007-2'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3007-3'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3007-4'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3007-5'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3007-6'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3007-7'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3007-8'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3007-9'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3007-10'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3007-3011'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3007-3012'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3007-3013'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3007-3014'] = $money;
            $contents .= '7/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($ba as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3008-1'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3008-2'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3008-3'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3008-4'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3008-5'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3008-6'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3008-7'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3008-8'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3008-9'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3008-10'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3008-3011'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3008-3012'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3008-3013'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3008-3014'] = $money;
            $contents .= '8/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($jiu as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3009-1'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3009-2'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3009-3'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3009-4'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3009-5'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3009-6'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3009-7'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3009-8'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3009-9'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3009-10'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3009-3011'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3009-3012'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3009-3013'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3009-3014'] = $money;
            $contents .= '9/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($shi as $key => $money){
        if($key == '1' && $money > 0){
            $danhao['ip_3010-1'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $danhao['ip_3010-2'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $danhao['ip_3010-3'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $danhao['ip_3010-4'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $danhao['ip_3010-5'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $danhao['ip_3010-6'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $danhao['ip_3010-7'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $danhao['ip_3010-8'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $danhao['ip_3010-9'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $danhao['ip_3010-10'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3010-3011'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3010-3012'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3010-3013'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3010-3014'] = $money;
            $contents .= '10/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($he as $key => $money){
        if($key == '3' && $money > 0){
            $hezhi['ip_3021-3'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $hezhi['ip_3021-4'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $hezhi['ip_3021-5'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $hezhi['ip_3021-6'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $hezhi['ip_3021-7'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $hezhi['ip_3021-8'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $hezhi['ip_3021-9'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '10' && $money > 0){
            $hezhi['ip_3021-10'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '11' && $money > 0){
            $hezhi['ip_3021-11'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '12' && $money > 0){
            $hezhi['ip_3021-12'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '13' && $money > 0){
            $hezhi['ip_3021-13'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '14' && $money > 0){
            $hezhi['ip_3021-14'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '15' && $money > 0){
            $hezhi['ip_3021-15'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '16' && $money > 0){
            $hezhi['ip_3021-16'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '17' && $money > 0){
            $hezhi['ip_3021-17'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '18' && $money > 0){
            $hezhi['ip_3021-18'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '19' && $money > 0){
            $hezhi['ip_3021-19'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $hezhi['ip_3017'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $hezhi['ip_3018'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $hezhi['ip_3019'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $hezhi['ip_3020'] = $money;
            $contents .= '和/' . $key . '/' . $money . ',';
            continue;
        }
    }
    if(count($danhao) > 0){
        $danhao['game_code'] = 159;
        $danhao['typecode'] = 2;
        $danhao['round'] = $term;
        $danhao['confirm'] = '下 注';
    }
    if(count($liangmian) > 0){
        $liangmian['game_code'] = 159;
        $liangmian['typecode'] = 0;
        $liangmian['round'] = $term;
        $liangmian['confirm'] = '下 注';
    }
    if(count($hezhi) > 0){
        $hezhi['game_code'] = 159;
        $hezhi['typecode'] = 1;
        $hezhi['round'] = $term;
        $hezhi['confirm'] = '下 注';
    }
    $json = array('danhao' => $danhao, 'liangmian' => $liangmian, 'hezhi' => $hezhi);
    return $json;
}
function y88_getBetSSC($roomid, & $contents, & $term){
    $wan = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $qian = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $bai = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $shi = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $ge = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '大' => 0, '小' => 0, '单' => 0, '双' => 0);
    $zong = array('大' => 0, '小' => 0, '单' => 0, '双' => 0, '龙' => 0, '虎' => 0, '合' => 0);
    $q3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
    $z3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
    $h3 = array('豹子' => 0, '对子' => 0, '顺子' => 0, '半顺' => 0, '杂六' => 0);
    $term = get_query_val('fn_open', 'next_term', "type = '3' order by term desc limit 1");
    select_query("fn_sscorder", '*', "`roomid` = '{$roomid}' and `status` = '未结算' and `term` = '$term' and `jia` = 'false'");
    while($con = db_fetch_array()){
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
    $bets = array();
    foreach($wan as $key => $money){
        if($key == '1' && $money > 0){
            $shuzi['ip_1000-1'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $shuzi['ip_1000-2'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $shuzi['ip_1000-3'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $shuzi['ip_1000-4'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $shuzi['ip_1000-5'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $shuzi['ip_1000-6'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $shuzi['ip_1000-7'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $shuzi['ip_1000-8'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $shuzi['ip_1000-9'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $shuzi['ip_1000-0'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_1-1005'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_1-1006'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_1-1007'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_1-1008'] = $money;
            $contents .= '1/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($qian as $key => $money){
        if($key == '1' && $money > 0){
            $shuzi['ip_1001-1'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $shuzi['ip_1001-2'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $shuzi['ip_1001-3'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $shuzi['ip_1001-4'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $shuzi['ip_1001-5'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $shuzi['ip_1001-6'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $shuzi['ip_1001-7'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $shuzi['ip_1001-8'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $shuzi['ip_1001-9'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $shuzi['ip_1001-0'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_2-1005'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_2-1006'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_2-1007'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_2-1008'] = $money;
            $contents .= '2/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($bai as $key => $money){
        if($key == '1' && $money > 0){
            $shuzi['ip_1002-1'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $shuzi['ip_1002-2'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $shuzi['ip_1002-3'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $shuzi['ip_1002-4'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $shuzi['ip_1002-5'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $shuzi['ip_1002-6'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $shuzi['ip_1002-7'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $shuzi['ip_1002-8'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $shuzi['ip_1002-9'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $shuzi['ip_1002-0'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_3-1005'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_3-1006'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_3-1007'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_3-1008'] = $money;
            $contents .= '3/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($shi as $key => $money){
        if($key == '1' && $money > 0){
            $shuzi['ip_1003-1'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $shuzi['ip_1003-2'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $shuzi['ip_1003-3'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $shuzi['ip_1003-4'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $shuzi['ip_1003-5'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $shuzi['ip_1003-6'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $shuzi['ip_1003-7'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $shuzi['ip_1003-8'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $shuzi['ip_1003-9'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $shuzi['ip_1003-0'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_4-1005'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_4-1006'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_4-1007'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_4-1008'] = $money;
            $contents .= '4/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($ge as $key => $money){
        if($key == '1' && $money > 0){
            $shuzi['ip_1004-1'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '2' && $money > 0){
            $shuzi['ip_1004-2'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '3' && $money > 0){
            $shuzi['ip_1004-3'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '4' && $money > 0){
            $shuzi['ip_1004-4'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '5' && $money > 0){
            $shuzi['ip_1004-5'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '6' && $money > 0){
            $shuzi['ip_1004-6'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '7' && $money > 0){
            $shuzi['ip_1004-7'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '8' && $money > 0){
            $shuzi['ip_1004-8'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '9' && $money > 0){
            $shuzi['ip_1004-9'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '0' && $money > 0){
            $shuzi['ip_1004-0'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '大' && $money > 0){
            $liangmian['ip_5-1005'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_5-1006'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_5-1007'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_5-1008'] = $money;
            $contents .= '5/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($zong as $key => $money){
        if($key == '大' && $money > 0){
            $liangmian['ip_1009'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '小' && $money > 0){
            $liangmian['ip_1010'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '单' && $money > 0){
            $liangmian['ip_1011'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '双' && $money > 0){
            $liangmian['ip_1012'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '龙' && $money > 0){
            $liangmian['ip_1013'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '虎' && $money > 0){
            $liangmian['ip_1014'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '和' && $money > 0){
            $liangmian['ip_1015'] = $money;
            $contents .= '总和/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($q3 as $key => $money){
        if($key == '豹子' && $money > 0){
            $qzhsan['ip_1016'] = $money;
            $contents .= '前三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '顺子' && $money > 0){
            $qzhsan['ip_1017'] = $money;
            $contents .= '前三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '对子' && $money > 0){
            $qzhsan['ip_1018'] = $money;
            $contents .= '前三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '半顺' && $money > 0){
            $qzhsan['ip_1019'] = $money;
            $contents .= '前三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '杂六' && $money > 0){
            $qzhsan['ip_1020'] = $money;
            $contents .= '前三/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($z3 as $key => $money){
        if($key == '豹子' && $money > 0){
            $qzhsan['ip_1021'] = $money;
            $contents .= '中三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '顺子' && $money > 0){
            $qzhsan['ip_1022'] = $money;
            $contents .= '中三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '对子' && $money > 0){
            $qzhsan['ip_1023'] = $money;
            $contents .= '中三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '半顺' && $money > 0){
            $qzhsan['ip_1024'] = $money;
            $contents .= '中三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '杂六' && $money > 0){
            $qzhsan['ip_1025'] = $money;
            $contents .= '中三/' . $key . '/' . $money . ',';
            continue;
        }
    }
    foreach($h3 as $key => $money){
        if($key == '豹子' && $money > 0){
            $qzhsan['ip_1026'] = $money;
            $contents .= '后三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '顺子' && $money > 0){
            $qzhsan['ip_1027'] = $money;
            $contents .= '后三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '对子' && $money > 0){
            $qzhsan['ip_1028'] = $money;
            $contents .= '后三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '半顺' && $money > 0){
            $qzhsan['ip_1029'] = $money;
            $contents .= '后三/' . $key . '/' . $money . ',';
            continue;
        }
        if($key == '杂六' && $money > 0){
            $qzhsan['ip_1030'] = $money;
            $contents .= '后三/' . $key . '/' . $money . ',';
            continue;
        }
    }
    $term = substr($term, 0, 8) . '-' . substr($term, 8, 3);
    if(count($shuzi) > 0){
        $shuzi['JeuValidate'] = 155312191;
        $shuzi['game_code'] = 2;
        $shuzi['typecode'] = 6;
        $shuzi['round'] = $term;
        $shuzi['confirm'] = '下 注';
    }
    if(count($liangmian) > 0){
        $liangmian['JeuValidate'] = 155312191;
        $liangmian['game_code'] = 2;
        $liangmian['typecode'] = 0;
        $liangmian['round'] = $term;
        $liangmian['confirm'] = '下 注';
    }
    if(count($qzhsan) > 0){
        $qzhsan['game_code'] = 2;
        $qzhsan['typecode'] = 1;
        $qzhsan['round'] = $term;
        $qzhsan['confirm'] = '下 注';
    }
    $json = array('shuzi' => $shuzi, 'liangmian' => $liangmian, 'qzhsan' => $qzhsan);
    return $json;
}
?>