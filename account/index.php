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
  $id = $_SESSION['account']['username'];
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
  $pending_comments = $con->prepare("SELECT * FROM comments WHERE student_id = {$_SESSION['account']['user-id']} AND stat = 2");
  $pending_comments->execute();
  $pending_comments = $pending_comments->fetchAll(PDO::FETCH_ASSOC);
  if(isset($_POST["pass"])){
    $passw = $_POST["pass"];
    $email = $_POST["email"];
    if($_SESSION['account']['access']==0){
      $sql = sprintf("UPDATE ccs_students SET password='%s', email='%s', change_pass=0 WHERE student_id = %d",$passw,$email,$id);
    }else{
      $sql = sprintf("UPDATE psits_officers SET password='%s' WHERE officer_id = %d",$passw,$id);
    }
    $query = $con->prepare($sql);
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
    <title><?php echo $webtitle?> - My Account</title>
    <script type="text/javascript" src="../js/mkj-api.js"></script>
    <?php include('../js/jquery.php') ?>
    <?php include('../css/styles.php') ?>
    <script type="text/javascript" src="../js/progressbar.js"></script>
    <script type="text/javascript">
    $(document).ready( function () {
      $('#dt').DataTable();
    });
    </script>
  </head>
  <body>
  <?php include("../core/header.php") ?>
  <div class="fake-body">
  <div class="wrap flex" id="container">
    <div class="acc-mnger flex flex-wrap align-start col-100">
      <div class="col-100 flex">
        <div style="background-color:#303030;padding: 20px 0;" class="s-cont">
          <ul class="normal-ul light-a no-bold" style="min-width: 200px;">
            <li>
              <a href="/account/?tab=account-settings">
                <i class="fal fa-cog"></i>
                Account settings</a>
            </li>
            <li>
              <a href="/account/?tab=payment">
                <i class="fal fa-history"></i>
                Payment history</a>
            </li>
            <!-- <li>
              <a href="/account/?tab=comment-settings">
                <i class="fal fa-calendar-alt"></i>
              Participated events</a>
            </li> -->
            <li>
              <a href="/account/?tab=comment-settings">
                <i class="fal fa-comment-alt"></i>
              Comments</a>
            </li>
          </ul>
        </div>
        <div class="account-info flex flex-wrap col-100" style="padding: 20px;">
          <?php if (!isset($_GET['tab'])): ?>
            <?php $_GET['tab'] = 'def' ?>
          <?php endif; ?>
          <?php if ($_GET['tab']=='account-settings' || $_GET['tab']=='def'): ?>
            <?php if (isset($_POST['pass'])){ ?>
              <div class="col-100">
                <?php if(strtoupper($_POST['pass'])!=strtoupper($_SESSION['account']['last_name'])){ ?>
                <?php if (strlen($_POST['pass'])>=7 && $query->execute()){ ?>
                  <?php $_SESSION['account']['password']=$passw;
                  if($_SESSION['account']['access']==0){
                    $_SESSION['account']['email']=$email;
                  }
                  ?>
                  <span class="success sm">
                    <b>Account info was updated successfully!</b>
                  </span>
                <?php } else{ ?>
                    <span class="error sm">
                      <b>Password must be at least 7 characters long.</b>
                    </span>
                <?php } ?>
              <?php } else { ?>
                  <span class="error sm">
                    <b>You can't use your default password again.</b>
                  </span>
                <?php } ?>
              </div>
            <?php } ?>
            <header class="col-100">
              <h3>Account manager</h3>
            </header>
            <form class="col-100" method="post" name="account">
            <div class="flex col-100">
              <div>
                Username
              </div>
              <div class="col-100">
                <input type="text" value="<?php echo $id ?>" readonly>
              </div>
            </div>
            <div class="flex col-100">
              <div>
                Password
              </div>
              <div class="col-100">
                <input type="password" spellcheck="false" name="pass" value="<?php echo $pass ?>">
              </div>
            </div>
            <div class="flex col-100">
              <div>
                Email
              </div>
              <div class="col-100">
                <input type="text" spellcheck="false" name="email" value="<?php echo $email ?>">
              </div>
            </div>
            <div class="flex col-100 justify-end">
              <button type="button" onclick="$('form[name=account]').submit()">Update</button>
            </div>
            </form>
            <?php else: ?>
              <?php if ($_GET['tab']=='comment-settings'): ?>
                <header class="col-100 flex align-center justify-space-between">
                  <h3>Pending comments</h3>
                </header>
                <div class="col-100">
                    <p>Your comment and feedbacks are important to us, thus we review them carefully before it goes out to public. If your comment is still pending for more than a week, then maybe it is inappropriate. Thank you for patience.</p>
                </div>
                <table id="dt" style="width: 100%;">
                  <thead>
                    <tr>
                      <td colspan="4" class="table-title">Pending comments</td>
                    </tr>
                    <tr>
                      <th>Date</th>
                      <th>Section</th>
                      <th>Comment</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($pending_comments as $key => $value): ?>
                      <tr>
                        <td style="white-space: pre"><?php echo $value['_date'] ?></td>
                        <td style="white-space: pre"><?php echo getEventName($value['event_id']) ?></td>
                        <td style="white-space: pre-wrap;word-break: break-word;width: 400px;"><?php echo $value['comment'] ?></td>
                        <td>
                          <a href="#" onclick="del(<?php echo $value['id'] ?>)">Delete</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php else: ?>
                <?php if ($_GET['tab']=='payment'): ?>
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
                  <div id="a-info" class="col-100" style="margin: 0;display:none;">
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
                    <div class="flex flex-wrap col-100" style="width: 100%!important;">
                      <header class="col-100 flex align-center justify-space-between">
                        <h3>Payment history</h3>
                        <div class="">
                          <a href="#" onclick="PrintElem('paymentInfo')">
                            <i class="fal fa-print"></i>
                            Print</a> &nbsp;&nbsp;
                          <a href="/account/payment.php" target="_blank">
                            <i class="fal fa-external-link"></i>
                            Full view
                          </a>
                        </div>
                      </header>
                      <div class="col-100">
                          <p>This section shows your current payment status of all PSITS activities. Failure of payment will result in a penalty of 25% of the payment amount. Student clearance will not be signed by the PSITS officers until penalty is paid.</p><br>
                      <p>Please let us know immediately if you think there is a mistake in your payment information.</p><br>
                      </div>
                      <div class="col-100" style="overflow-x: auto;" id="paymentInfo">
                        <table>
                          <thead>
                            <tr>
                              <td class="table-title" colspan="6" align="right" style="background: #1981b9!important;">Summary</td>
                            </tr>
                            <tr>
                              <th style="white-space:pre;">Event</th>
                              <th style="white-space:pre;">Payment date</th>
                              <th style="white-space:pre;">Received by</th>
                              <th style="white-space:pre;">Status</th>
                              <th style="white-space:pre;">Amount paid</th>
                              <!-- <th style="white-space:pre;">Due payment</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($events as $key => $value): ?>
                              <?php $payment_data = getPaymentData($value['id'],$id); ?>
                              <tr>
                                <td style="max-width: 230px;white-space:pre;overflow:hidden;text-overflow: ellipsis;"><?php echo $value['name'] ?><?php if ($value['id']==3): ?><i><small><?php echo getTshirtSize(getTshirtPaid($id)); ?></small></i><?php endif; ?>
                                </td>
                                <td><?php echo $payment_data['_date'] ?></td>
                                <td><?php echo $payment_data['encoded_by'] ?></td>
                                <td><?php echo $payment_data['status']=="Paid"?"Paid":"Unpaid" ?></td>
                                <td>
                                  <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                                  <?php $payment = $payment_data['payment'];
                                  echo sprintf("%.2f",$payment);  ?>
                                </td>
                                <!-- <td>
                                  <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                                  <?php if ($value['id']!=3): ?>
                                    <?php echo $payment_data['payment']>0?0:$value['price']*.25;  ?>
                                    <?php else: ?>
                                      <?php echo getTshirtPaid($id)>0?0:getTshirtPaid($id) ?>
                                  <?php endif; ?>
                                </td> -->
                              </tr>
                              <?php $totalAll+=$payment?>
                              <?php if ($value['id']!=3): ?>
                                <?php $total+=$payment_data['payment']>0?0:(float)$value['price']*.25; ?>
                              <?php else: ?>
                                <?php $total+=getTshirtPaid($id)>0?0:getTshirtPaid($id)*.25; ?>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colspan="4" align="right">
                                <b>Total</b>
                              </td>
                              <!-- <td>
                                <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                                <?php echo sprintf("%.2f",$totalAll); ?>
                              </td> -->
                              <td>
                                <img src="../dashboard/img/peso.svg" width="10" style="user-select: none;" alt="P">
                                <?php echo sprintf("%.2f",$totalAll); ?>
                              </td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                <?php else: ?>
                  <?php if ($_GET['tab']=='receipts'): ?>
                    <?php
                    $my_collection = $con->prepare("SELECT * FROM psits_collections WHERE student_id = {$_SESSION['account']['student_id']}");
                    $my_collection->execute();
                    $my_collection = $my_collection->fetchAll(PDO::FETCH_ASSOC);
                     ?>
                    <?php foreach ($my_collection as $key => $value): ?>
                      <a href="receipt.php?<?php echo "cid={$value['id']}&amount={$value['payment']}" ?>">
                        <?php echo getEventName($value['event']) ?>
                      </a>
                      <?php if (isset($_GET['id'])): ?>

                      <?php endif; ?>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php $hideQuickies = true ?>
  </div>
  </div>
  <?php include('../core/footer.php');?>
  <script type="text/javascript">
    checkerror = function() {
      if($(".error").text().replace(/\n/g, '').replace(/ /g,'')<=0){
        $(".error").hide();
      }else {
        $(".error").show();
      }
    }
    $("input[type=password]").focus(function(){
      $(this).attr("type","text")})
    $("input[type=password]").blur(function(){
      $(this).attr("type","password")})
      $("#menu-links a").each(function(){
        if($(this).attr("href")==window.location.pathname)
        $(this).addClass("selected");
      })
  </script>
  <script type="text/javascript">
    let  del = function(id){
      event.preventDefault();
      $.ajax({
        method: 'GET',
        url: '/api/send.php?action=del&id='+id,
        success: function(data){
          location.reload();
        }
      })
    }
    $("#menu-links a").each(function(){
      if($(this).text().trim()=='My account')
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
       // document.body.innerHTML = "<br>"+logo+
       // "<br><br><div class='user-info flex flex-wrap'>"+userInfo+"</div><br>"+printContents;

       myWindow=window.open('','','width=1000,height=500');
       myWindow.document.write(`
        <head>
          ${document.head.innerHTML}
        </head>
        <body style="background: #fff!important;">
        ${logo}
        <br><br>
        <div class='user-info flex flex-wrap'>
          ${userInfo}
        </div><br>
        ${printContents}
        </body>
      `);
       myWindow.document.close();
       myWindow.focus();
       myWindow.print();
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
