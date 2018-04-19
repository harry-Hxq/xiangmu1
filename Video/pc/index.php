<?
	include_once(dirname(dirname(dirname(__FILE__))).'/Public/config.php');
  if($_COOKIE['game'] == 'xy28'){
    $fengpantime = get_query_val('fn_lottery4','fengtime',array('roomid'=>$_SESSION['roomid']));
  }elseif($_COOKIE['game'] == 'jnd28'){
    $fengpantime = get_query_val('fn_lottery5','fengtime',array('roomid'=>$_SESSION['roomid']));
  }
	
?>
<html>
<head>
<meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0">
<meta http-equiv="expires" content="Sat, 31 Oct 2014 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>娱乐 - PC蛋蛋娱乐 </title>
<style>
       *{
       font-family:arial,Microsoft JhengHei,sans-serif;
       padding:0;
       margin:0;       
       }
       .windowBlock{width:100%;height:100%; position:fixed;margin:0;top:0;left:0;background-color:#f2f2f2; background-repeat:no-repeat;background-position:0 0;background-size:cover;z-index:9999;display:none}
       .windowBlock img{width:100%}
       .windowBlock2{width:100%;height:100%;position:fixed;margin:0;top:0;left:0;background-color:#404040;background-repeat:no-repeat;background-position:0 0;background-size:cover;z-index:9999;text-align:center;padding-top:2.5%;display:none}
       .windowBlock2 img{width:95%}
       .closeBtn{position:fixed;width:80px;height:80px;left:35px;top:35px;z-index:10000;}
    </style>
<link rel="stylesheet" href="css/animate.css">
<script src="js/jquery.js"></script>
<script>
	var fengpantime = <? echo $fengpantime; ?>
</script>
<script src="js/public.js"></script>
<script src="js/jquery.plugin.js"></script>
<script src="js/jquery.timer.js"></script>
<script  type="text/javascript">
 var templatex ="";
$(document).ready(function() {
 templatex = $('#template').html();
init();

});                       

</script>
</head>

<body style="padding:0; margin: 0; background:#000; ">
<div id="reloadtime"></div>
 <div class="content">
      <script type="text/template" id="template">
      <div style=" width:1000px;height:100%; position:relative; margin:0 auto; text-align:center; background-color:#000; background-repeat:no-repeat;  ">
      <div style="position: absolute; top:31px; left:292px; text-align:center; width:70px; height:20px; z-index:9999; ">
      <div style="color:#E4007F; font-size:17px;" id="GamLogo">{GamLogo}</div>
      </div>
      <div style="position: absolute; top:61px; left:308px; text-align:center; width:70px; height:20px; z-index:9999; ">
      <div style="color:#E4007F; font-size:17px;" id="ThisResult">{ThisResult}</div>
      </div>
      <div style="position: absolute; top:40px; left:630px; text-align:center; width:70px; height:20px; z-index:9999;  ">
      <div style="color:#E4007F; font-size:18px;"id="ThisGame">{ThisGame}</div>
      </div>
      <div style="position: absolute; top:61px; left:685px; text-align:center; width:45px; height:20px; z-index:9999; ">
      <div style="color:#2EA7E0; font-size:18px;" id="ThisEnd">{ThisEnd}</div>
      </div>
      <div style="position: absolute; top:61px; left:840px; text-align:center; width:45px; height:20px; z-index:9999; ">
      <div style="color:#8FC31F; font-size:18px;" id="NextStart">{NextStart}</div>
      </div>
      <div style="position: absolute; top:145px; left:120px; text-align:center; width:70px; height:120px; z-index:9999;   ">
      <div style="color:#000; font-size:110px; font-weight:bold; overflow:hidden;" id="Number1" class="animated fadeInDown">{Number1}</div>
      </div>
      <div style="position: absolute; top:145px; left:330px; text-align:center; width:70px; height:120px; z-index:9999;   ">
      <div style="color:#000; font-size:110px; font-weight:bold;" id="Number2" class="animated fadeInDown1">{Number2}</div>
      </div>
      <div style="position: absolute; top:145px; left:545px; text-align:center; width:70px; height:120px; z-index:9999;   ">
      <div style="color:#000; font-size:110px; font-weight:bold;"  id="Number3" class="animated fadeInDown2">{Number3}</div>
      </div>
      <div style="position: absolute; top:145px; left:755px; text-align:center; width:120px; height:120px; z-index:9999;  ">
      <div style="color:#000; font-size:110px; font-weight:bold;"  id="NumberSum" class="animated fadeInDown3">{NumberSum}</div>
      </div>

      <div style="position: absolute; top:335px; left:144px; text-align:center; width:110px; height:60px; z-index:9999;  ">
      <div style="color:#E60012; font-size:36px; font-weight:bold" id="Combin"  class="animated fadeIn">{Combin}{Combin2}</div>
      </div>

      <div style="position: absolute; top:335px; left:352px; text-align:center; width:110px; height:60px; z-index:9999;  ">
      <div style="color:#E60012; font-size:36px; font-weight:bold" id="HomeAway"  class="animated fadeIn1">{HomeAway}</div>
      </div>

      <div style="position: absolute; top:335px; left:557px; text-align:center; width:110px; height:60px; z-index:9999;  ">
      <div style="color:#E60012; font-size:36px; font-weight:bold" id="BigSmall"  class="animated fadeIn2">{BigSmall}</div>
      </div>

      <div style="position: absolute; top:335px; left:790px; text-align:center; width:110px; height:60px; z-index:9999;  ">
      <div style="color:#E60012; font-size:36px; font-weight:bold" id="Triple"  class="animated fadeIn3">{Triple}</div>
      </div>

      <img src="img/bg2.jpg" width="100%">
      </div>
       </script>
  <div>
</body>
</html>
