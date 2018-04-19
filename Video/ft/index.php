<?php
include_once(dirname(dirname(dirname(__FILE__))).'/Public/config.php');
?>
<html style="font-size: 126px;"><head>
        <meta charset="utf-8">
        <title>幸运飞艇开奖直播</title>
          <script src="jquery.js"></script>
    </head>
    <body style="padding: 0px; margin: 0px; background-color: rgb(0, 0, 0); background-position: initial initial; background-repeat: initial initial;">
		<div style="position:absolute;left:250px;top:98px;display:block;color:#fff;z-index:99999;display:none;" id="acontainer"></div>
        <div style="text-align: center; font-size: 0">
            <div id="Cocos2dGameContainer" style="width: 1136px; height: 640px; position: relative; overflow: hidden;"><canvas id="gameCanvas" width="1136" height="640" tabindex="1" style="outline: none; cursor: default;"></canvas></div>
        </div>
	<?
        if(strpos(get_query_val('fn_room','roomadmin',array('roomid'=>$_SESSION['roomid'])),'gd') !== false){    
    ?>
        <script src="cocos2d_gd.js"></script>
    <?
        }else{
    ?>
        <script src="cocos2d.js"></script>
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
	AddList([{"code":"xyft_zhibo_html5","data":["<br><font color='red' style='font-size:45px;'><? echo get_query_val('fn_setting','setting_video',array("roomid"=>$_SESSION['roomid'])); ?></font>"]}]);
<?
}
?>
</script>
</div>
<script src="platform/jsloader.js" id="result"></script>
<script src="platform/cocos1.js"></script>
<script src="platform/cocos2.js"></script>
<script src="platform/cocos3.js"></script>
<script src="resource.js"></script>
<script src="total.js"></script>
<script src="platform/main.js"></script>
</body>
</html>