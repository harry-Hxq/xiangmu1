<?php
include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_COOKIE['game'];
$page = $_GET['page'] ? $_GET['page'] : 1 ;
$offset = 50;
$limit = ($page-1)*$offset;
?>
<link rel="stylesheet" type="text/css" href="/Style/Old/css/common.css"/>
<body align="center">
<br>
<strong style="font-size:58px;">游戏走势图</strong>
<?php if ($game == 'pk10') {
    $type = '1';
} elseif ($game == 'xyft') {
    $type = '2';
} elseif ($game == 'cqssc') {
    $type = '3';
} elseif ($game == 'xy28') {
    $type = '4';
} elseif ($game == 'jnd28') {
    $type = '5';
} elseif ($game == 'jsmt') {
    $type = '6';
} elseif ($game == 'jssc') {
    $type = '7';
} elseif ($game == 'jsssc') {
    $type = '8';
} elseif ($game == 'kuai3') {
    $type = '9';
} else {
    echo '<br><br><strong style="font-size:40px;color:red">该房间正在维护中！';
    exit();
}
?>
<div class="g_w1000min open_form">
    <div class="sub_right  ">
        <div class="sub_r_bot">
            <div class="sub_bot_one">
                <span class="sub_bot_bt">开奖记录</span>
                <!--<span class="r"><a href="#" class="g_button btn_orange" target="_blank">更多历史</a></span>-->
            </div>
        </div>
        <style>
            .dx_shadow {
                width: 1000px;
            }

            .top_right {
                border-bottom: 1px solid #e3e3e3;
                font-size: 20px;
                height: 25px;
                line-height: 25px;
            }

            .top_right_red {
                border-bottom: 1px solid #e3e3e3;
                color: red;
                font-size: 20px;
                height: 25px;
                line-height: 25px;
            }

            .chuxian {
                height: 25px;
                line-height: 25px;
            }
        </style>
        <div class="sm g_hide"></div>
        <table class="sub_table" cellpadding="0" cellspacing="0" border="0" width="980">
            <?php if ($game == 'xy28' || $game == 'jnd28') {
                ?>
                <thead>
                <tr id="th_header">
                    <th width="163" class="">时间</th>
                    <th width="103" class="">期数</th>
                    <th width="380" class="">开奖号码</th>
                    <th colspan="3" class="" width="103">总和</th>
                </tr>
                </thead>
                <tbody id="reslist" linNos="0,1,2,5">
                <?php
                select_query('fn_open', '*', "`type` = '$type' order by `term` desc limit $limit , $offset");
                while ($con = db_fetch_array()) {
                    $cons[] = $con;
                }
                $ay = true;
                foreach ($cons as $con) {
                    if ($ay) {
                        $line_row = "";
                        $ay = false;
                    } else {
                        $line_row = "line_row";
                        $ay = true;
                    }
                    $ys['小'] = 'blue';
                    $ys['大'] = 'gray';
                    $ys['单'] = 'blue';
                    $ys['双'] = 'gray';
                    $ys['龙'] = 'gray';
                    $ys['虎'] = 'blue';
                    $codes = explode(",", $con['code']);
                    if (count($codes) != 20) {
                        continue;
                    } else {
                        if ($type == '4') {
                            $number1 = (int)$codes[0] + (int)$codes[1] + (int)$codes[2] + (int)$codes[3] + (int)$codes[4] + (int)$codes[5];
                            $number2 = (int)$codes[6] + (int)$codes[7] + (int)$codes[8] + (int)$codes[9] + (int)$codes[10] + (int)$codes[11];
                            $number3 = (int)$codes[12] + (int)$codes[13] + (int)$codes[14] + (int)$codes[15] + (int)$codes[16] + (int)$codes[17];
                        } elseif ($type == '5') {
                            $number1 = (int)$codes[1] + (int)$codes[4] + (int)$codes[7] + (int)$codes[10] + (int)$codes[13] + (int)$codes[16];
                            $number2 = (int)$codes[2] + (int)$codes[5] + (int)$codes[8] + (int)$codes[11] + (int)$codes[14] + (int)$codes[17];
                            $number3 = (int)$codes[3] + (int)$codes[6] + (int)$codes[9] + (int)$codes[12] + (int)$codes[15] + (int)$codes[18];
                        }
                        $number1 = substr($number1, -1);
                        $number2 = substr($number2, -1);
                        $number3 = substr($number3, -1);
                        $hz = (int)$number1 + (int)$number2 + (int)$number3;
                    }
                    $dx = $hz < 14 ? '小' : '大';
                    $ds = $hz % 2 == 0 ? '双' : '单';
                    ?>
                    <tr class="<?php echo $line_row;
                    ?>">
                        <td><?php echo date("H:i:s", strtotime($con['time']));
                            ?></td>
                        <td> <?php echo $con['term'];
                            ?> </td>
                        <td>
											<span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number1;
                                                ?></span>
                            <span class="ball_s_">+</span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number2;
                                ?></span>
                            <span class="ball_s_">+</span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number3;
                                ?></span>
                        </td>
                        <td class="count"><?php echo $hz;
                            ?></td>
                        <td class="<?php echo $ys[$dx];
                        ?>"><?php echo $dx;
                            ?></td>
                        <td class="<?php echo $ys[$ds];
                        ?>"><?php echo $ds;
                            ?></td>
                    </tr>
                <?php }
                ?>
                </tbody>
            <?php } elseif ($game == 'cqssc' || $game == 'jsssc') {
                ?>
                <thead>
                <tr id="th_header">
                    <th width="163" class="">时间</th>
                    <th width="103" class="">期数</th>
                    <th width="380" class="">开奖号码</th>
                    <th colspan="3" class="" width="103">总和</th>
                    <th width="50" class="">龙虎</th>

                </tr>
                </thead>
                <tbody id="reslist" linNos="0,1,2,5">
                <?php
                select_query('fn_open', '*', "`type` = '$type' order by `term` desc limit $limit,$offset");
                while ($con = db_fetch_array()) {
                    $cons[] = $con;
                }
                $ay = true;
                foreach ($cons as $con) {
                    if ($ay) {
                        $line_row = "";
                        $ay = false;
                    } else {
                        $line_row = "line_row";
                        $ay = true;
                    }
                    $ys['小'] = 'blue';
                    $ys['大'] = 'gray';
                    $ys['单'] = 'blue';
                    $ys['双'] = 'gray';
                    $ys['龙'] = 'gray';
                    $ys['虎'] = 'blue';
                    $codes = explode(",", $con['code']);
                    if (count($codes) != 5) {
                        continue;
                    } else {
                        if ($type == '3') {
                            $number1 = (int)$codes[0];
                            $number2 = (int)$codes[1];
                            $number3 = (int)$codes[2];
                            $number4 = (int)$codes[3];
                            $number5 = (int)$codes[4];

                        } elseif ($type == '8') {
                            $number1 = (int)$codes[0];
                            $number2 = (int)$codes[1];
                            $number3 = (int)$codes[2];
                            $number4 = (int)$codes[3];
                            $number5 = (int)$codes[4];
                        }
                        $number1 = substr($number1, -1);
                        $number2 = substr($number2, -1);
                        $number3 = substr($number3, -1);
                        $number4 = substr($number4, -1);
                        $number5 = substr($number5, -1);

                        $hz = (int)$number1 + (int)$number2 + (int)$number3 + (int)$number4 + (int)$number5;

                    }
                    $ds = $hz % 2 != 0 ? '单' : '双';
                    $dx = $hz > 22 ? '大' : '小';
                    ?>
                    <tr class="<?php echo $line_row;
                    ?>">
                        <td><?php echo date("H:i:s", strtotime($con['time']));
                            ?></td>
                        <td> <?php echo substr($con['term'], -3, 3) ?> </td>
                        <td>
											<span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number1;
                                                ?></span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number2;
                                ?></span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number3;
                                ?></span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number4;
                                ?></span>
                            <span class="  ball_s_ ball_s_blue ball_lenght5  "><?php echo $number5;
                                ?></span>
                        </td>
                        <td class="count"><?php echo $hz;
                            ?></td>
                        <td class="<?php echo $ys[$dx];
                        ?>"><?php echo $dx;
                            ?></td>
                        <td class="<?php echo $ys[$ds];
                        ?>"><?php echo $ds;
                            ?></td>
                        <td class=" ball_s_blue ball_lenght5  ">
                            <?php if ($codes[0] > $codes[4]) {
                                echo '龙';
                            } elseif ($codes[0] == $codes[4]) {
                                echo '和';
                            } else {
                                echo '虎';
                            }
                            ?>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            <?php } else {
                ?>
                <thead>
                <tr id="th_header">
                    <th width="93" class="">时间</th>
                    <th width="90" class="">期数</th>
                    <th width="380" class="">开奖号码</th>
                    <th colspan='3' class="g_r_line">冠亚</th>
                    <th colspan='5'>1-5球龙虎</th>
                </tr>
                </thead>
                <tbody id="reslist" linNos="0,1,2,5">
                <?php
                select_query('fn_open', '*', "`type` = '$type' order by `term` desc limit $limit , $offset");
                while ($con = db_fetch_array()) {
                    $cons[] = $con;
                }
                $ay = true;
                foreach ($cons as $con) {
                    if ($ay) {
                        $line_row = "";
                        $ay = false;
                    } else {
                        $line_row = "line_row";
                        $ay = true;
                    }
                    $g = explode(",", $con['code']);
                    $ys['小'] = 'blue';
                    $ys['大'] = 'gray';
                    $ys['单'] = 'blue';
                    $ys['双'] = 'gray';
                    $ys['龙'] = 'gray';
                    $ys['虎'] = 'blue';
                    $h['冠亚'] = $g[0] + $g[1];
                    $h['冠亚大小'] = ($h['冠亚'] > 11) ? '大' : '小';
                    $h['冠亚单双'] = (($h['冠亚'] % 2) == 0) ? '双' : '单';
                    $adb = array();
                    $adb[] = ($g[0] > $g[9]) ? '龙' : '虎';
                    $adb[] = ($g[1] > $g[8]) ? '龙' : '虎';
                    $adb[] = ($g[2] > $g[7]) ? '龙' : '虎';
                    $adb[] = ($g[3] > $g[6]) ? '龙' : '虎';
                    $adb[] = ($g[4] > $g[5]) ? '龙' : '虎';
                    ?>
                    <tr class="<?php echo $line_row;
                    ?>">
                        <td><?php echo date("H:i:s", strtotime($con['time']));
                            ?></td>
                        <td><?php if ($type == '1' || $type == '6' || $type == '7') {
                                echo $con['term'];
                            } else {
                                echo substr($con['term'], 8);
                            }
                            ?></td>
                        <td>
											<span class="ball_pks_  ball_pks<?php echo (int)$g[0];
                                            ?> ball_lenght10" title="<?php echo (int)$g[0];
                                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[1];
                            ?> ball_lenght10" title="<?php echo (int)$g[1];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[2];
                            ?> ball_lenght10" title="<?php echo (int)$g[2];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[3];
                            ?> ball_lenght10" title="<?php echo (int)$g[3];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[4];
                            ?> ball_lenght10" title="<?php echo (int)$g[4];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[5];
                            ?> ball_lenght10" title="<?php echo (int)$g[5];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[6];
                            ?> ball_lenght10" title="<?php echo (int)$g[6];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[7];
                            ?> ball_lenght10" title="<?php echo (int)$g[7];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[8];
                            ?> ball_lenght10" title="<?php echo (int)$g[8];
                            ?>">&nbsp;</span>
                            <span class="ball_pks_  ball_pks<?php echo (int)$g[9];
                            ?> ball_lenght10" title="<?php echo (int)$g[9];
                            ?>">&nbsp;</span>
                        </td>
                        <td class="count"><?php echo $h['冠亚'];
                            ?></td>
                        <td class="<?php echo $ys[$h['冠亚大小']];
                        ?>"><?php echo $h['冠亚大小'];
                            ?></td>
                        <td class="<?php echo $ys[$h['冠亚单双']];
                        ?> g_r_line g_td_p_right"><?php echo $h['冠亚单双'];
                            ?></td>
                        <td class="<?php echo $ys[$adb[0]];
                        ?> g_td_p_left"><?php echo $adb[0];
                            ?></td>
                        <td class="<?php echo $ys[$adb[1]];
                        ?>"><?php echo $adb[1];
                            ?></td>
                        <td class="<?php echo $ys[$adb[2]];
                        ?>"><?php echo $adb[2];
                            ?></td>
                        <td class="<?php echo $ys[$adb[3]];
                        ?>"><?php echo $adb[3];
                            ?></td>
                        <td class="<?php echo $ys[$adb[4]];
                        ?>"><?php echo $adb[4];
                            ?></td>
                    </tr>
                <?php }
                ?>
                </tbody>
            <?php }
            ?>
        </table>
        <div class="sub_hr"></div>
        <div class="pages-jl">
            <a class="pages-jla" <?php if($page == 1) echo "style='display:none'"; ?> href="/Templates/Old/BetTrend.php?page=<?php echo $page-1 ?>">上一页</a>
            <a class="pages-jla" href="/Templates/Old/BetTrend.php?page=<?php echo $page+1 ?>">下一页</a>
            <a class="pages-jla"
                <?php echo "href='/qr.php?room={$_SESSION['roomid']}&g={$_COOKIE['game']}'"; ?>
            >返回游戏</a>
        </div>
    </div>
</div>
<div class="clear"></div>
</body>