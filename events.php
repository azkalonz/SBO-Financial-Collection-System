<?php   require("config/config.php");
  require("api/func.php"); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Events</title>
    <script type="text/javascript" src="js/mkj-api.js"></script>
    <?php include('js/jquery.php') ?>
    <?php include('./css/styles.php') ?>
    <script type="text/javascript" src="js/progressbar.js"></script>
    <style media="screen">
      .compose-comment form table tr td {
        background: none!important;}
    </style>
    <script>
      function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        document.getElementById("d").contentWindow.document.body.onclick = function() {
          resizeIframe(document.getElementById('d'))
        }
      }
    </script>
  </head>
  <body>
  <?php include("core/header.php") ?>
  <div class="fake-body">
    <div class="wrap flex" id="container">
      <iframe id="d" src="/calendar" width="100%" style="border: none;" onload="resizeIframe(this)"></iframe>
      <?php include('core/sidebar.php') ?>
    </div>
  </div>
  <?php include('core/footer.php');?>
  <?php include('js/common.php');?>
  <script type="text/javascript">
    $("#menu-links a").each(function(){
      if($(this).text().trim()=='Events')
      $(this).addClass("selected");
    })

  </script>
  </body>
</html>
