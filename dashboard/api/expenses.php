<?php
require('../../config/config.php');

(function(){
  global $con;
  if(isset($_GET['getInfo'])){
    $id = $_GET['id'];
    $query = $con->prepare("SELECT * FROM psits_expenses WHERE id = {$id} LIMIT 1");
    $query->execute();
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
  }
  if(isset($_POST['sendInfo'])){
    $details = '';
    $receipts = '';
    $amount = '';
    foreach ($_POST['details'] as $key => $value) {
      $details.= $value;
      if($key<sizeof($_POST['details'])-1)
      $details.= ', ';
    }
    foreach ($_POST['receipts'] as $key => $value) {
      $receipts.= $value;
      if($key<sizeof($_POST['receipts'])-1)
      $receipts.= ', ';
    }
    foreach ($_POST['amount'] as $key => $value) {
      $amount.= $value;
      if($key<sizeof($_POST['amount'])-1)
      $amount.= ', ';
    }
    $user = $_SESSION['account']['officer_id'];
    $date = $_POST['date'];
    $id = $_POST['id'];
    $event_id = $_POST['event_id'];
    $total = (float)$_POST['total'];
    $sql = sprintf("INSERT INTO psits_liquidation(user,_date,_endDate,exp_id,details,receipts,amount,total,event_id) VALUES (%d,'%s','%s',%d,'%s','%s','%s',%.2f,%d)", $user, $date, date('F d, Y'), $id, $details, $receipts, $amount, $total,$event_id);
    $query = $con->prepare($sql);
    if($query->execute()){
      $update = $con->prepare("UPDATE psits_expenses SET status = 1, amount = {$total}, receipt = '{$receipts}'  WHERE id = {$id}");
      if(!$update->execute()){
        echo 'false';
        return;
      }
      echo 'true';
    }
    else{
      echo 'false';}
  }
})();
?>
