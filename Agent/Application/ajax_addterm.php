<?php
include(dirname(dirname(dirname(__FILE__))).'/Public/config.php');
include(dirname(dirname(dirname(__FILE__))).'/caiji/jiesuan.php');


switch($_POST['game']){
    case '1':
        jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '2':
        jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '3':
        SSC_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '4':
        PC_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '5':
        PC_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '6':
        MT_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '7':
        JSSC_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '8':
        JSSSC_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
    case '9':
        K3_jiesuan($_POST['addterm'], $_POST['addcode']);
        break;
}
echo json_encode(array('status'=>true));

?>