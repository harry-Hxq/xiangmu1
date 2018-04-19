<?php
include_once("../Public/config.php");
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>聊天下注机器人</title>
		<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    </head>
    <body>
        <?php if(get_query_val("fn_lottery1", "gameopen", array("roomid" => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="pk10">
			<iframe src="Application/robot_bet.php?g=pk10" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=pk10" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(1);">北京赛车封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="xyft">
			<iframe src="Application/robot_bet.php?g=xyft" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=xyft" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(2);">幸运飞艇封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery3', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="cqssc">
			<iframe src="Application/robot_bet.php?g=cqssc" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=cqssc" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(3);">重庆时时彩封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="xy28">
			<iframe src="Application/robot_bet.php?g=xy28" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=xy28" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(4);">北京28封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="jnd28">
			<iframe src="Application/robot_bet.php?g=jnd28" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=jnd28" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(5);">加拿大28封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery6', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="jsmt">
			<iframe src="Application/robot_bet.php?g=jsmt" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=jsmt" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(6);">极速摩托封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery7', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="jssc">
			<iframe src="Application/robot_bet.php?g=jssc" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=jssc" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(7);">极速赛车封盘</button>
		</div>
        <?php }
if(get_query_val('fn_lottery8', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="jsssc">
			<iframe src="Application/robot_bet.php?g=jsssc" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=jsssc" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(8);">极速时时彩封盘</button>
		</div>
		<?php }
if(get_query_val('fn_lottery9', 'gameopen', array('roomid' => $_SESSION['agent_room'])) == 'true'){
    ?>
		<div id="kuai3">
			<iframe src="Application/robot_bet.php?g=kuai3" frameBorder=0 scrolling=no ></iframe>
			<iframe src="Application/robot_point.php?g=kuai3" frameBorder=0 scrolling=no ></iframe>
			<button onclick="stop(8);">江苏快三封盘</button>
		</div>
        <?php }
?>
    </body>
	<script>
		function stop(type){
			switch(type){
				case 1:
					$('#pk10').remove();
					break;
				case 2:
					$('#xyft').remove();
					break;
				case 3:
					$('#cqssc').remove();
					break;
				case 4:
					$('#xy28').remove();
					break;
				case 5:
					$('#jnd28').remove();
					break;
				case 6:
					$('#jsmt').remove();
					break;
				case 7:
					$('#jssc').remove();
					break;
				case 8:
					$('#jsssc').remove();
					break;
				case 9:
					$('#kuai3').remove();
					break;
					
			}
		}
	</script>
</html>