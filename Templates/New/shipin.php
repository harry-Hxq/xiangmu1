<?php include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$game = $_COOKIE['game'];
if($game == 'pk10'){
    if(get_query_val('fn_lottery1', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$game = 'feng';
}elseif($game == 'xyft'){
    if(get_query_val('fn_lottery2', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$game = 'feng';
}elseif($game == 'xy28'){
    if(get_query_val('fn_lottery4', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$game = 'feng';
}elseif($game == 'jnd28'){
    if(get_query_val('fn_lottery5', 'gameopen', array('roomid' => $_SESSION['roomid'])) == 'false')$game = 'feng';
}
if($game == 'pk10'){
    ?>
	<iframe src="/Video/pk10/"width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<?php }elseif($game == 'xyft'){
    ?>	
	<iframe src="/Video/ft/"width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<?php }elseif($game == 'xy28' || $game == 'jnd28'){
    ?>	
	<iframe src="/Video/pc/"width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<?php }elseif($game == 'jsmt'){
    ?>	
	<iframe src="/Video/jsmt/"width="980" height="630" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
<?php }else{
    ?>
<html>
	<img src="/Style/images/fengpan.png">
</html>
<?php }
?>