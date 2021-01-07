<?php
  require("../config/config.php");
  require("../api/func.php");
  secure();
  if($_SESSION['account']['access']==0){
  $id = $_SESSION['account']['student_id'];
  $fname = $_SESSION['account']['first_name'];
  $lname = $_SESSION['account']['last_name'];
  $name = $_SESSION['account']['full_name'];
  $username = $_SESSION['account']['username'];
  $course = $_SESSION['account']['course'];
  $pass = $_SESSION['account']['password'];
  $year = $_SESSION['account']['_year'];
  $email = $_SESSION['account']['email'];
} else {
  $id = $_SESSION['account']['officer_id'];
  $fname = substr($_SESSION['account']['full_name'],0,strpos($_SESSION['account']['full_name']," "));
  $lname = substr($_SESSION['account']['full_name'],strpos($_SESSION['account']['full_name']," "));
  $_SESSION['account']['last_name'] = $lname;
  $name = $_SESSION['account']['full_name'];
  $username = $_SESSION['account']['username'];
  $course = $_SESSION['account']['position'];
  $pass = $_SESSION['account']['password'];
  $year = "N/A";
  $email = "N/A";
}

  $collection = $con->prepare("SELECT * FROM psits_collections");
  $collection->execute();
  $collection = $collection->fetchAll(PDO::FETCH_ASSOC);
  $total = 0;
  $totalAll = 0;

  $events = $con->prepare("SELECT * FROM psits_events");
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);

  ksort($events);

  if(isset($_GET['confirm']) && isset($_SESSION['account']['student_id'])){
    $confirm_id = $_GET['id'];
    $confirmquery = $con->prepare("SELECT * FROM psits_collections WHERE id = {$confirm_id} AND student_id = {$_SESSION['account']['student_id']}");
    $confirmquery->execute();
    $payment_info = $confirmquery->fetch();
    $confirmation = $payment_info>0?true:false;
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $webtitle?> - Payment</title>
    <script type="text/javascript" src="../js/mkj-api.js"></script>
    <?php include('../js/jquery.php') ?>
    <script type="text/javascript" src="../js/progressbar.js"></script>
    <?php include('../css/styles.php') ?>
  </head>
  <body>
  <?php include("../core/header.php") ?>
  <div class="fake-body">
  <div class="wrap flex" id="container">
    <div class="acc-mnger flex flex-wrap align-start col-100" style="padding: 10px;">
      <?php if (isset($confirmation)): ?>
        <?php if ($confirmation): ?>
          <span class="success sm">
            <h4>
              Your payment was confirmed successfully.
            </h4>
          </span>
        <?php else: ?>
          <span class="error sm">
            <h4>
              Sorry, we couldn't confirm your payment.
            </h4>
          </span>
        <?php endif; ?>
      <?php endif; ?>
      <div class="col-100" style="margin: 0;">
        <div class="user-info flex flex-wrap">
          <div class="col-50">
            <header class="col-100">
              <h3>Personal Info</h3>
            </header>
            <main class="personal-info flex flex-wrap col-100">
              <?php if($_SESSION['account']['access']==0): ?>
                <div class="flex col-100">
                  <h4>First Name</h4>
                  <a><?php echo $fname ?></a>
                </div>
                <div class="flex col-100">
                  <h4>Last Name</h4>
                  <a><?php echo $lname ?></a>
                </div>
                <?php else: ?>
                  <div class="flex col-100">
                    <h4>Full Name</h4>
                    <a><?php echo $_SESSION['account']['full_name'] ?></a>
                  </div>
                <?php endif; ?>
              <div class="flex col-100">
                <h4>Email</h4>
                <a><?php echo $email ?></a>
              </div>
            </main>
          </div>
          <div class="col-50">
            <header class="col-100">
              <h3>Student Info</h3>
            </header>
            <main class="student-info flex flex-wrap col-100">
              <div class="flex col-100">
                <h4><?php echo $_SESSION['account']['access']==40?"Officer ID":"Student ID"; ?></h4>
                <a><?php echo $id ?></a>
              </div>
              <div class="flex col-100">
                <h4><?php echo $_SESSION['account']['access']==40?"Position":"Course"; ?></h4>
                <a><?php echo $course ?></a>
              </div>
              <div class="flex col-100">
                <h4>Year lever</h4>
                <a><?php echo $year ?></a>
              </div>
            </main>
          </div>
        </div>
      </div>

      <div class="col-100">
        <div class="flex flex-wrap col-100">
          <header class="col-100 flex justify-space-between align-center">
            <h3>Payment Info</h3>
            <div class="">
              <a href="#" onclick="PrintElem('paymentInfo')">
                <i class="fal fa-print"></i>
                Print</a>&nbsp;&nbsp;
            </div>
          </header>
          <div class="col-100" style="overflow-x: auto;" id="paymentInfo">
            <table>
              <thead>
                <tr>
                  <td class="table-title" colspan="100%" align="right" style="background: #1981b9!important;">Summary</td>
                </tr>
                <tr>
                  <td>Event</td>
                  <td>Payment date</td>
                  <td>Received by</td>
                  <td>Status</td>
                  <td>Amount paid</td>
                  <td>Payment amount</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($events as $key => $value): ?>
                  <?php $payment_data = getPaymentData($value['id'],$id); ?>
                  <tr>
                    <td><?php echo $value['name'] ?>
                      <?php if ($value['id']==3): ?>
                          <i>
                            <small>
                              <?php echo getTshirtSize(getTshirtPaid($id)); ?>
                            </small>
                          </i>
                      <?php endif; ?>
                    </td>
                    <td><?php echo $payment_data['_date'] ?></td>
                    <td><?php echo $payment_data['encoded_by'] ?></td>
                    <td><?php echo $payment_data['status']=="Paid"?"Paid":"Unpaid" ?></td>
                    <td>
                      <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                      <?php $payment = $payment_data['payment'];
                      echo sprintf("%.2f",$payment);  ?>
                    </td>
                    <td>
                      <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                      <?php if ($value['id']!=3): ?>
                        <?php echo $payment_data['payment']>0?0:$value['price']*.25;  ?>
                        <?php else: ?>
                          <?php echo getTshirtPaid($id)>0?0:getTshirtPaid($id) ?>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php $totalAll+=$payment?>
                  <?php if ($value['id']!=3): ?>
                    <?php $total+=$payment_data['payment']>0?0:$value['price']*.25; ?>
                  <?php else: ?>
                    <?php $total+=getTshirtPaid($id)>0?0:getTshirtPaid($id)*.25; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
                <tr>
                  <td colspan="5" align="right">
                    <b>Total</b>
                  </td>
                  <!-- <td>
                    <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                    <?php echo sprintf("%.2f",$totalAll); ?>
                  </td> -->
                  <td>
                    <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                    <?php echo sprintf("%.2f",$total); ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <?php include('../core/footer.php');?>
  <script type="text/javascript">
    $("#menu-links a").each(function(){
      if($(this).attr("href")==window.location.pathname)
      $(this).addClass("selected");
    })
    PrintElem = function(divName) {
       document.getElementById(divName).style.color = 'red';
       $(".wrap").css("width","100%");
       $("h3").css("color","red")
       $("body").css("background","#fff");
       $("#logo-title").children()[0].style.color="#000";
       let userInfo = $(".user-info").html();
       let logo = document.querySelector("#logo").innerHTML;
       let printContents = document.getElementById(divName).innerHTML;
       let originalContents = document.body.innerHTML;
       document.body.innerHTML = "<br>"+logo+
       "<br><br><div class='user-info flex flex-wrap'>"+userInfo+"</div><br>"+printContents;
       window.print();
       document.body.innerHTML = originalContents;
       $("#logo-title").children()[0].style.color="#fff";
       $("input[type=password]").focus(function(){
         $(this).attr("type","text")})
       $("input[type=password]").blur(function(){
         $(this).attr("type","password")})
       $("h3").css("color","#0375e3")
       $("body").css("background","#ececec");
       $(".wrap").css("width","90%")
    }
  </script>
        <div style="display:none">
      <?php include('../core/sidebar.php')?>
    </div>
  <?php include('../js/common.php')?>
  </body>
</html>
