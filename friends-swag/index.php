
<?php
  require "../config/config.php";
  require "../api/func.php";
  secure();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Intramurals 2019</title>
    <?php include('../js/jquery.php') ?>
    <?php include('../css/styles.php') ?>
    <link rel="stylesheet" href="./style.css">
    <script type="text/javascript">
    function resizeIframe(obj) {
      obj.style.height = (obj.contentWindow.document.body.scrollHeight+20) + 'px';
      document.getElementById("d").contentWindow.document.body.onclick = function() {
        resizeIframe(document.getElementById('d'))
      }
    }
    </script>
  </head>
  <body>
  <?php include("../core/header.php") ?>

  <div class="fake-body" style="color:#fff;position:relative;padding: 20px 100px;padding-bottom:0;">
    <button onclick="window.location='/friends-swag/friends_swag_design_contest_release_form.docx'"><h2>Download Release Form</h2></button>
    <iframe src="swag.htm" id="d" width="100%" style="padding:0;margin:0;border:none;" onload="resizeIframe(this)"></iframe>
  </div>
  <?php include('../core/footer.php');?>
  <script type="text/javascript" src="/js/comment.js"></script>
  <?php include('../js/common.php');?>
  </body>
</html>
