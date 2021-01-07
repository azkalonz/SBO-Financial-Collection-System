<?php
  require("../config/config.php");
  require("../api/func.php");
  if(isset($_SESSION['account'])){
    header('location: /');
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Login</title>
    <script type="text/javascript" src="../js/mkj-api.js"></script>
    <?php include('../js/jquery.php') ?>
    <?php include('../css/styles.php') ?>
    <style media="screen">
      table {background: #fff;}
      #teacher-login-form table tr td.table-title{color: #9f3cb1!important;}
      #student-login-form table tr td.table-title{color: #06ace3!important;}
      .login-container {border: 2px solid #06ace3;}
      form {width: 100%;}
      table tr td, table tr th {border:none!important;}
    </style>
  </head>
  <body>
  <?php include("../core/header.php") ?>
  <div class="fake-body">
  <div class="wrap">
     <div id="choices" style="display: none;">
     </div>
    <div class="login-container">
      <div id="login-form" class="flex align-center justify-center">
        <div id="particles-js" style="position:absolute;left:0;top:0;width: 100%;height: 100%;z-index:-1">
          <button onclick="$('.login-trigger').click()" style="border-radius: 0;background: #000;">
              <i class="fa fa-exchange-alt"></i>
           </button>
         </div>
        <form id="student-login-form" onsubmit="return false;">
          <table>
          <tr>
            <td colspan="100%">
              <span class="alert sm" style="display: <?php echo isset($_GET['sec'])?'block':'none' ?>;">
                <h5 id="logged-in-message">
                  <?php if (isset($_GET['sec'])): ?>
                          <b>Please login to continue.</b>
                  <?php endif; ?>
                </h5>
              </span>
            </td>
          </tr>
          <tr>
            <td class="table-title fade" colspan="100%">Student Login</td>
          </tr>
          <tr>
            <td>STUDENT ID</td>
            <td>
              <input type="text" name="student_id" value="" autocomplete="off">
            </td>
          </tr>
          <tr>
            <td>PASSWORD</td>
            <td>
              <input type="password" name="student_password" value="">
            </td>
          </tr>
          <tr>
            <td></td>
            <td colspan="100%">
              <div class="flex justify-space-between align-center">
                <a href="#">Forgot password</a>
                <button type="button" name="button" id="login_student">Login</button>
              </div>
            </td>
          </tr>
        </table>
        </form>
        <form id="teacher-login-form" style="display: none;" onsubmit="return false;">
          <table>
            <tr>
              <td colspan="100%">
                <span class="alert sm" style="display: none;">
                  <h5 id="teacher-logged-in-message"></h5>
                </span>
              </td>
            </tr>
            <tr>
              <td class="table-title fade" colspan="100%">Teacher/officer Login</td>
            </tr>
            <tr>
              <td>USERNAME</td>
              <td>
                <input type="text" name="officer_id" value="" autocomplete="off">
              </td>
            </tr>
            <tr>
              <td>PASSWORD</td>
              <td>
                <input type="password" name="password" value="">
              </td>
            </tr>
            <td colspan="100%" align="right">
              <!-- <div class="checkbox-container">
                <input type="checkbox" name="remember" id="remember2" value="">
                <span class="checkbox-mask"></span>
              </div>
              <label for="remember2">Remember Password</label> -->
              <button type="button" name="button" id="login_teacher" onclick="$('.error').show()">Login</button>
            </td>
            </tr>
          </table>
        </form>
      </div>
      <div id="login-trigger-container" class>
      </div>
      <div id="student-login">
        <div class="flex wrap align-center justify-center flex-wrap fade" style="height: inherit;">
          <div class="col-100">
            <h2 class="slide-left">
              Welcome back Officer/Teacher!
            </h2>
          </div>
          <div class="col-100">
            I'm a Student<br><br>
            <a class="login-trigger">STUDENT LOGIN</a>
          </div>
        </div>
      </div>
      <div id="teacher-login" >
        <div class="flex wrap align-center justify-center flex-wrap fade" style="height: inherit;">
          <div class="col-100">
            <h2 class="slide-right">
              Welcome back Student!
            </h2>
          </div>
          <div class="col-100">
            I'm a Teacher/Officer<br><br>
            <a class="login-trigger">OFFICER LOGIN</a>
          </div>
          <!-- <div id="particles-js2" style="position:absolute;left:0;top:0;width: 100%;height: 100%;z-index:-1"></div> -->
        </div>
      </div>
      <div id="menu" style="display: none;box-shadow: 0 5px 7px rgba(0,0,0,0.1);white-space:nowrap;background: #222;border:1px solid #ededed;padding: 10px;border-radius: 7px; position: absolute;z-index: 9999;">
        <a href="https://www.facebook.com/average.g0at" target="_blank">
          &copy; MK.J 2019
        </a>
      </div>
    </div>
    <div style="display:none">
          <?php include('../core/sidebar.php')?>

    </div>
  </div>
  </div>
  <?php include('../core/footer.php')?>
  <script type="text/javascript">
  $('input[type=text],input[type=password]').on('keyup',()=>{
    if(event.which==13)
    $('button:contains("Login")').click();
  });

  $("#menu-links a").each(function(){
    if($(this).attr("href")==window.location.pathname)
    $(this).addClass("selected");
  })
      checkerror = function() {
        $(".error").show();
        $(".alert").show();
        $(".success").show();
      }
      $("#login_student").click(function() {
        $data = {"action":"student-login"};
        <?php if(isset($_GET['ref'])): ?>
        $data.ref = '<?php echo $_GET['ref'] ?>';
        <?php endif; ?>
        $("#student-login-form input").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $("#student-login-form select").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $data["remember"] = $("#remember").prop("checked")==true?1:0;
        $action($data,"#logged-in-message","");
        checkerror();
      })

      $("#login_teacher").click(function() {
        $data = {"action":"teacher-login"};
        <?php if(isset($_GET['ref'])): ?>
        $data.ref = '<?php echo $_GET['ref'] ?>';
        <?php endif; ?>
        $("#teacher-login-form input").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $("#teacher-login-form select").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $data["remember"] = $("#remember2").prop("checked")==true?1:0;
        $action($data,"#teacher-logged-in-message","");
      })
  </script>
  <script type="text/javascript" src="login-script.js"></script>
  <script type="text/javascript" src="../js/particles.min.js"></script>
  <script type="text/javascript" src="../js/constellation.js"></script>
  <?php include('../js/common.php');?>
  </body>
  </html>
