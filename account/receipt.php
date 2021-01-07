<?php
  require("../config/config.php");
  require("../api/func.php");
  function cInfo($e){
    global $con;
    $query = $con->prepare("SELECT encoded_by,_date FROM psits_collections WHERE id = $e");
    $query->execute();
    return $query->fetch();
  }
  $encoder = cInfo($_GET['cid'])[0];
  $date = cInfo($_GET['cid'])[1];
  $student_id = $_SESSION['account']['student_id'];
  $info = "<span style='color: #9C27B0;'>{$encoder}  |  {$date}</span>";
  $name = str_replace(' ','_',getEventName($_GET['cid']));
  $amount = $_GET['amount'];
  $event = str_replace(' ','_',$name);
  $collection_id = $_GET['cid'];
  $user = $encoder;
  $message = file_get_contents("{$webhost}template.php?date={$date}&collection_id={$collection_id}&event={$event}&name={$name}&user={$user}&amount={$_GET['amount']}&sid={$student_id}");
 ?>
