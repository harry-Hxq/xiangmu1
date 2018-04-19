<?php
include_once(dirname(dirname(dirname(__FILE__))).'/Public/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>极速摩托开奖直播</title>
          <script src="./src/jquery.js"></script>
          
    </head>
    <body style="padding:0; margin: 0; background: #000;">
		<div style="position:absolute;left:250px;top:98px;display:block;color:#fff;z-index:99999;display:none;" id="acontainer"></div>
        <div style="text-align: center; font-size: 0">
            <canvas id="gameCanvas" width="1136" height="640"></canvas>
        </div>
        <script src="./cocos2d.js"></script>
            <div style="display:none;">

<script type="text/javascript">


var acontainer = $('#acontainer');
var hasData = false;
function AddList(data){
	if(data.length > 0){
		hasData = true;
		acontainer.html(data[0].data);
		acontainer.show();
	}
}
function beforeStartRunning(){
	acontainer.hide();
}
function afterEndRunning(){
	if(hasData)acontainer.show();
}
<?
if(get_query_val('fn_setting','setting_video',array("roomid"=>$_SESSION['roomid'])) != ""){
?>
	AddList([{"code":"xyft_zhibo_html5","data":["<br><font color='yellow' style='font-size:45px;'><? echo get_query_val('fn_setting','setting_video',array("roomid"=>$_SESSION['roomid'])); ?></font>"]}]);
<?
}
?>
</script>
</div>

    </body>
</html>
