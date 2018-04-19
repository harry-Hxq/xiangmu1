<?php
include_once("../Public/config.php");
if($_SESSION['agent_user'] != "" && $_SESSION['agent_pass'] != "" && $_SESSION['agent_room'] != ""){
    $sql = get_query_vals('fn_room', '*', array('roomid' => $_SESSION['agent_room']));
    if($_SESSION['agent_user'] != $sql['roomadmin'] || $_SESSION['agent_pass'] != $sql['roompass']){
        $_SESSION['agent_user'] = "";
        $_SESSION['agent_pass'] = "";
        $_SESSION['agent_room'] = "";
        echo '<script>top.location.href="login.php";</script>';
        exit();
    }
}else{
    $_SESSION['agent_user'] = "";
    $_SESSION['agent_pass'] = "";
    $_SESSION['agent_room'] = "";
    echo '<script>top.location.href="login.php";</script>';
    exit();
}
$version = get_query_val('fn_room', 'version', array('roomid' => $_SESSION['agent_room']));
if($_GET['m'] == ''){
    $page = '首页';
}elseif($_GET['m'] == 'g_setting'){
    $page = '游戏设置';
}elseif($_GET['m'] == 'setting'){
    $page = '系统设置';
}elseif($_GET['m'] == 'user'){
    $page = '用户管理';
}elseif($_GET['m'] == 'userjia'){
    $page = '假人管理';
}elseif($_GET['m'] == 'userdata'){
    $page = '用户报表';
}elseif($_GET['m'] == 'termcount'){
    $page = '当期统计';
}elseif($_GET['m'] == 'ban'){
    $page = '禁言管理';
}elseif($_GET['m'] == 'report'){
    $page = '报表查询';
}elseif($_GET['m'] == 'chat'){
    $page = '聊天管理';
}elseif($_GET['m'] == 'robot'){
    $page = '自动拖管理';
}elseif($_GET['m'] == 'extend'){
    $page = '代理系统';
}elseif($_GET['m'] == 'share'){
    $page = '分享房间';
}elseif($_GET['m'] == 'buy'){
    $page = '套餐续费';
}elseif($_GET['m'] == 'flyorder'){
    $page = '飞单系统';
}elseif($_GET['m'] == 'clean'){
    $page = '数据清除';
}elseif($_GET['m'] == 'addterm'){
    $page = '手动开奖';
}
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $console;
?>娱乐系统 | <?php echo $page;
?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="dist/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/js/app.min.js"></script>
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">
        <!-- Logo -->
        <a href="index.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><?php echo mb_substr($console, 0, 1, 'utf-8');
?></b><?php echo mb_substr($console, 1, 1, 'utf-8');
?></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><?php echo $console;
?></b>娱乐系统</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success fade sf">0</span>
            </a>
                <ul class="dropdown-menu">
                  <li class="header">你收到<span class="sf">3</span>条上分消息</li>
                  <li>
                    <!-- inner menu: contains the messages -->
                    <ul class="menu">
                      <li id="sfdata">

                      </li>
                    </ul>
                    <!-- /.menu -->
                  </li>
                  <li class="footer"><a href="index.php?m=manage&a=up">查看全部消息</a></li>
                </ul>
              </li>
              <!-- /.messages-menu -->

              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning fade xf">0</span>
            </a>
                <ul class="dropdown-menu">
                  <li class="header">你收到<span class="xf">3</span>条下分消息</li>
                  <li>
                    <!-- inner menu: contains the messages -->
                    <ul class="menu">
                      <li id="xfdata">

                      </li>
                      <!-- end message -->
                    </ul>
                    <!-- /.menu -->
                  </li>
                  <li class="footer"><a href="index.php?m=manage&a=down">查看全部消息</a></li>
                </ul>
              </li>
              <!-- /.messages-menu -->

              <li class="dropdown messages-menu">
                <!-- Menu toggle button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger fade pay">0</span>
            </a>
                <ul class="dropdown-menu">
                  <li class="header">你收到<span class="pay">0</span>条充值消息</li>
                  <li>
                    <!-- inner menu: contains the messages -->
                    <ul class="menu">
                      <li id="paydata">

                      </li>
                      <!-- end message -->
                    </ul>
                    <!-- /.menu -->
                  </li>
                  <li class="footer"><a href="#">查看全部消息</a></li>
                </ul>
              </li>
              <!-- /.messages-menu -->

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?php echo $_SESSION['agent_user'];
?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                    <p>
                      <?php echo $_SESSION['agent_user'];
