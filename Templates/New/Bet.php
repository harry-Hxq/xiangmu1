<?php
include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = get_query_val('fn_setting', 'setting_game', array('roomid' => $_SESSION['roomid']));
if($game == 'pk10'){
    $type = 1;
    $alljilu = get_query_val('fn_order', 'count(*)', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%'");
    $allpage = (int)($alljilu / 11);
}elseif($game == 'xyft'){
    $type = 2;
    $alljilu = get_query_val('fn_order', 'count(*)', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%'");
    $allpage = (int)($alljilu / 11);
}elseif($game == 'cqssc'){
    $type = 3;
}elseif($game == 'xy28'){
    $type = 4;
    $alljilu = get_query_val('fn_pcorder', 'count(*)', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%'");
    $allpage = (int)($alljilu / 11);
}elseif($game == 'jnd28'){
    $type = 5;
    $alljilu = get_query_val('fn_pcorder', 'count(*)', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%'");
    $allpage = (int)($alljilu / 11);
}
if($allpage == 0)$allpage = 1;
$page = $_GET['page'] == "" ? "1" : $_GET['page'];
$sqlpage = ($page - 1) * 11;
?>
<script type="text/javascript" src="/Style/New/js/jquery.min.js"></script>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
    <thead>
        <tr align="center">
            <th style=" height:50px;border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">期号</th>
            <th style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">内容</th>
            <th style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">金额</th>
            <th style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">盈亏</th>
            <th style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">时间</th>
            <th style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; " width="16%">操作</th>
        </tr>
    </thead>
    <tbody align="center">
    <?php
 if($game == 'pk10' || $game == 'xyft'){
    select_query('fn_order', '*', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%' order by id desc limit $sqlpage,11");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
        <tr>
            <td style=" height:50px;border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; text-align">
                <?php echo substr($con['term'], -3);
        ?>期
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo $con['mingci'] . '/' . $con['content'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo $con['money'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:<?php if($con['status'] == '未结算'){
            echo '#FFFFFF';
        }elseif($con['status'] == '已撤单'){
            echo '#FA9F34';
        }elseif($con['status'] < 0){
            echo '#68C600';
        }elseif($con['status'] > 0){
            echo '#FF0000';
        }else{
            echo "#EEEEEE";
        }
        ?>;">
                <?php echo $con['status'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo date("H:i:s", strtotime($con['addtime']));
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php if($con['status'] != '未结算'){
            echo '--';
        }else{
            ?>
                <a href="javascript:delBet(<?php echo $con['id'];
            ?>)" style="color:red;font-weight:bold;font-size:2.2rem;text-decoration:none">撤单</a>
            </td>
        </tr>
    <?php }
    }
    if(count($cons) == 0){
        echo '<tr><td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF;" colspan="6">今日没有对应订单</td>></tr>>';
    }
}elseif($game == 'xy28' || $game == 'jnd28'){
    select_query('fn_pcorder', '*', "userid = '{$_SESSION['userid']}' and addtime like '" . date('Y-m-d') . "%' order by id desc limit $sqlpage,11");
    while($con = db_fetch_array()){
        ?>
        <tr>
            <td style=" height:50px;border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; text-align">
                <?php echo substr($con['term'], -3);
        ?>期
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo $con['content'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo $con['money'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:<?php if($con['status'] == '未结算'){
            echo '#FFFFFF';
        }elseif($con['status'] == '已撤单'){
            echo '#FA9F34';
        }elseif($con['status'] < 0){
            echo '#68C600';
        }elseif($con['status'] > 0){
            echo '#FF0000';
        }else{
            echo "#EEEEEE";
        }
        ?>;">
                <?php echo $con['status'];
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php echo date("H:i:s", strtotime($con['addtime']));
        ?>
            </td>
            <td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF; ">
                <?php if($con['status'] != '未结算'){
            echo '--';
        }else{
            ?>
                <a href="javascript:delPCBet(<?php echo $con['id'];
            ?>)" style="color:red;font-weight:bold;font-size:2.2rem;text-decoration:none">撤单</a>
            </td>
        </tr>
    <?php }
    }
    if(count($cons) == 0){
        echo '<tr><td style="border:1px solid #eeeeee; border-collapse:collapse; font-size:32px; color:#FFFFFF;" colspan="6">今日没有对应订单</td>></tr>>';
    }
}
?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" align="left" style="font-size:32px; height:55px; color:#FFFFFF;">
                <div>
                    <center>
                    <?php if($page == 1 && $page == $allpage){
    echo '';
}elseif($page == 1 && $page != $allpage){
    echo "<a style='color:white;' href='Bet.php?page=" . ($page + 1) . "'>下一页</a>";
}elseif($page != $allpage){
    echo "<a style='color:white;' href='Bet.php?page=" . ($page - 1) . "'>上一页</a>&nbsp;<a style='color:white;' href='Bet.php?page=" . ($page + 1) . "'>下一页</a>";
}else{
    echo "<a style='color:white;' href='Bet.php?page=1'>首页</a>&nbsp;<a style='color:white;' href='Bet.php?page=" . ($page - 1) . "'>上一页</a>";
}
?>
                        当前第<?php echo $page;
?>页,共<?php echo $allpage;
?>页,共有<?php echo $alljilu;
?>条记录
                    </center>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
<script>
	function delPCBet(id){
		$.ajax({
			url: '/Application/ajax_delPCbet.php',
			type: 'post',
			data: {id: id},
			dataType: 'json',
			success:function(data){
				if(data.success){
					alert('撤单成功！');
				}else{
					alert(data.msg);
				}
			}
    	});
	}
	function delBet(id){
    	$.ajax({
    		url: '/Application/ajax_delbet.php',
    		type: 'post',
			data: {id: id},
			dataType: 'json',
			success:function(data){
				if(data.success){
					alert('撤单成功！');
				}else{
					alert(data.msg);
				}
			}
		});
	}
</script>