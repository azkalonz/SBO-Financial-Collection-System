<?php include("../../config/config.php");?>
<?php require("../../api/request.php"); ?>
<?php function getEventFund($id){
  global $con;
  $query = $con->prepare("SELECT funds FROM psits_events WHERE id = {$id}");
  $query->execute();
  return $query->fetch()[0];
} ?>
<?php
  if(isset($_POST["action"])){
    $id = $_SESSION["account"]["officer_id"];
    $user = $_SESSION["account"]["full_name"];
    if($_POST["action"]=="add"){
      foreach ($_POST as $key => $value) {
        $d["$key"] = $value;
      }
      $title = $d['title'];
      $amount = (double)$d['amount'];
      $sem = $d['sem'];
      $event = $d['event'];
      $m = date("F"); $d = date("d"); $y = date("Y");
      $date = $m." ".$d.", ".$y;
      if($title==""||$title==null){
        echo "<span class='error sm'><h5>Title cannot be empty</h5></span>";return;
      } else if($amount==""||$amount==null){
        echo "<span class='error sm'><h5>Amount cannot be empty</h5></span>";return;}
      $sql = sprintf("INSERT INTO psits_expenses(user_id,event_id,sem,user,title,received,_date,_month,_day,_year,_time)
       VALUES (%d,%d,%d,'%s','%s',%.2f,'%s','%s',%d,%d,'%s')", $id,$event,$sem,$user,$title,$amount,$date,$m,$d,$y,date("h:i:s A"));
      $exp = $con->prepare($sql);
      $pending_exp = $con->prepare("INSERT INTO pending_expenses SET officer_id = {$_SESSION['account']['user-id']}, exp_id = (SELECT MAX(id) FROM psits_expenses)");
      if($exp->execute()){
        if($_SESSION['account']['access']<40){
          $pending_exp->execute();
        }
        $register = new Request("Cash out",$_SESSION['account']['username'],$amount,$event,1);
        echo "<span class='success sm'><h5>Your request of Php".$amount." was successful and is now pending.</h5></span>";
        ?>
        <!-- <script type="text/javascript">
        $("#disp").load("expenses/add.php",{},function(){
            $("#disp").html(`<span class='success sm'><h5>Php <?php echo $amount ?> was withdrawn by <?php echo $title ?></h5></span>`+$("#disp").html());
        });
        </script> -->
        <?php
      }
    }
    if ($_POST['action']=='accept') {
      $query = $con->prepare("DELETE FROM pending_expenses WHERE id = {$_POST['id']}");
      $resul = [];
      if($query->execute()){
        $result = [
          "stat"=>"success",
          "msg"=>"Accepted cash out request"
        ];
      } else {
        $result = [
          "stat"=>"error",
          "msg"=>"Database error please contact the web administrator."
        ];
      }
      echo json_encode($result);
    }
  }
  if(isset($_GET["notify"])) {
    $my_id = $_SESSION["account"]["officer_id"];
    $user_id = $_GET["user-id"];
    $exp_id = $_GET["exp-id"];

    $sql = sprintf("INSERT INTO notifications(exp_id,title,sender_id,receiver_id,message,_date,seen)
     VALUES (%d,'%s',%d,%d,'%s','%s',%d)",$exp_id,"Submit Liquidation",$my_id,$user_id,"Please submit your liquidation report before due.", date("F d, Y"), 0);
    $query = $con->prepare($sql);
    if($query->execute())
    echo "Sent";
    else
    echo "Not sent";
  }
 ?>
