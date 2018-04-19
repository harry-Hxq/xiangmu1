<?php
include_once(dirname(dirname(dirname(preg_replace('@\(.*\(.*$@', '', __FILE__)))) . "/Public/config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Demo</title>
  <link rel="stylesheet" href="/Style/New/css/idangerous.swiper.css">
  <style>
    /* Demo Styles */

    html {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 36px;
      line-height: 1.5;
      position: relative;
      height: 100%;
    }

    .swiper-container {
      width: 100%;
      height: 100%;
      color: #fff;
      text-align: center;
    }

    .red-slide {
      background: #ca4040;
      text-align: center;
      font-size: 36px;
    }

    .blue-slide {
      background: #4390ee;
    }

    .orange-slide {
      background: #ff8604;
    }

    .green-slide {
      background: #49a430;
    }

    .pink-slide {
      background: #973e76;
    }

    .swiper-slide .title {
      font-style: italic;
      font-size: 66px;
      margin-top: 66px;
      margin-bottom: 0;
      line-height: 45px;
    }

    .pagination {
      position: absolute;
      z-index: 20;
      left: 10px;
      bottom: 10px;
    }

    .swiper-pagination-switch {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 8px;
      background: #222;
      margin-right: 5px;
      opacity: 0.8;
      border: 1px solid #fff;
      cursor: pointer;
    }

    .swiper-visible-switch {
      background: #aaa;
    }

    .swiper-active-switch {
      background: #fff;
    }
  </style>
</head>
<?php $sql = get_query_val('fn_setting', 'setting_rules', array('roomid' => $_SESSION['roomid']));
$sql = explode('|', $sql);
?>
<body>
  <div class="swiper-container">
    <div class="swiper-wrapper">
      <div class="swiper-slide red-slide">
        <div class="title"><?php echo $sql[0];
?></div>
        <?php echo $sql[1];
?>
      </div>
      <div class="swiper-slide blue-slide" <?php if($sql[2] == '' && $sql[3] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[2];
?></div>
        <?php echo $sql[3];
?>
      </div>
      <div class="swiper-slide orange-slide" <?php if($sql[4] == '' && $sql[5] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[4];
?></div>
        <?php echo $sql[5];
?>
      </div>
      <div class="swiper-slide green-slide" <?php if($sql[6] == '' && $sql[7] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[6];
?></div>
        <?php echo $sql[7];
?>
      </div>
      <div class="swiper-slide pink-slide" <?php if($sql[8] == '' && $sql[9] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[8];
?></div>
        <?php echo $sql[9];
?>
      </div>
      <div class="swiper-slide red-slide" <?php if($sql[10] == '' && $sql[11] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[10];
?></div>
        <?php echo $sql[11];
?>
      </div>
      <div class="swiper-slide blue-slide" <?php if($sql[12] == '' && $sql[13] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[12];
?></div>
        <?php echo $sql[13];
?>
      </div>
      <div class="swiper-slide orange-slide" <?php if($sql[14] == '' && $sql[15] == '')echo 'style="display:none"'?>>
        <div class="title"><?php echo $sql[14];
?></div>
        <?php echo $sql[15];
?>
      </div>
    </div>
    <div class="pagination"></div>
  </div>
  <script src="/Style/New/js/jquery.min.js"></script>
  <script src="/Style/New/js/idangerous.swiper-2.1.min.js"></script>
  <script>
    var mySwiper = new Swiper('.swiper-container', {
      pagination: '.pagination',
      paginationClickable: true
    })
  </script>
</body>

</html>