?> -
                        <?php echo get_query_val("fn_room", "version", array("roomid" => $_SESSION['agent_room']));
?>
                          <small>到期时间: <?php echo get_query_val("fn_room", "roomtime", array("roomid" => $_SESSION['agent_room']));
?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="row">
                      <div class="col-xs-6 text-center">
                        <a href="#">使用教程</a>
                      </div>
                      <div class="col-xs-6 text-center">
                        <a href="#">合作伙伴</a>
                      </div>
                    </div>
                    <!-- /.row -->
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <?php if($version != '尊享版'){
    ?>
                      <div class="pull-left">
                        <a href="#" class="btn btn-default btn-flat">升级版本</a>
                      </div>
                      <?php }
?>
                        <div class="pull-right">
                          <a href="javascript:logout();" class="btn btn-default btn-flat">安全退出</a>
                        </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <!--右侧面板按钮 li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li-->
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- Sidebar user panel (optional) -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>
                <?php echo $_SESSION['agent_user'];
?>
              </p>
              <!-- Status -->
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>

          <!-- search form (Optional) -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
          </form>
          <!-- /.search form -->

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">导航</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="<?php if($_GET['m'] == "")echo 'active';
?>"><a href="index.php"><i class="fa fa-dashboard"></i> <span>控制台</span></a></li>
            <li class="<?php if($_GET['m'] == "setting")echo 'active';
?>"><a href="index.php?m=setting"><i class="fa fa-cogs"></i> <span>系统设置</span></a></li>
            <li class="treeview <?php if($_GET['m'] == "g_setting")echo 'active';
?>">
              <a href="#"><i class="fa fa-gamepad"></i> <span>游戏设置</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['g'] == "pk10")echo 'active';
?>"><a href="index.php?m=g_setting&g=pk10"><i class="fa fa-circle-o"></i>北京赛车</a></li>
                <li class="<?php if($_GET['g'] == "xyft")echo 'active';
?>"><a href="index.php?m=g_setting&g=xyft"><i class="fa fa-circle-o"></i>幸运飞艇</a></li>
                <li class="<?php if($_GET['g'] == "cqssc")echo 'active';
?>"><a href="index.php?m=g_setting&g=cqssc"><i class="fa fa-circle-o"></i>重庆时时彩</a></li>
				<li class="<?php if($_GET['g'] == "xy28")echo 'active';
?>"><a href="index.php?m=g_setting&g=xy28"><i class="fa fa-circle-o"></i>幸运28</a></li>
                <li class="<?php if($_GET['g'] == "jnd28")echo 'active';
?>"><a href="index.php?m=g_setting&g=jnd28"><i class="fa fa-circle-o"></i>加拿大28</a></li>
                <!--<li class="<?php if($_GET['g'] == "bjl")echo 'active';
?>"><a href="index.php?m=g_setting&g=bjl"><i class="fa fa-circle-o"></i>真人百家乐</a></li>-->
                <li class="<?php if($_GET['g'] == "jsmt")echo 'active';
?>"><a href="index.php?m=g_setting&g=jsmt"><i class="fa fa-circle-o"></i>极速摩托</a></li>
                <li class="<?php if($_GET['g'] == "jssc")echo 'active';
