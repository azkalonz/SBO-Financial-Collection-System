
<?php
  require "config/config.php";
  require "api/func.php";
  $max_items = 5;
  $page = isset($_GET['page'])?$_GET['page']-1:0;
  $page *= $max_items;
  $total_items = $con->prepare("SELECT count(id) FROM psits_events");
  $total_items->execute();
  $total_items = $total_items->fetch()[0];
  $total_page = ceil($total_items/$max_items);
  $events = $con->prepare("SELECT * FROM psits_events ORDER BY id DESC LIMIT $page,$max_items");
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);
  if (isset($_SESSION["account"]) && $_SESSION['account']['access']==0) {
    $stat = $con->prepare("SELECT change_pass FROM ccs_students WHERE student_id = {$_SESSION['account']['student_id']}");
    $stat->execute();
    $stat = $stat->fetch()[0];
  } else {
    $stat = 0;
  }
  if (isset($_GET['confirm-token'])) {
    $query = $con->prepare("UPDATE subscribers SET active = 1 WHERE code = '{$_GET['confirm-token']}' ");
    if($query->execute()){
      $real = $con->prepare("SELECT * FROM subscribers WHERE code = '{$_GET['confirm-token']}'");
      $real->execute();
      if($real->fetch()>0){
        $confirmed = true;
      } else {
        $confirmed = false;
      }
    } else {
      $confirmed = false;
    }
  } else if(isset($_GET['unsub-token'])){
    $query = $con->prepare("UPDATE subscribers SET active = 0 WHERE code = '{$_GET['unsub-token']}' ");
    if($query->execute()){
      $confirmed = true;
    } else {
      $confirmed = false;
    }
  }
  ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Home</title>
    <script type="text/javascript" src="js/mkj-api.js"></script>
    <?php include('js/jquery.php') ?>
    <?php include('./css/styles.php') ?>
    <script type="text/javascript" src="js/progressbar.js"></script>
    <style media="screen">
      .compose-comment form table tr td {
        background: none!important;}
      /* #logo-title {
        position: relative;
        margin-left:40px;
      }
      #logo-title::before,#logo-title::after {
        position: absolute;
        color:#fff;
        font-size: 3.7rem;
        height:100%;
        top: -10px;
      }
      #logo-title::after {
        content: '}';
        right:-30px;
      }
      #logo-title::before {
        content: '{';
        left:-30px;
      } */
    </style>
  </head>
  <body>
  <?php include("core/header.php") ?>
  <div class="fake-body">
  <div class="wrap flex" id="container">
    <div class="flex flex-wrap align-start col-100">

      <?php include './raffle/index.php' ?>

    </div>


      <?php include('core/sidebar.php') ?>

  </div>
</div>
    <?php include('core/footer.php');?>
  <script type="text/javascript" src="/js/comment.js"></script>
  <script type="text/javascript">
    $("#menu-links a").each(function(){
      if($(this).attr("href")+"/"==window.location.pathname)
      $(this).addClass("selected");
    })
  </script>
  <?php include('js/common.php');?>
  </body>
</html>
