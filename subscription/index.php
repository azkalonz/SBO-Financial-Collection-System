<?php
require "../config/config.php";
require "../api/func.php";
$invalid = false;
$code = md5(uniqid(rand(), true));
$sent = 0;
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  $invalid = true;
}
if(!isset($_SESSION['newsletter-age'])){
  $_SESSION['newsletter-age'] = time();
} else if(time()-$_SESSION['newsletter-age']>=120){
  header('location: /?expire=newsletter');
}

if(!$invalid){
  $real = $con->prepare("SELECT * FROM subscribers WHERE email = '{$_POST['email']}'");
  if($real->execute()){
    if ($real->fetch()==0) {
      $query = $con->prepare("INSERT INTO subscribers SET email = '{$_POST['email']}', active = 0, code = '{$code}'");
      $query->execute();
    } else {
      $query = $con->prepare("UPDATE subscribers SET code = '{$code}' WHERE email = '{$_POST['email']}'");
      $query->execute();
    }
    $sent = true;
  }
}

$ref =  isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/';
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Subscription</title>
    <script type="text/javascript" src="../js/mkj-api.js"></script>
    <?php include('../js/jquery.php') ?>
    <?php include('../css/styles.php') ?>
  </head>
  <body>
  <?php include("../core/header.php") ?>
  <div class="fake-body" style="position: relative;">
  <div class="wrap flex" id="container">
    <div class="flex flex-wrap align-start col-100">
      <div class="w col-100" style="min-height: 70vh;padding: 40px;">
        <a href="<?php echo $ref ?>">
          <i class="fa fa-long-arrow-left"></i>
          Go back
        </a>
        <br>
        <br>
        <div id="stat">
          <?php if (!$invalid && $sent): ?>
            <div style="position: absolute;top:0;right:0;background: url('/img/email.png') no-repeat;width: 300px;height: 85px;
            background-size: 100%;padding: 0 50px;line-height: 95px;font-weight:bold;color: #fff;z-index: 2;">
            Check your email
            </div>

            <h1>Hey there!</h1>
            <p>
              Please confirm your email address by opening the confirmation link that we sent an email to <b><?php echo $_POST['email'] ?></b>. If you did not get the message you may click the resend button below or check your spam folder.
            </p>
            <br>
            <button id="resend" type="button" name="button" class="round">Resend</button>
            <?php else: ?>
              <span class="error sm col-100">
                <p>Invalid email address</p>
              </span>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>
  <?php include('../core/footer.php');?>
  <script type="text/javascript">
    data = {
      action: 'newsletter',
      title: 'PSITS newsletter email confirmation',
      message: `
      <div style="padding: 40px; border: 1px solid #b5b5b5; border-radius: 6px;">
        <h2 style="margin:0;">Hey there!</h2>
        <p>Please click the following link to confirm that <b><?php echo $_POST['email'] ?></b> is your email address where you will receive newsletters.</p>
        <a style="display: inline-block; color: #fff; text-decoration:none; padding: 6px 23px; border-radius: 20px; background: #f5741b;" href="<?php echo $webhost ?>?confirm-token=<?php echo $code ?>">Confirm</a>
      </div>

      `,
      email: '<?php echo $_POST['email'] ?>'
    }
    $('#resend').on('click',function(){
      that = $(this);
      $.ajax({
        method: 'POST',
        data: data,
        url: '/mail.php',
        beforeSend: function(){
          that
          .css('width',(that.width()+70)+'px')
          .css('transition','width 0.7s ease-in-out, background 0.6s ease-out')
          .css('width','47px')
          .css('background','#7ac142')
          that
          .html(`<img src="/dashboard/img/balls.svg" width="16px"/>`)
        },
        success: function(data){
          console.log(data);
          that
          .html(`<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg>`)
        }
      })
      $(this).off();
    })
    <?php if($sent): ?>
        $.ajax({
          method: 'POST',
          data: data,
          url: '/mail.php',
          success: function(data){
            e = JSON.parse(data)
            if(e.error){
              $('#stat').html(`<span class="error sm">Oops! something went wrong. Please try again later.`);
            }
          }
        })
    <?php endif; ?>
  </script>
  <?php include('../js/common.php');?>
  </body>
</html>