?>"><a href="index.php?m=g_setting&g=jssc"><i class="fa fa-circle-o"></i>极速赛车</a></li>
                <li class="<?php if($_GET['g'] == "jsssc")echo 'active';
?>"><a href="index.php?m=g_setting&g=jsssc"><i class="fa fa-circle-o"></i>极速时时彩</a></li>
				<li class="<?php if($_GET['g'] == "kuai3")echo 'active';
?>"><a href="index.php?m=g_setting&g=kuai3"><i class="fa fa-circle-o"></i>江苏快3</a></li>
              </ul>
            </li>
            <li class="<?php if($_GET['m'] == "user")echo 'active';
?>"><a href="index.php?m=user"><i class="fa fa-group"></i><span>用户管理</span></a></li>
            <li class="<?php if($_GET['m'] == 'termcount')echo 'active';
?>"><a href="index.php?m=termcount"><i class="glyphicon glyphicon-list"></i><span>当期统计</span></a></li>
            <li class="<?php if($_GET['m'] == 'ban')echo 'active';
?>"><a href="index.php?m=ban"><i class="glyphicon glyphicon-warning-sign"></i><span>禁言管理</span></a></li>
            <li class="treeview <?php if($_GET['m'] == "report")echo 'active';
?>">
              <a href="#"><i class="fa fa-list-ol"></i> <span>分数报表</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['r'] == "up")echo 'active';
?>"><a href="index.php?m=report&r=up"><i class="fa fa-circle-o"></i>上下分报表</a></li>
                <li class="<?php if($_GET['r'] == "term")echo 'active';
?>"><a href="index.php?m=report&r=term"><i class="fa fa-circle-o"></i>期期报表</a></li>
                <li class="<?php if($_GET['r'] == "none")echo 'active';
?>"><a href="index.php?m=report&r=none"><i class="fa fa-circle-o"></i>未结算报表</a></li>
              </ul>
            </li>
            <li class="treeview <?php if($_GET['m'] == "manage")echo 'active';
?>">
              <a href="#"><i class="fa fa-database"></i> <span>分数管理</span>
            <span class="pull-right-container">
              <span class="label pull-right bg-red fade sf">3</span>
              <span class="label pull-right bg-blue fade xf">3</span>
              <span class="label pull-right bg-green fade pay">3</span>
            </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['a'] == "up")echo 'active';
?>"><a href="index.php?m=manage&a=up"><i class="fa fa-circle-o"></i>上分管理 <span class="label pull-right bg-red fade sf">3</span></a></li>
                <li class="<?php if($_GET['a'] == "down")echo 'active';
?>"><a href="index.php?m=manage&a=down"><i class="fa fa-circle-o"></i>下分管理 <span class="label pull-right bg-blue fade xf" >3</span></a></li>
                <!--<li class="<?php if($_GET['a'] == "pay")echo 'active';
?>"><a href="index.php?m=manage&a=pay"><i class="fa fa-circle-o"></i>充值管理 <span class="label pull-right bg-green fade pay">3</span></a></li>-->
              </ul>
            </li>
            <li class="<?php if($_GET['m'] == "tui")echo 'active';
?>"><a href="index.php?m=tui"><i class="fa fa-cloud-download"></i> <span>退水设置</span></a></li>
            <li class="treeview <?php if($_GET['m'] == 'chat')echo 'active';
?>">
              <a href="#"><i class="fa fa-comments-o"></i> <span>聊天管理</span>
            <span class="pull-right-container msgdown">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            <span class="label pull-right bg-purple fade msg">3</span>
          </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['c'] == 'room')echo 'active';
?>"><a href="index.php?m=chat&c=room"><i class="fa fa-circle-o"></i>房间聊天</a></li>
                <li class="<?php if($_GET['c'] == 'custom')echo 'active';
?>"><a href="index.php?m=chat&c=custom"><i class="fa fa-circle-o"></i>客服管理<span class="label pull-right bg-purple fade msg">3</span></a></li>
              </ul>
            </li>
            <li class="treeview <?php if($_GET['m'] == 'robot')echo 'active';
