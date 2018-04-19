<?php
include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_COOKIE['game'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="/Style/Old/css/bootstrap.min.css" />
<script src="/Style/Old/js/jquery.min.js"></script>
<script src="/Style/Old/lib/table/bootstrap-table.js"></script>
<script src="/Style/Old/lib/table/locale/bootstrap-table-zh-CN.js"></script>
<link rel="stylesheet" href="/Style/Old/lib/table/bootstrap-table.css" />
</head>
<style>
body {
	font-family: Arial, Helvetica, sans-serif;
	background: #000 url(/Style/Xs/Public/images/bg.png);
	font-size: 24px;
	font-weight:bold;
}
</style>
<body>
	<div class="container" align="center">
		<div class="panel panel-info">
			<div class="panel-heading">
				未结算投注
			</div>
			<div class="panel-body">
				<table class="table table-striped table-bordered " style="text-align:center;">
				<?php
 if($game == 'xy28' || $game == 'jnd28'){
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_pcorder', '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
								function delBet(id){
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
							</script>
						</tbody>
				<?php }elseif($game == 'cqssc'){
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>球号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_sscorder', '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
								function delBet(id){
									$.ajax({
										url: '/Application/ajax_delSSCbet.php',
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
						</tbody>
                  <?php }elseif($game == 'jsssc'){
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>球号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_jssscorder', '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
								function delBet(id){
									$.ajax({
										url: '/Application/ajax_delJSSSCbet.php',
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
						</tbody>
				<?php }elseif($game == 'jsmt'){
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_mtorder', '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php if($con['mingci'] != '和'){
            echo $con['mingci'] . '名';
        }else{
            echo "和值";
        }
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
								function delBet(id){
									$.ajax({
										url: '/Application/ajax_delMTbet.php',
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
						</tbody>
                  <?php }elseif($game == 'jssc'){
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_jsscorder', '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php if($con['mingci'] != '和'){
            echo $con['mingci'] . '名';
        }else{
            echo "和值";
        }
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
								function delBet(id){
									$.ajax({
										url: '/Application/ajax_delJSSCbet.php',
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
						</tbody>
				<?php }else{
    ?>
						<thead>
							<tr>
								<th>期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query("fn_order", '*', "`userid` = '{$_SESSION['userid']}' and `status` = '未结算' and `roomid` = '{$_SESSION['roomid']}'");
    while($con = db_fetch_array()){
        $cons[] = $con;
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php if($con['mingci'] != '和'){
            echo $con['mingci'] . '名';
        }else{
            echo "和值";
        }
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td><a href="javascript:delBet(<?php echo $con['id'];
        ?>);" class="btn btn-danger">撤单</a></td>
									</tr>
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
							<script>
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
						</tbody>
					<?php }
?>
				</table>
			</div>
		</div>
	</div>
	<style>
		.win{
			color:green;
		}
		.lose{
			color:red;
		}
		.che{
			color:#428BCA;
			font-weight:bold;
		}
	</style>
	<div class="container" align="center" style="font-size:20px;">
		<div class="panel panel-success">
			<div class="panel-heading">
				今日投注
			</div>
			<div class="panel-body">
				<table data-sort-name="Code" data-sort-order="desc" data-pagination="true" data-page-size="15" data-page-list="[15, 30, 50, 100, All]" data-search="true" data-toggle="table" class="table table-striped table-bordered " style="text-align:center;">
				<?php if($game == 'xy28' || $game == 'jnd28'){
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_pcorder', '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
				<?php }elseif($game == 'cqssc'){
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>球号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_sscorder', '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
          		<?php }elseif($game == 'jsssc'){
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>球号</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_jssscorder', '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
				<?php }elseif($game == 'jsmt'){
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_mtorder', '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
  				<?php }elseif($game == 'jssc'){
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query('fn_jsscorder', '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
				<?php }else{
    ?>
						<thead>
							<tr>
								<th data-field="Code">期号</th>
								<th>车道</th>
								<th>内容</th>
								<th>金额</th>
								<th>投注时间</th>
								<th>结果</th>
							</tr>
						</thead>
						<tbody>
							<?php
     select_query("fn_order", '*', "`roomid` = '{$_SESSION['roomid']}' and `userid` = '{$_SESSION['userid']}' and `status` != '未结算' and `addtime` like '" . date('Y-m-d') . "%'");
    $all_m = 0;
    $all_z = 0;
    while($con = db_fetch_array()){
        $cons[] = $con;
        if($con['status'] != '已退还' && $con['status'] != '已撤单'){
            $all_m += (int)$con['money'];
            if((int)$con['status'] > 0)$all_z += (int)$con['status'];
        }
        ?>
									<tr>
										<td><?php echo $con['term'];
        ?></td>
										<td><?php echo $con['mingci'];
        ?></td>
										<td><?php echo $con['content'];
        ?></td>
										<td><?php echo $con['money'];
        ?></td>
										<td><?php echo $con['addtime'];
        ?></td>
										<td class="<?php if((int)$con['status'] > 0)echo 'win';
        if((int)$con['status'] < 0)echo 'lose';
        if($con['status'] == '已撤单')echo 'che';
        ?>"><?php if($con['status'] == '已撤单'){
            echo '撤单';
        }else{
            echo $con['status'];
        }
        ?></td>
									</tr>		
							<?php }
    if(count($cons) == 0){
        echo '<tr><td colspan="6">没有未结算订单</td></tr>';
    }
    ?>
						</tbody>
					</table>
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>今日流水</th>
								<th>今日盈亏(玩家)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $all_m;
    ?></td>
								<td><?php $a = '-' . $all_m;
    echo (int)$a + $all_z;
    ?></td>
							</tr>
						</tbody>
					</table>
				<?php }
?>
			</div>
		</div>
	</div>
</html>