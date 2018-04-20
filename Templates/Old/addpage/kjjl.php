<?php
include_once( "../../../Public/config.php");

$typearr['pk10'] = 1;
$typearr['xyft'] = 2;
$typearr['cqssc'] = 3;
$typearr['bjkl8'] = 4;
$typearr['cakeno'] = 5;
$typearr['jsmt'] = 6;
$typearr['jssc'] = 7;
$typearr['jsssc'] = 8;
$typearr['jsk3'] = 9;
$typearr['baccarat'] = 10;

$typeid = $typearr[$_COOKIE['game']];

$page = $_GET['page'] ? $_GET['page'] : 1 ;
$offset = 20;
$limit = ($page-1)*$offset;
$list = db_query(sprintf("select * from `fn_open` where `type`=%d order by `term` desc limit %d, %d",$typeid,$limit,$offset));

?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no">

    <!-- <title>飞鸟娱乐系统</title> -->
    <script type="text/javascript">
        var ctxPath = '';
    </script>
</head>
<html>
<head>
    <title>开奖记录</title>
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="Stylesheet" type="text/css" href="../../../Style/Old/css/wx.css"/>

</head>
<body>
<div>
<table id="tables" class="tables" border="0" cellpadding="1" cellspacing="1">
    <thead>
    <tr>
        <th class="th_1">开奖期数</th>
        <th class="th_2 no">开奖号码</th>
        <!-- 						<th class="th_3 lh">开奖结果</th> -->
        <th class="th_4">开奖时间</th>
    </tr>
    </thead>
    <tbody>

    <?php
        while($con = db_fetch_array()) {
            $code_arr = explode(",",$con['code']);
            ?>

            <tr>
                <td class="dd_qh"><?php echo $con['term'] ?></td>
<!--                <td class="dd_no"><i>6</i>+<i>3</i>+<i>4</i>=<i class="tm">13</i></td>-->
                <td class="dd_no"><?php
                    foreach ($code_arr as $code){
                        echo '<i>'.$code.'</i>';
                    }
                    ?></td>

                <td class="num3"><?php echo $con['time'] ?></td>
            </tr>

            <?php
        }
    ?>

    </tbody>
</table>
<!-- 		    </div> -->
<div style="height: 1.4rem;text-align: center;font-size: 36px;padding: 8px;margin: 10px;">
    <a href="/fo/youxi/kjjl.html?r=8&pd=1&page=1">上一页</a>
    <a href="/fo/youxi/kjjl.html?r=8&pd=1&page=2">下一页</a>
    <a
        <?php echo "href='/qr.php?room={$_SESSION['roomid']}&g={$_COOKIE['game']}'"; ?>
    >返回游戏</a>
</div>
</div>
</body>
</html>