
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
      <?php if (isset($_SESSION["account"]) && $stat): ?>
        <span class="alert sm">
          <h3>Change your Password!</h3>
          <p>It is adviced that you update your password, using your default is strongly
            not recommended <a href="./account/index.php" style="white-space: nowrap;">click here</a> to update your password</p>
        </span>
      <?php endif; ?>
      <?php   if(isset($_SESSION['newsletter-age']) && isset($_GET['expire'])):?>
          <?php if ($_GET['expire']=='newsletter'): ?>
            <?php unset($_SESSION['newsletter-age']); ?>
            <span class="error sm">
              <p>Session expired</p>
            </span>
          <?php endif; ?>
        <?php endif; ?>

      <?php if (isset($_GET['confirm-token'])): ?>
        <?php if (!$confirmed): ?>
          <span class="alert sm">
            <p>The link you followed may have expired.</p>
          </span>
        <?php endif; ?>
        <?php if ($confirmed): ?>
          <span class="success sm">
            <p>You are now getting emails from us in the future. If you wish to end your subscription click <a href="/?unsub-token=<?php echo $_GET['confirm-token'] ?>">here</a>.</p>
          </span>
        <?php endif; ?>
        <?php else: ?>
          <?php if (isset($_GET['unsub-token'])): ?>
            <?php if ($confirmed): ?>
              <span class="success sm">
                <p>You ended your newsletter subscription.</p>
              </span>
            <?php endif; ?>
          <?php endif; ?>
      <?php endif; ?>

        <?php foreach ($events as $key => $value): ?>
          <div class="event-container">
          <?php
          $date = explode('-',date('d-M-l',strtotime($value['date'])));
          $day = $date[0];
          $month = $date[1];
          $week = $date[2];
          $time = date("g:i A", strtotime($value['startTime']));
           ?>

          <div class="col-100 event-info flex align-start justify-space-between">

            <div class="calendar flex flex-wrap text-right">
              <div class="col-100 day" style="background-color:  <?php echo $value['theme_color']?>">
                <b class="d2" style="color: <?php echo $value['theme_color'];?>"><?php echo $month ?></b>
                <b class="d"><?php echo $day ?></b>
                <span class="d2" style="background-color:  <?php echo $value['theme_color']?>;padding: 0 10px;border-radius: 4px;"><b><?php echo $day ?></b></span>
              </div>
              <div class="col-100 month d" style="color: <?php echo $value['theme_color'];?>">
                <b><?php echo $month ?></b>
              </div>
            </div>

            <div class="details flex flex-wrap">
              <div class="col-100">
                <h1><?php echo $value['name'] ?></h1>
              </div>
              <div class="col-100">
                <a style="color: <?php echo $value['theme_color'];?>">
                  <i class="fa fa-calendar"></i>
                  <?php echo $week ?>
                </a>&nbsp;&nbsp;
                <a style="color: <?php echo $value['theme_color'];?>">
                  <i class="fa fa-clock"></i>
                  <?php echo $time ?>
                </a>
              </div>
              <div class="col-100">
                <br>
                <?php echo $value['_desc']; ?><br><br>
                <a href="/event/?id=<?php echo $value['id'] ?>" class="button" style="background-color: <?php echo $value['theme_color'];?>;">
                  <i class="fal fa-info-circle"></i>
                  details
                  </a>

              </div>

            </div>

          </div>

          <?php
          $cid = $value['id'];
          include 'module/comment.php'; ?>

        </div>
        <?php endforeach; ?>

    <div class="pag" style="padding: 10px;">
      <?php for($i=1;$i<=$total_page;$i++): ?>
          <a href="/?page=<?php echo $i ?>" <?php echo isset($_GET['page'])?$_GET['page']==$i?'style="font-size:1.5em;"':'':'' ?>>
            <?php echo $i ?>
          </a>&nbsp;&nbsp;&nbsp;
      <?php endfor; ?><br>
      Page <?php echo $page+1 ?> of <?php echo $total_page ?>
    </div>

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