?>">
              <a href="#"><i class="fa fa-commenting-o"></i> <span>自动拖管理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['r'] == 'plan')echo 'active';
?>"><a href="index.php?m=robot&r=plan"><i class="fa fa-circle-o"></i>方案管理</a></li>
                <li class="<?php if($_GET['r'] == 'robots')echo 'active';
?>"><a href="index.php?m=robot&r=robots"><i class="fa fa-circle-o"></i>机器人管理</a></li>
              </ul>
            </li>
            <li class="<?php if($_GET['m'] == 'extend')echo 'active';
?>"><a href="index.php?m=extend"><i class="fa fa-user-plus"></i> <span>代理系统</span></a></li>
            <li class="<?php if($_GET['m'] == 'share')echo 'active';
?>"><a href="index.php?m=share"><i class="fa fa-share-square-o"></i> <span>分享房间</span></a></li>
            <li class="treeview <?php if($_GET['m'] == 'flyorder')echo 'active';
?>">
              <a href="#"><i class="fa fa-paper-plane"></i> <span>飞单系统</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if($_GET['f'] == "fly")echo 'active';
?>"><a href="index.php?m=flyorder&f=fly"><i class="fa fa-circle-o"></i>飞单设置</a></li>
                <li class="<?php if($_GET['f'] == "old")echo 'active';
?>"><a href="index.php?m=flyorder&f=old"><i class="fa fa-circle-o"></i>飞单历史</a></li>
              </ul>
            </li>
            <li class="<?php if($_GET['m'] == 'buy')echo 'active';
?>"><a href="index.php?m=buy"><i class="fa fa-shopping-cart"></i> <span>修改密码</span></a></li>
            <li class="<?php if($_GET['m'] == 'clean')echo 'active';
?>"><a href="index.php?m=clean"><i class="fa fa-trash"></i> <span>数据清理</span></a></li>
            <li class="<?php if($_GET['m'] == 'addterm')echo 'active';
