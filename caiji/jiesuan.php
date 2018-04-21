<?php
 function 用户_上分($Userid, $Money, $room, $game, $term, $content){
    update_query('fn_user', array('money' => '+=' . $Money), array('userid' => $Userid, 'roomid' => $room));
    insert_query("fn_marklog", array("userid" => $Userid, 'type' => '上分', 'content' => $term . '期' . $game . '中奖派彩' . $content, 'money' => $Money, 'roomid' => $room, 'addtime' => 'now()'));
}
function PC_jiesuan(){
    select_query("fn_pcorder", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        if((int)$term > 2000000){
            $openType = 5;
            $game = '加拿大28';
        }else{
            $openType = 4;
            $game = '幸运28';
        }
        $zym_9 = (int)get_query_val('fn_pcorder', 'sum(`money`)', array('roomid' => $roomid, 'term' => $term, 'userid' => $user));
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '$openType'");
        if($opencode == "")continue;
        $codes = explode(',', $opencode);
        if(count($codes) != 20){
            echo 'ERROR! --pc开奖没有对应'."\n" ;
            continue;
        }else{
            if($openType == 4){
                $number1 = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4] + (int)$codes[5];
                $number2 = (int)$codes[6] + (int)$codes[7] + (int)$codes[8] + (int)$codes[9] + (int)$codes[10] + (int)$codes[11];
                $number3 = (int)$codes[12] + (int)$codes[13] + (int)$codes[14] + (int)$codes[15] + (int)$codes[16] + (int)$codes[17];
                $number1 = substr($number1, -1);
                $number2 = substr($number2, -1);
                $number3 = substr($number3, -1);
                $hz = (int)$number1 + (int)$number2 + (int)$number3;
            }elseif($openType == 5){
                $number1 = (int)$codes[1] + (int)$codes[4] + (int)$codes[7] + (int)$codes[10] + (int)$codes[13] + (int)$codes[16];
                $number2 = (int)$codes[2] + (int)$codes[5] + (int)$codes[8] + (int)$codes[11] + (int)$codes[14] + (int)$codes[17];
                $number3 = (int)$codes[3] + (int)$codes[6] + (int)$codes[9] + (int)$codes[12] + (int)$codes[15] + (int)$codes[18];
                $number1 = substr($number1, -1);
                $number2 = substr($number2, -1);
                $number3 = substr($number3, -1);
                $hz = (int)$number1 + (int)$number2 + (int)$number3;
            }
        }
        if($number1 == $number2 && $number2 == $number3){
            $zym_10 = true;
        }
        if($number1 == $number2 || $number2 == $number3 || $number1 == $number3){
            if(!$zym_10){
                $zym_6 = true;
            }
        }
        if($number1 + 1 == $number2 && $number2 + 1 == $number3 || $number1 - 1 == $number2 && $number2 - 1 == $number3){
            $zym_5 = true;
        }
        if($zym_8 == '大' || $zym_8 == '小' || $zym_8 == '单' || $zym_8 == '双'){
            $peilv = get_query_val('fn_lottery' . $openType, 'dxds', "`roomid` = '$roomid'");
            if($hz == 13 || $hz == 14){
                $dxds_zongzhu1 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu1', array('roomid' => $roomid));
                $dxds_zongzhu2 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu2', array('roomid' => $roomid));
                $dxds_zongzhu3 = get_query_val('fn_lottery' . $openType, 'dxds_zongzhu3', array('roomid' => $roomid));
                $dxds_1314_1 = get_query_val('fn_lottery' . $openType, 'dxds_1314_1', array('roomid' => $roomid));
                $dxds_1314_2 = get_query_val('fn_lottery' . $openType, 'dxds_1314_2', array('roomid' => $roomid));
                $dxds_1314_3 = get_query_val('fn_lottery' . $openType, 'dxds_1314_3', array('roomid' => $roomid));
                if($dxds_zongzhu1 != ""){
                    if($zym_9 > (int)$dxds_zongzhu1){
                        $peilv = $dxds_1314_1;
                    }
                }
                if($dxds_zongzhu2 != ""){
                    if($zym_9 > (int)$dxds_zongzhu2){
                        $peilv = $dxds_1314_2;
                    }
                }
                if($dxds_zongzhu3 != ""){
                    if($zym_9 > (int)$dxds_zongzhu3){
                        $peilv = $dxds_1314_3;
                    }
                }
            }
            if($zym_8 == '大' && $hz > 13){
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小' && $hz < 14){
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '单' && $hz % 2 != 0){
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '双' && $hz % 2 == 0){
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_8 == '极大' || $zym_8 == '极小'){
            if($zym_8 == '极大' && $hz > 21){
                $peilv = get_query_val('fn_lottery' . $openType, 'jida', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '极小' && $hz < 6){
                $peilv = get_query_val('fn_lottery' . $openType, 'jixiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_8 == '大单' || $zym_8 == '大双' || $zym_8 == '小单' || $zym_8 == '小双'){
            if($zym_8 == '大单' && $hz > 13 && $hz % 2 != 0){
                $peilv = get_query_val('fn_lottery' . $openType, 'dadan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小单' && $hz < 14 && $hz % 2 != 0){
                $peilv = get_query_val('fn_lottery' . $openType, 'xiaodan', "`roomid` = '$roomid'");
                if($hz == 13){
                    $zuhe_zongzhu1 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu1', array('roomid' => $roomid));
                    $zuhe_zongzhu2 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu2', array('roomid' => $roomid));
                    $zuhe_zongzhu3 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu3', array('roomid' => $roomid));
                    $zuhe_1314_1 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_1', array('roomid' => $roomid));
                    $zuhe_1314_2 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_2', array('roomid' => $roomid));
                    $zuhe_1314_3 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_3', array('roomid' => $roomid));
                    if($zuhe_zongzhu1 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu1){
                            $peilv = $zuhe_1314_1;
                        }
                    }
                    if($zuhe_zongzhu2 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu2){
                            $peilv = $zuhe_1314_2;
                        }
                    }
                    if($zuhe_zongzhu3 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu3){
                            $peilv = $zuhe_1314_3;
                        }
                    }
                }
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '大双' && $hz > 13 && $hz % 2 == 0){
                $peilv = get_query_val('fn_lottery' . $openType, 'dashuang', "`roomid` = '$roomid'");
                if($hz == 14){
                    $zuhe_zongzhu1 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu1', array('roomid' => $roomid));
                    $zuhe_zongzhu2 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu2', array('roomid' => $roomid));
                    $zuhe_zongzhu3 = get_query_val('fn_lottery' . $openType, 'zuhe_zongzhu3', array('roomid' => $roomid));
                    $zuhe_1314_1 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_1', array('roomid' => $roomid));
                    $zuhe_1314_2 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_2', array('roomid' => $roomid));
                    $zuhe_1314_3 = get_query_val('fn_lottery' . $openType, 'zuhe_1314_3', array('roomid' => $roomid));
                    if($zuhe_zongzhu1 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu1){
                            $peilv = $zuhe_1314_1;
                        }
                    }
                    if($zuhe_zongzhu2 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu2){
                            $peilv = $zuhe_1314_2;
                        }
                    }
                    if($zuhe_zongzhu3 != ""){
                        if($zym_9 > (int)$zuhe_zongzhu3){
                            $peilv = $zuhe_1314_3;
                        }
                    }
                }
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小双' && $hz < 14 && $hz % 2 == 0){
                $peilv = get_query_val('fn_lottery' . $openType, 'xiaoshuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_8 == '豹子' || $zym_8 == '对子' || $zym_8 == '顺子'){
            if($zym_8 == '豹子' && $zym_10 == true){
                $peilv = get_query_val('fn_lottery' . $openType, 'baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_6 == true){
                $peilv = get_query_val('fn_lottery' . $openType, 'duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_5 == true){
                $peilv = get_query_val('fn_lottery' . $openType, 'shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if((int)$zym_8 == $hz){
            if($hz == 0 || $hz == 27){
                $peilv = get_query_val('fn_lottery' . $openType, '`0027`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 1 || $hz == 26){
                $peilv = get_query_val('fn_lottery' . $openType, '`0126`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 2 || $hz == 25){
                $peilv = get_query_val('fn_lottery' . $openType, '`0225`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 3 || $hz == 24){
                $peilv = get_query_val('fn_lottery' . $openType, '`0324`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 4 || $hz == 23){
                $peilv = get_query_val('fn_lottery' . $openType, '`0423`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 5 || $hz == 22){
                $peilv = get_query_val('fn_lottery' . $openType, '`0522`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 6 || $hz == 21){
                $peilv = get_query_val('fn_lottery' . $openType, '`0621`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 7 || $hz == 20){
                $peilv = get_query_val('fn_lottery' . $openType, '`0720`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 8 || $hz == 9 || $hz == 18 || $hz == 19){
                $peilv = get_query_val('fn_lottery' . $openType, '`891819`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                echo $peilv . '<br>';
                echo $zym_7 . '<br>';
                echo $peilv * $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 10 || $hz == 11 || $hz == 16 || $hz == 17){
                $peilv = get_query_val('fn_lottery' . $openType, '`10111617`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 12 || $hz == 15){
                $peilv = get_query_val('fn_lottery' . $openType, '`1215`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($hz == 13 || $hz == 14){
                $peilv = get_query_val('fn_lottery' . $openType, '`1314`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_8 . '/' . $zym_7);
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }else{
            $zym_11 = '-' . $zym_7;
            update_query("fn_pcorder", array("status" => $zym_11), array('id' => $id));
            continue;
        }
    }
}
function SSC_jiesuan($qihao=''){
    select_query("fn_sscorder", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $game = '重庆时时彩';
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '3'");
        if($opencode == "")continue;
        $codes = explode(',', $opencode);
        if(count($codes) != 5){
            echo 'ERROR! --重启时时彩开奖没有对应'."\n" ;
            exit();
        }else{
            $zym_2 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $q3 = array((int)$codes[0], (int)$codes[1], (int)$codes[2]);
            sort($q3);
            $zym_3 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $z3 = array((int)$codes[1], (int)$codes[2], (int)$codes[3]);
            sort($z3);
            $zym_4 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $h3 = array((int)$codes[2], (int)$codes[3], (int)$codes[4]);
            sort($h3);
            if($codes[0] == $codes[1] && $codes[1] == $codes[2]){
                $zym_2['豹子'] = true;
            }elseif($codes[0] == $codes[1] || $codes[0] == $codes[2] || $codes[1] == $codes[2]){
                $zym_2['对子'] = true;
            }elseif(($q3[0] + 1 == $q3[1] && $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[1] == '8' && $q3[2] == '9') || ($q3[0] == '0' && $q3[1] == '1' && $q3[2] == '9')){
                $zym_2['顺子'] = true;
            }elseif(($q3[0] + 1 == $q3[1] || $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[2] == '9')){
                $zym_2['半顺'] = true;
            }else{
                $zym_2['杂六'] = true;
            }
            if($codes[1] == $codes[2] && $codes[2] == $codes[3]){
                $zym_3['豹子'] = true;
            }elseif($codes[1] == $codes[2] || $codes[1] == $codes[3] || $codes[2] == $codes[3]){
                $zym_3['对子'] = true;
            }elseif(($z3[0] + 1 == $z3[1] && $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[1] == '8' && $z3[2] == '9') || ($z3[0] == '0' && $z3[1] == '1' && $z3[2] == '9')){
                $zym_3['顺子'] = true;
            }elseif(($z3[0] + 1 == $z3[1] || $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[2] == '9')){
                $zym_3['半顺'] = true;
            }else{
                $zym_3['杂六'] = true;
            }
            if($codes[2] == $codes[3] && $codes[3] == $codes[4]){
                $zym_4['豹子'] = true;
            }elseif($codes[2] == $codes[3] || $codes[2] == $codes[4] || $codes[3] == $codes[4]){
                $zym_4['对子'] = true;
            }elseif(($h3[0] + 1 == $h3[1] && $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[1] == '8' && $h3[2] == '9') || ($h3[0] == '0' && $h3[1] == '1' && $h3[2] == '9')){
                $zym_4['顺子'] = true;
            }elseif(($h3[0] + 1 == $h3[1] || $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[2] == '9')){
                $zym_4['半顺'] = true;
            }else{
                $zym_4['杂六'] = true;
            }
            $zong = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4];
        }
        if($zym_1 == '总'){
            if($zym_8 == '大' && $zong > 22){
                $peilv = get_query_val('fn_lottery3', 'zongda', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小' && $zong < 23){
                $peilv = get_query_val('fn_lottery3', 'zongxiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '单' && $zong % 2 != 0){
                $peilv = get_query_val('fn_lottery3', 'zongdan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '双' && $zong % 2 == 0){
                $peilv = get_query_val('fn_lottery3', 'zongshuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '龙' && $codes[0] > $codes[4]){
                $peilv = get_query_val('fn_lottery3', '`long`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '虎' && $codes[0] < $codes[4]){
                $peilv = get_query_val('fn_lottery3', 'hu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '和' && $codes[0] == $codes[4]){
                $peilv = get_query_val('fn_lottery3', 'he', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '前三'){
            if($zym_8 == '豹子' && $zym_2['豹子'] == true){
                $peilv = get_query_val('fn_lottery3', 'q_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_2['顺子'] == true){
                $peilv = get_query_val('fn_lottery3', 'q_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_2['对子'] == true){
                $peilv = get_query_val('fn_lottery3', 'q_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_2['半顺'] == true){
                $peilv = get_query_val('fn_lottery3', 'q_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_2['杂六'] == true){
                $peilv = get_query_val('fn_lottery3', 'q_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '中三'){
            if($zym_8 == '豹子' && $zym_3['豹子'] == true){
                $peilv = get_query_val('fn_lottery3', 'z_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_3['顺子'] == true){
                $peilv = get_query_val('fn_lottery3', 'z_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_3['对子'] == true){
                $peilv = get_query_val('fn_lottery3', 'z_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_3['半顺'] == true){
                $peilv = get_query_val('fn_lottery3', 'z_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_3['杂六'] == true){
                $peilv = get_query_val('fn_lottery3', 'z_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '后三'){
            if($zym_8 == '豹子' && $zym_4['豹子'] == true){
                $peilv = get_query_val('fn_lottery3', 'h_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_4['顺子'] == true){
                $peilv = get_query_val('fn_lottery3', 'h_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_4['对子'] == true){
                $peilv = get_query_val('fn_lottery3', 'h_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_4['半顺'] == true){
                $peilv = get_query_val('fn_lottery3', 'h_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_4['杂六'] == true){
                $peilv = get_query_val('fn_lottery3', 'h_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if((int)$zym_1 > 0){
            $count = (int)$zym_1 - 1;
            if($zym_8 == '大' && (int)$codes[$count] > 4){
                $peilv = get_query_val('fn_lottery3', 'da', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小' && (int)$codes[$count] < 5){
                $peilv = get_query_val('fn_lottery3', 'xiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '单' && (int)$codes[$count] % 2 != 0){
                $peilv = get_query_val('fn_lottery3', 'dan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '双' && (int)$codes[$count] % 2 == 0){
                $peilv = get_query_val('fn_lottery3', 'shuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == $codes[$count]){
                $peilv = get_query_val('fn_lottery3', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_sscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
    }
    zhangdanSay('ssc',3,'fn_sscorder',$qihao);
}
function JSSSC_jiesuan($qihao=''){
    select_query("fn_jssscorder", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $game = '极速时时彩';
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '8'");
        if($opencode == "")continue;
        $codes = explode(',', $opencode);
        if(count($codes) != 5){
            echo 'ERROR! --极速时时彩开奖没有对应'."\n" ;
            exit();
        }else{
            $zym_2 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $q3 = array((int)$codes[0], (int)$codes[1], (int)$codes[2]);
            sort($q3);
            $zym_3 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $z3 = array((int)$codes[1], (int)$codes[2], (int)$codes[3]);
            sort($z3);
            $zym_4 = array('豹子' => false, '半顺' => false, '顺子' => false, '对子' => false, '杂六' => false);
            $h3 = array((int)$codes[2], (int)$codes[3], (int)$codes[4]);
            sort($h3);
            if($codes[0] == $codes[1] && $codes[1] == $codes[2]){
                $zym_2['豹子'] = true;
            }elseif($codes[0] == $codes[1] || $codes[0] == $codes[2] || $codes[1] == $codes[2]){
                $zym_2['对子'] = true;
            }elseif(($q3[0] + 1 == $q3[1] && $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[1] == '8' && $q3[2] == '9') || ($q3[0] == '0' && $q3[1] == '1' && $q3[2] == '9')){
                $zym_2['顺子'] = true;
            }elseif(($q3[0] + 1 == $q3[1] || $q3[1] + 1 == $q3[2]) || ($q3[0] == '0' && $q3[2] == '9')){
                $zym_2['半顺'] = true;
            }else{
                $zym_2['杂六'] = true;
            }
            if($codes[1] == $codes[2] && $codes[2] == $codes[3]){
                $zym_3['豹子'] = true;
            }elseif($codes[1] == $codes[2] || $codes[1] == $codes[3] || $codes[2] == $codes[3]){
                $zym_3['对子'] = true;
            }elseif(($z3[0] + 1 == $z3[1] && $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[1] == '8' && $z3[2] == '9') || ($z3[0] == '0' && $z3[1] == '1' && $z3[2] == '9')){
                $zym_3['顺子'] = true;
            }elseif(($z3[0] + 1 == $z3[1] || $z3[1] + 1 == $z3[2]) || ($z3[0] == '0' && $z3[2] == '9')){
                $zym_3['半顺'] = true;
            }else{
                $zym_3['杂六'] = true;
            }
            if($codes[2] == $codes[3] && $codes[3] == $codes[4]){
                $zym_4['豹子'] = true;
            }elseif($codes[2] == $codes[3] || $codes[2] == $codes[4] || $codes[3] == $codes[4]){
                $zym_4['对子'] = true;
            }elseif(($h3[0] + 1 == $h3[1] && $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[1] == '8' && $h3[2] == '9') || ($h3[0] == '0' && $h3[1] == '1' && $h3[2] == '9')){
                $zym_4['顺子'] = true;
            }elseif(($h3[0] + 1 == $h3[1] || $h3[1] + 1 == $h3[2]) || ($h3[0] == '0' && $h3[2] == '9')){
                $zym_4['半顺'] = true;
            }else{
                $zym_4['杂六'] = true;
            }
            $zong = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4];
        }
        if($zym_1 == '总'){
            if($zym_8 == '大' && $zong > 22){
                $peilv = get_query_val('fn_lottery8', 'zongda', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小' && $zong < 23){
                $peilv = get_query_val('fn_lottery8', 'zongxiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '单' && $zong % 2 != 0){
                $peilv = get_query_val('fn_lottery8', 'zongdan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '双' && $zong % 2 == 0){
                $peilv = get_query_val('fn_lottery8', 'zongshuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '龙' && $codes[0] > $codes[4]){
                $peilv = get_query_val('fn_lottery8', '`long`', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '虎' && $codes[0] < $codes[4]){
                $peilv = get_query_val('fn_lottery8', 'hu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '和' && $codes[0] == $codes[4]){
                $peilv = get_query_val('fn_lottery8', 'he', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '前三'){
            if($zym_8 == '豹子' && $zym_2['豹子'] == true){
                $peilv = get_query_val('fn_lottery8', 'q_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_2['顺子'] == true){
                $peilv = get_query_val('fn_lottery8', 'q_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_2['对子'] == true){
                $peilv = get_query_val('fn_lottery8', 'q_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_2['半顺'] == true){
                $peilv = get_query_val('fn_lottery8', 'q_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_2['杂六'] == true){
                $peilv = get_query_val('fn_lottery8', 'q_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '中三'){
            if($zym_8 == '豹子' && $zym_3['豹子'] == true){
                $peilv = get_query_val('fn_lottery8', 'z_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_3['顺子'] == true){
                $peilv = get_query_val('fn_lottery8', 'z_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_3['对子'] == true){
                $peilv = get_query_val('fn_lottery8', 'z_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_3['半顺'] == true){
                $peilv = get_query_val('fn_lottery8', 'z_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_3['杂六'] == true){
                $peilv = get_query_val('fn_lottery8', 'z_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if($zym_1 == '后三'){
            if($zym_8 == '豹子' && $zym_4['豹子'] == true){
                $peilv = get_query_val('fn_lottery8', 'h_baozi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '顺子' && $zym_4['顺子'] == true){
                $peilv = get_query_val('fn_lottery8', 'h_shunzi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '对子' && $zym_4['对子'] == true){
                $peilv = get_query_val('fn_lottery8', 'h_duizi', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '半顺' && $zym_4['半顺'] == true){
                $peilv = get_query_val('fn_lottery8', 'h_banshun', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '杂六' && $zym_4['杂六'] == true){
                $peilv = get_query_val('fn_lottery8', 'h_zaliu', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
        if((int)$zym_1 > 0){
            $count = (int)$zym_1 - 1;
            if($zym_8 == '大' && (int)$codes[$count] > 4){
                $peilv = get_query_val('fn_lottery8', 'da', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '小' && (int)$codes[$count] < 5){
                $peilv = get_query_val('fn_lottery8', 'xiao', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '单' && (int)$codes[$count] % 2 != 0){
                $peilv = get_query_val('fn_lottery8', 'dan', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == '双' && (int)$codes[$count] % 2 == 0){
                $peilv = get_query_val('fn_lottery8', 'shuang', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }elseif($zym_8 == $codes[$count]){
                $peilv = get_query_val('fn_lottery8', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jssscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
            continue;
        }
    }
    zhangdanSay('jsssc',8,'fn_jssscorder',$qihao);



}
function jiesuan($qihao=''){
    select_query("fn_order", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $table = strlen($term) > 8 ? 'fn_lottery2' : 'fn_lottery1';
        $game = strlen($term) > 8 ? '幸运飞艇' : '北京赛车';
        $gametype = strlen($term) > 8 ? '2' : '1';
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '$gametype'");
        $opencode = str_replace('10', '0', $opencode);
        if($opencode == "")continue;
        $code = explode(',', $opencode);
        if($zym_1 == '1'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[0] > 5 || $code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[0] < 6 && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] < 5 && (int)$code[0] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if((int)$code[0] > (int)$code[9] && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if((int)$code[0] < (int)$code[9] && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[0]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '2'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[1] > 5 || $code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[1] < 6 && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] < 5 && (int)$code[1] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if((int)$code[1] > (int)$code[8] && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if((int)$code[1] < (int)$code[8] && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[1]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '3'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[2] > 5 || $code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[2] < 6 && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] < 5 && (int)$code[2] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if((int)$code[2] > (int)$code[7] && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if((int)$code[2] < (int)$code[7] && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[2]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '4'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[3] > 5 || $code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[3] < 6 && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] < 5 && (int)$code[3] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if((int)$code[3] > (int)$code[6] && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if((int)$code[3] < (int)$code[6] && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[3]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '5'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[4] > 5 || $code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[4] < 6 && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] < 5 && (int)$code[4] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val($table, '`long`', "`roomid` = '$roomid'");
                if((int)$code[4] > (int)$code[5] && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val($table, 'hu', "`roomid` = '$roomid'");
                if((int)$code[4] < (int)$code[5] && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[4]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '6'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[5] > 5 || $code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[5] < 6 && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] < 5 && (int)$code[5] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[5]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '7'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[6] > 5 || $code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[6] < 6 && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] < 5 && (int)$code[6] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[6]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '8'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[7] > 5 || $code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[7] < 6 && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] < 5 && (int)$code[7] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[7]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '9'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[8] > 5 || $code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[8] < 6 && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] < 5 && (int)$code[8] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[8]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '0'){
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'da', "`roomid` = '$roomid'");
                if((int)$code[9] > 5 || $code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'xiao', "`roomid` = '$roomid'");
                if((int)$code[9] < 6 && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'dan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'shuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val($table, 'dadan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val($table, 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val($table, 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] < 5 && (int)$code[9] != 0 ){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val($table, 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] <= 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[9]){
                $peilv = get_query_val($table, 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '和'){
            if($code[0] == "0" || $code[1] == "0"){
                $hz = (int)$code[0] + (int)$code[1] + 10;
            }else{
                $hz = (int)$code[0] + (int)$code[1];
            }
            if($zym_8 == '大'){
                $peilv = get_query_val($table, 'heda', "`roomid` = '$roomid'");
                if($hz > 11){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val($table, 'hexiao', "`roomid` = '$roomid'");
                if($hz < 12){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val($table, 'hedan', "`roomid` = '$roomid'");
                if($hz % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val($table, 'heshuang', "`roomid` = '$roomid'");
                if($hz % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif((int)$zym_8 == $hz){
                if($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19){
                    $peilv = get_query_val($table, 'he341819', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17){
                    $peilv = get_query_val($table, 'he561617', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15){
                    $peilv = get_query_val($table, 'he781415', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13){
                    $peilv = get_query_val($table, 'he9101213', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 11){
                    $peilv = get_query_val($table, 'he11', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, $game, $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_order", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
    zhangdanSay('bjpk10',1,'fn_order',$qihao);
}
function MT_jiesuan(){
    select_query("fn_mtorder", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '6'");
        $opencode = str_replace('10', '0', $opencode);
        if($opencode == "")continue;
        $code = explode(',', $opencode);
        if($zym_1 == '1'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[0] > 5 || $code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[0] < 6 && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if((int)$code[0] > (int)$code[9] && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if((int)$code[0] < (int)$code[9] && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[0]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '2'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[1] > 5 || $code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[1] < 6 && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if((int)$code[1] > (int)$code[8] && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if((int)$code[1] < (int)$code[8] && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[1]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '3'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[2] > 5 || $code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[2] < 6 && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if((int)$code[2] > (int)$code[7] && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if((int)$code[2] < (int)$code[7] && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[2]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '4'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[3] > 5 || $code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[3] < 6 && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if((int)$code[3] > (int)$code[6] && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if((int)$code[3] < (int)$code[6] && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[3]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '5'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[4] > 5 || $code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[4] < 6 && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery6', '`long`', "`roomid` = '$roomid'");
                if((int)$code[4] > (int)$code[5] && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery6', 'hu', "`roomid` = '$roomid'");
                if((int)$code[4] < (int)$code[5] && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[4]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '6'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[5] > 5 || $code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[5] < 6 && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[5]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '7'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[6] > 5 || $code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[6] < 6 && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[6]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '8'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[7] > 5 || $code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[7] < 6 && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[7]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '9'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[8] > 5 || $code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[8] < 6 && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[8]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '0'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'da', "`roomid` = '$roomid'");
                if((int)$code[9] > 5 || $code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[9] < 6 && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'dan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery6', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery6', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery6', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery6', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[9]){
                $peilv = get_query_val('fn_lottery6', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '和'){
            if($code[0] == "0" || $code[1] == "0"){
                $hz = (int)$code[0] + (int)$code[1] + 10;
            }else{
                $hz = (int)$code[0] + (int)$code[1];
            }
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery6', 'heda', "`roomid` = '$roomid'");
                if($hz > 11){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery6', 'hexiao', "`roomid` = '$roomid'");
                if($hz < 12){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery6', 'hedan', "`roomid` = '$roomid'");
                if($hz % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery6', 'heshuang', "`roomid` = '$roomid'");
                if($hz % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif((int)$zym_8 == $hz){
                if($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19){
                    $peilv = get_query_val('fn_lottery6', 'he341819', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17){
                    $peilv = get_query_val('fn_lottery6', 'he561617', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15){
                    $peilv = get_query_val('fn_lottery6', 'he781415', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13){
                    $peilv = get_query_val('fn_lottery6', 'he9101213', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 11){
                    $peilv = get_query_val('fn_lottery6', 'he11', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速摩托', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_mtorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
}
function JSSC_jiesuan(){
    select_query("fn_jsscorder", '*', array("status" => "未结算"));
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    foreach($cons as $con){
        echo $con['id'];
        $id = $con['id'];
        $roomid = $con['roomid'];
        $user = $con['userid'];
        $term = $con['term'];
        $zym_1 = $con['mingci'];
        $zym_8 = $con['content'];
        $zym_7 = $con['money'];
        $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = '7'");
        $opencode = str_replace('10', '0', $opencode);
        if($opencode == "")continue;
        $code = explode(',', $opencode);
        if($zym_1 == '1'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[0] > 5 || $code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[0] < 6 && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] > 5 || (int)$code[0] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 == 0 && (int)$code[0] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[0] % 2 != 0 && (int)$code[0] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if((int)$code[0] > (int)$code[9] && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[0] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if((int)$code[0] < (int)$code[9] && $code[0] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[0]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '2'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[1] > 5 || $code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[1] < 6 && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] > 5 || (int)$code[1] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 == 0 && (int)$code[1] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[1] % 2 != 0 && (int)$code[1] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if((int)$code[1] > (int)$code[8] && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[1] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if((int)$code[1] < (int)$code[8] && $code[1] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[1]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '3'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[2] > 5 || $code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[2] < 6 && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] > 5 || (int)$code[2] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 == 0 && (int)$code[2] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[2] % 2 != 0 && (int)$code[2] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if((int)$code[2] > (int)$code[7] && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[2] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if((int)$code[2] < (int)$code[7] && $code[2] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[2]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '4'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[3] > 5 || $code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[3] < 6 && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] > 5 || (int)$code[3] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 == 0 && (int)$code[3] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[3] % 2 != 0 && (int)$code[3] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery7', '`long`', "`roomid` = '$roomid'");
                if((int)$code[3] > (int)$code[6] && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[3] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if((int)$code[3] < (int)$code[6] && $code[3] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[3]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '5'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[4] > 5 || $code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[4] < 6 && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] > 5 || (int)$code[4] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 == 0 && (int)$code[4] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[4] % 2 != 0 && (int)$code[4] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '龙'){
                $peilv = get_query_val('fn_lottery7', '``', "`roomid` = '$roomid'");
                if((int)$code[4] > (int)$code[5] && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[4] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '虎'){
                $peilv = get_query_val('fn_lottery7', 'hu', "`roomid` = '$roomid'");
                if((int)$code[4] < (int)$code[5] && $code[4] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[4]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '6'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[5] > 5 || $code[5] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[5] < 6 && $code[5] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] > 5 || (int)$code[5] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 == 0 && (int)$code[5] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[5] % 2 != 0 && (int)$code[5] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[5]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '7'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[6] > 5 || $code[6] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[6] < 6 && $code[6] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] > 5 || (int)$code[6] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 == 0 && (int)$code[6] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[6] % 2 != 0 && (int)$code[6] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[6]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '8'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[7] > 5 || $code[7] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[7] < 6 && $code[7] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] > 5 || (int)$code[7] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 == 0 && (int)$code[7] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[7] % 2 != 0 && (int)$code[7] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[7]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '9'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[8] > 5 || $code[8] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[8] < 6 && $code[8] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] > 5 || (int)$code[8] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 == 0 && (int)$code[8] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[8] % 2 != 0 && (int)$code[8] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[8]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '0'){
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'da', "`roomid` = '$roomid'");
                if((int)$code[9] > 5 || $code[9] == '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'xiao', "`roomid` = '$roomid'");
                if((int)$code[9] < 6 && $code[9] != '0'){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'dan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'shuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大单'){
                $peilv = get_query_val('fn_lottery7', 'dadan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] > 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '大双'){
                $peilv = get_query_val('fn_lottery7', 'dashuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] > 5 || (int)$code[9] == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小双'){
                $peilv = get_query_val('fn_lottery7', 'xiaoshuang', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 == 0 && (int)$code[9] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小单'){
                $peilv = get_query_val('fn_lottery7', 'xiaodan', "`roomid` = '$roomid'");
                if((int)$code[9] % 2 != 0 && (int)$code[9] < 5){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == $code[9]){
                $peilv = get_query_val('fn_lottery7', 'tema', "`roomid` = '$roomid'");
                $zym_11 = $peilv * (int)$zym_7;
                用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
        if($zym_1 == '和'){
            if($code[0] == "0" || $code[1] == "0"){
                $hz = (int)$code[0] + (int)$code[1] + 10;
            }else{
                $hz = (int)$code[0] + (int)$code[1];
            }
            if($zym_8 == '大'){
                $peilv = get_query_val('fn_lottery7', 'heda', "`roomid` = '$roomid'");
                if($hz > 11){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '小'){
                $peilv = get_query_val('fn_lottery7', 'hexiao', "`roomid` = '$roomid'");
                if($hz < 12){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '单'){
                $peilv = get_query_val('fn_lottery7', 'hedan', "`roomid` = '$roomid'");
                if($hz % 2 != 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif($zym_8 == '双'){
                $peilv = get_query_val('fn_lottery7', 'heshuang', "`roomid` = '$roomid'");
                if($hz % 2 == 0){
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }else{
                    $zym_11 = '-' . $zym_7;
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }elseif((int)$zym_8 == $hz){
                if($hz == 3 || $hz == 4 || $hz == 18 || $hz == 19){
                    $peilv = get_query_val('fn_lottery7', 'he341819', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 5 || $hz == 6 || $hz == 16 || $hz == 17){
                    $peilv = get_query_val('fn_lottery7', 'he561617', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 7 || $hz == 8 || $hz == 14 || $hz == 15){
                    $peilv = get_query_val('fn_lottery7', 'he781415', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 9 || $hz == 10 || $hz == 12 || $hz == 13){
                    $peilv = get_query_val('fn_lottery7', 'he9101213', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }elseif($hz == 11){
                    $peilv = get_query_val('fn_lottery7', 'he11', "`roomid` = '$roomid'");
                    $zym_11 = $peilv * (int)$zym_7;
                    用户_上分($user, $zym_11, $roomid, '极速赛车', $term, $zym_1 . '/' . $zym_8 . '/' . $zym_7);
                    update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                    continue;
                }
            }else{
                $zym_11 = '-' . $zym_7;
                update_query("fn_jsscorder", array("status" => $zym_11), array('id' => $id));
                continue;
            }
        }
    }
}
function K3_jiesuan($addterm = "", $addcode=""){
	if($addterm != "" && $addcode != ""){
		select_query('fn_k3order','*',array('status'=>'未结算','term'=>$addterm));
	}else{
		select_query('fn_k3order','*',array('status'=>'未结算'));
	}
	while($con = db_fetch_array()){
		$cons[] = $con;
	}
	foreach($cons as $con){
		$id = $con['id'];
		$roomid = $con['roomid'];
		$gametype = get_query_val('fn_lottery9', 'setting_open', array('roomid'=>$roomid));
		$user = $con['userid'];
		$term = $con['term'];
		$名次 = $con['mingci'];
		$内容 = $con['content'];
		$金额 = $con['money'];
		if($addterm != "" && $addcode != ""){
			$opencode = $addcode;
		}else{
			$opencode = get_query_val('fn_open','code',"`term` = '$term' and `type` = '9-$gametype'");
		}
		if($opencode == "") continue;
		$codes = explode(',',$opencode);
		$sum = $codes[0] + $codes[1] + $codes[2];

		if($codes[0] == $codes[1] && $codes[1] == $codes[2]){
			$ts = "豹子";
		}elseif($codes[0] == $codes[1] || $codes[1] == $codes[2] || $codes[0] == $codes[2]){
			$ts = "对子";
		}elseif($codes[0] + 1 == $codes[1] && $codes[1] + 1 == $codes[2]){
			$ts = "顺子";
		}else{
			$ts = "三杂";
		}
		$settings = get_query_val('fn_lottery9', '*', array('roomid'=>$roomid));
		if($settings['setting_10shazuhe'] == "true"){
			$kai10shazuhe = true;
		}else{
			$kai10shazuhe = false;
		}
		if($settings['setting_baozitongsha'] == "true"){
			$baozitongsha = true;
		}else{
			$baozitongsha = false;
		}

		switch($名次){
			case '总':
				switch($内容){
					case '大':
						$peilv = get_query_val('fn_lottery9','da',"`roomid` = '$roomid'");
						if($sum > 10){
							if($baozitongsha && $ts == "豹子"){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '小':
						$peilv = get_query_val('fn_lottery9','xiao',"`roomid` = '$roomid'");
						if($sum < 11){
							if($baozitongsha && $ts == "豹子"){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '单':
						$peilv = get_query_val('fn_lottery9','dan',"`roomid` = '$roomid'");
						if($sum %2 != 0){
							if($baozitongsha && $ts == "豹子"){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '双':
						$peilv = get_query_val('fn_lottery9','shuang',"`roomid` = '$roomid'");
						if($sum %2 == 0){
							if($baozitongsha && $ts == "豹子"){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '大单':
						$peilv = get_query_val('fn_lottery9','dadan',"`roomid` = '$roomid'");
						if($sum %2 != 0 && $sum >10){
							if($kai10shazuhe && $sum == 10){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '小单':
						$peilv = get_query_val('fn_lottery9','xiaodan',"`roomid` = '$roomid'");
						if($sum %2 != 0 && $sum < 11){
							if($kai10shazuhe && $sum == 10){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '大双':
						$peilv = get_query_val('fn_lottery9','dashuang',"`roomid` = '$roomid'");
						if($sum %2 == 0 && $sum > 10){
							if($kai10shazuhe && $sum == 10){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '小双':
						$peilv = get_query_val('fn_lottery9','xiaoshuang',"`roomid` = '$roomid'");
						if($sum %2 == 0 && $sum < 11){
							if($kai10shazuhe && $sum == 10){
								$中 = '-'.$金额;
								update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
								break;
							}
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
				}
				break;
			case '特':
				switch($内容){
					case '顺子':
						$peilv = get_query_val('fn_lottery9','tong_shunzi',"`roomid` = '$roomid'");
						if($ts == "顺子"){
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '豹子':
						$peilv = get_query_val('fn_lottery9','tong_baozi',"`roomid` = '$roomid'");
						if($ts == "豹子"){
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '对子':
						$peilv = get_query_val('fn_lottery9','tong_duizi',"`roomid` = '$roomid'");
						if($ts == "对子"){
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '三杂':
						$peilv = get_query_val('fn_lottery9','tong_sanza',"`roomid` = '$roomid'");
						if($ts == "三杂" || $ts == '顺子'){
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
					case '二杂':
						$peilv = get_query_val('fn_lottery9','tong_erza',"`roomid` = '$roomid'");
						if($codes[0] != $codes[1] || $codes[1] != $codes[2] || $codes[0] != $codes[2]){
							$中 = $peilv * (int)$金额;
							用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}else{
							$中 = '-'.$金额;
							update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
							break;
						}
						break;
				}
				break;
			case '豹子':
				$peilv = get_query_val('fn_lottery9','zhi_baozi',"`roomid` = '$roomid'");
				if($ts == "豹子" && ($codes[0].$codes[1].$codes[2]) == $内容){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
			case '顺子':
				$peilv = get_query_val('fn_lottery9','zhi_shunzi',"`roomid` = '$roomid'");
				if($ts == "顺子" && ($codes[0].$codes[1].$codes[2]) == $内容){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
			case '对子':
				$peilv = get_query_val('fn_lottery9','zhi_duizi',"`roomid` = '$roomid'");
				if($ts == "对子" && strpos($codes[0].$codes[1].$codes[2], $内容) !== false){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
			case '三杂':
				$peilv = get_query_val('fn_lottery9','zhi_sanza',"`roomid` = '$roomid'");
				$c = $codes[0].$codes[1].$codes[2];
				if(($ts == "三杂" || $ts == '顺子') && strpos($c, $内容) !== false){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
			case '二杂':
				$peilv = get_query_val('fn_lottery9','zhi_erza',"`roomid` = '$roomid'");
				$c = $codes[0].$codes[1].$codes[2];
				if(strpos($c, $内容) !== false){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
			case '三军':
				$peilv = get_query_val('fn_lottery9','zhi_sanjun',"`roomid` = '$roomid'");
				$c = $codes[0].$codes[1].$codes[2];
				if(strpos($c, $内容) !== false){
					$中 = $peilv * (int)$金额;
					用户_上分($user,$中,$roomid,'快三娱乐',$term,$名次.'/'.$内容.'/'.$金额); 
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}else{
					$中 = '-'.$金额;
					update_query('fn_k3order',array('status'=>$中),array('id'=>$id));
					break;
				}
				break;
		
		}
		continue;
	}
}

function kaichat($game, $term){
    if($game == 'bjpk10'){
        select_query('fn_lottery1', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'pk10');
            echo "bjpk10喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'mlaft'){
        select_query('fn_lottery2', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'xyft');
            echo "mlaft喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'cqssc'){
        select_query('fn_lottery3', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'cqssc');
            echo "mlaft喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'bjkl8'){
        select_query('fn_lottery4', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'xy28');
            echo "bjkl8喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'cakeno'){
        select_query('fn_lottery5', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'jnd28');
            echo "cakeno喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'jsmt'){
        select_query('fn_lottery6', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'jsmt');
            echo "jsmt喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'jssc'){
        select_query('fn_lottery7', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'jssc');
            echo "jssc喊话-" . $con['roomid'] . '..<br>';
        }
    }elseif($game == 'jsssc'){
        select_query('fn_lottery8', '*', array('gameopen' => 'true'));
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            管理员喊话("第 $term 期已经开启,请开始下注!", $con['roomid'], 'jsssc');
            echo "jsssc喊话-" . $con['roomid'] . '..<br>';
        }
    }
}
function 管理员喊话($Content, $roomid, $game){
    $headimg = get_query_val('fn_setting', 'setting_robotsimg', array('roomid' => $roomid));
    insert_query("fn_chat", array("username" => "机器人", "headimg" => $headimg, 'content' => $Content, 'game' => $game, 'addtime' => date('H:i:s'), 'type' => 'S3', 'userid' => 'system', 'roomid' => $roomid));
}
// 兑奖核对
function zhangdanSay($game_name,$game_type,$table,$term){
    $opencode = get_query_val('fn_open', 'code', "`term` = '$term' and `type` = $game_type");
    select_query($table, '*', "term = '{$term}'");
    $names = [];
    $yinkui = [];
    while($con = db_fetch_array()){
        $cons[] = $con;
    }
    if(!empty($cons)){

        #todo 这里可能出现一个用户在不同房间同时投注的情况

        foreach ($cons as $c){
            $names[$c['username']] .= $c['mingci']."/".$c['content']."/".$c['money']." ";
            $yinkui[$c['username']] += $c['status'] + $c['money'];
        }
        $roomlist = array_unique(array_column($cons,'roomid'));
        $nameInfo = '';
        foreach ($names as $name => $res){
            $nameInfo .= $name.": [".$res."]<br />";
            $nameInfo .= "当期盈亏：".$yinkui[$name].'<br />';
            $nameInfo .= '========================='.'<br />';
        }
        $content = "-------开奖核对--------".'<br />';
        $content .= "期号：".$term.'<br />';
        $content .= "开奖结果：".$opencode.'<br />';
        $content .= $nameInfo;
        foreach ($roomlist as $roomid){
            管理员喊话( $content, $roomid, $game_name);
        }

    }
}
?>