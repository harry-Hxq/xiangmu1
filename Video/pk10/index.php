<?php
include_once(dirname(dirname(dirname(__FILE__))).'/Public/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>北京赛车pk10开奖直播</title>
          <script src="sj/jquery.js"></script>
          
    </head>
    <body style="padding:0; margin: 0; background: #000;">
		<div style="position:absolute;left:440px;top:230px;display:block;color:#fff;z-index:99999;display:none;font-size:27px" id="acontainer"></div>
        <div style="text-align: center; font-size: 0">
            <canvas id="gameCanvas" width="1136" height="640"></canvas>
        </div>
    <?
        if(strpos(get_query_val('fn_room','roomadmin',array('roomid'=>$_SESSION['roomid'])),'gd') !== false){    
    ?>
        <script src="sj/cocos2d_gd.js"></script>
    <?
        }else{
    ?>
        <script src="sj/cocos2d.js"></script>
    <?
        }
    ?>
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
	AddList([{"code":"xyft_zhibo_html5","data":["<? echo get_query_val('fn_setting','setting_video',array("roomid"=>$_SESSION['roomid'])); ?>"]}]);
<?
}
?>
</script>
</div>

    </body>
</html>
