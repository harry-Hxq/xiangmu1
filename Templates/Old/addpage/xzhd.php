<?php
include_once( "../../../Public/config.php");
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
    <title>下注核对</title>
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="Stylesheet" type="text/css" href="../../../Style/Old/css/wx.css"/>

</head>

<body>
<div class="mui-content">
    <div class="tzjl">本期投注记录</div>
    <table class="tables">
        <thead>
        <tr>
            <th class="th_1">下注信息</th>
            <th class="th_2 no">剩余积分</th>
            <th class="th_4">下注时间</th>
        </tr>
        </thead>
        <tbody>


        <tr>
            <td class="dd_qh">大双150</td>
            <td class="dd_no">1800</td>
            <td class="num3"> 11:16:06</td>
        </tr>

        <tr>
            <td class="dd_qh">大双150</td>
            <td class="dd_no">1800</td>
            <td class="num3"> 11:16:06</td>
        </tr>


        </tbody>
    </table>
    <div class="tzjl">历史投注记录</div>
    <table class="tables">
        <thead>
        <tr>
            <th class="th_1">下注信息</th>
            <th class="th_1">剩余积分</th>
            <th class="th_1">下注时间</th>
            <th class="th_1">下注期数</th>
            <th class="th_1">盈亏</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
        </tr>


        <tr>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
        </tr>


        <tr>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
            <td class="dd_qh">11</td>
        </tr>


        <tr>
            <td colspan="5" >没有未结算订单</td>
        </tr>


        </tbody>
    </table>
    <div style="height: 4.4rem;text-align: center;font-size: 60px;">
        <a href="/fo/youxi/kjjl.html?r=8&pd=1&page=1">上一页</a>
        <a href="/fo/youxi/kjjl.html?r=8&pd=1&page=2">下一页</a>
        <a
         <?php echo "href='/qr.php?room={$_SESSION['roomid']}&g={$_COOKIE['game']}'"; ?>
        >返回游戏</a>
    </div>
    </div>
</body>