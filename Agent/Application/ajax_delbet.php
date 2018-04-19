<?php
include(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
$type = $_GET['t'];
if($type == '1'){
    $game = $_POST['game'];
    $id = $_POST['id'];
    switch($game){
    case 'xy28': $table = "fn_pcorder";
        break;
    case "jnd28": $table = "fn_pcorder";
        break;
    case "jsmt": $table = "fn_jsmtorder";
        break;
    case "jssc": $table = "fn_jsscorder";
        break;
    case "jsssc": $table = "fn_jssscorder";
        break;
    case "cqssc": $table = "fn_sscorder";
        break;
    case "pk10": $table = "fn_order";
        break;
    case "xyft": $table = "fn_order";
        break;
	case "kuai3": $table = "fn_k3order";
        break;
    }
    delete_query($table, array('id' => $id));
    echo json_encode(array("success" => true));
}elseif($type == '2'){
    $game = $_POST['game'];
    $id = $_POST['id'];
    switch($game){
    case 'xy28': $table = "fn_pcorder";
        break;
    case "jnd28": $table = "fn_pcorder";
        break;
    case "jsmt": $table = "fn_jsmtorder";
        break;
    case "jssc": $table = "fn_jsscorder";
        break;
    case "jsssc": $table = "fn_jssscorder";
        break;
    case "cqssc": $table = "fn_sscorder";
        break;
    case "pk10": $table = "fn_order";
        break;
    case "xyft": $table = "fn_order";
        break;
	case "kuai3": $table = "fn_k3order";
        break;
    }
    $m = get_query_vals('fn_order', '*', array('id' => $id));
    $term = $m['term'];
    $userid = $m['userid'];
    $m = $m['money'];
    update_query("fn_order", array("status" => "已撤单"), array("id" => $id));
    backmoney($userid, $m, $term, $_SESSION['agent_room']);
    echo json_encode(array("success" => true));
}elseif($type == '3'){
    $game = $_POST['game'];
    if($game == 'pk10'){
        delete_query('fn_order', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) < 8");
    }elseif($game == 'xyft'){
        delete_query('fn_order', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) > 8");
    }elseif($game == 'xy28'){
        delete_query('fn_pcorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` < 2000000");
    }elseif($game == 'jnd28'){
        delete_query('fn_pcorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
    }elseif($game == 'cqssc'){
        delete_query('fn_sscorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jsssc'){
        delete_query('fn_jssscorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jssc'){
        delete_query('fn_jsscorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jsmt'){
        delete_query('fn_jsmtorder', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }
	elseif($game == 'kuai3'){
        delete_query('fn_k3order', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }
    echo json_encode(array("success" => true));
}elseif($type == '4'){
    $game = $_POST['game'];
    if($game == 'pk10'){
        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) < 8");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_order", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) < 8");
    }elseif($game == 'xyft'){
        select_query('fn_order', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) > 8");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_order", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and length(`term`) > 8");
    }elseif($game == 'xy28'){
        select_query('fn_pcorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_pcorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jnd28'){
        select_query('fn_pcorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_pcorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'cqssc'){
        select_query('fn_sscorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_sscorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jsssc'){
        select_query('fn_jssscorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_jssscorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jssc'){
        select_query('fn_jsscorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_jsscorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }elseif($game == 'jsmt'){
        select_query('fn_jsmtorder', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_jsmtorder", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }
	elseif($game == 'kuai3'){
        select_query('fn_k3order', '*', "roomid = '{$_SESSION['agent_room']}' and status = '未结算' and `term` > 2000000");
        while($con = db_fetch_array()){
            $cons[] = $con;
        }
        foreach($cons as $con){
            $m = $con['money'];
            $userid = $con['userid'];
            $term = $con['term'];
            backmoney($userid, $m, $term, $_SESSION['agent_room']);
        }
        update_query("fn_k3order", array("status" => "已撤单"), "roomid = '{$_SESSION['agent_room']}' and status = '未结算'");
    }
    echo json_encode(array("success" => true));
}
function backmoney($userid, $money, $term, $room){
    update_query('fn_user', array('money' => '+=' . $money), array('userid' => $userid, 'roomid' => $room));
    insert_query("fn_marklog", array("roomid" => $room, 'userid' => $userid, 'type' => '上分', 'content' => '管理员退还' . $term . '期下注', 'money' => $money, 'addtime' => 'now()'));
}
?>