?>"><a href="index.php?m=addterm"><i class="fa fa-cubes"></i> <span>手动开奖</span></a></li>
            <li class="header">About</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>KP智投系统</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Version 5.6.7</span></a></li>
            <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>再创奇迹</span></a></li>
          </ul>

          <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>
      <div id="callout" class="callout" style="position:fixed;width:350px;top:20px;z-index:9999999;right:10px;display:none">
          <h4 id="title">收到一笔上分消息</h4>
          <p id="cont">来自XXXX的上分消息</p>
      </div>
      <div id="flycallout" class="callout callout-info" style="position:fixed;width:350px;top:130px;z-index:9999999;right:10px;display:none">
          <h4>飞单成功</h4>
          <p>本期飞单指令已经提交</p>
      </div>
      <!-- Content Wrapper. Contains page content -->
      <?php
 if($_GET['m'] == ''){
    require 'templates/dashboard.html';
}elseif($_GET['m'] == 'setting'){
    require 'templates/roomsetting.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'pk10'){
    require 'templates/gamesetting/pk10.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'xyft'){
    require 'templates/gamesetting/xyft.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'cqssc'){
    require 'templates/gamesetting/cqssc.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'xy28'){
    require 'templates/gamesetting/xy28.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'jnd28'){
    require 'templates/gamesetting/jnd28.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'cqssc'){
    require 'templates/error.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'bjl'){
    require 'templates/error.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'jsmt'){
    require 'templates/gamesetting/jsmt.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'jssc'){
    require 'templates/gamesetting/jssc.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'jsssc'){
    require 'templates/gamesetting/jsssc.html';
}elseif($_GET['m'] == 'g_setting' && $_GET['g'] == 'kuai3'){
    require 'templates/gamesetting/kuai3.html';
}elseif($_GET['m'] == 'user'){
    require 'templates/user.html';
}elseif($_GET['m'] == 'userjia'){
    require 'templates/userjia.html';
}elseif($_GET['m'] == 'userdata'){
    require 'templates/userdata.html';
}elseif($_GET['m'] == 'termcount'){
    require 'templates/termcount.html';
}elseif($_GET['m'] == 'ban'){
    require 'templates/ban.html';
}elseif($_GET['m'] == 'report' && $_GET['r'] == 'up'){
    require 'templates/markreport/upmark.html';
}elseif($_GET['m'] == 'report' && $_GET['r'] == 'term'){
    require 'templates/markreport/termmark.html';
}elseif($_GET['m'] == 'report' && $_GET['r'] == 'none'){
    require 'templates/markreport/none.html';
}elseif($_GET['m'] == 'manage' && $_GET['a'] == 'up'){
    require 'templates/manage/up.html';
}elseif($_GET['m'] == 'manage' && $_GET['a'] == 'down'){
    require 'templates/manage/down.html';
}elseif($_GET['m'] == 'manage' && $_GET['a'] == 'pay'){
    require 'templates/error.html';
}elseif($_GET['m'] == 'tui'){
    require 'templates/tui.html';
}elseif($_GET['m'] == 'chat' && $_GET['c'] == 'room'){
    require 'templates/chat/room.html';
}elseif($_GET['m'] == 'chat' && $_GET['c'] == 'custom'){
    require 'templates/chat/custom.html';
}elseif($_GET['m'] == 'robot' && $_GET['r'] == 'plan'){
    require 'templates/robots/plan.html';
}elseif($_GET['m'] == 'robot' && $_GET['r'] == 'robots'){
    require 'templates/robots/robot.html';
}elseif($_GET['m'] == 'extend'){
    require 'templates/extend.html';
}elseif($_GET['m'] == 'share'){
    require 'templates/share.html';
}elseif($_GET['m'] == 'buy'){
    require 'templates/buy.html';
}elseif($_GET['m'] == 'flyorder' && $_GET['f'] == 'fly'){
    require 'templates/flyorder/fly.html';
}elseif($_GET['m'] == 'flyorder' && $_GET['f'] == 'old'){
    require 'templates/flyorder/old.html';
}elseif($_GET['m'] == 'clean'){
    require 'templates/clean.html';
}elseif($_GET['m'] == 'addterm'){
    require 'templates/addterm.html';
}else{
    require "templates/404.html";
}
?>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
          <!-- To the right -->
          <div class="pull-right hidden-xs">
            做你所想 想你所得
          </div>
          <!-- Default to the left -->
          <strong>Copyright &copy; 2017 <a href="#">KP娱乐版权所有</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
          <!-- Create the tabs -->
          <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
              <h3 class="control-sidebar-heading">Recent Activity</h3>
              <ul class="control-sidebar-menu">
                <li>
                  <a href="javascript:;">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
                </li>
              </ul>
              <!-- /.control-sidebar-menu -->

              <h3 class="control-sidebar-heading">Tasks Progress</h3>
              <ul class="control-sidebar-menu">
                <li>
                  <a href="javascript:;">
                    <h4 class="control-sidebar-subheading">
                      Custom Template Design
                      <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                      </span>
                    </h4>

                    <div class="progress progress-xxs">
                      <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                    </div>
                  </a>
                </li>
              </ul>
              <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
              <form method="post">
                <h3 class="control-sidebar-heading">General Settings</h3>

                <div class="form-group">
                  <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

                  <p>
                    Some information about this general settings option
                  </p>
                </div>
                <!-- /.form-group -->
              </form>
            </div>
            <!-- /.tab-pane -->
          </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
  </body>
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <script>
    parent.document.title = document.title;
    function logout(){
      $.get('Application/ajax_index.php',{t: 'logout'},function(data){
        if(data.success){
          alert('退出系统成功..');
          window.location.reload();
        }
      },'json');
    }
  </script>
  </html>