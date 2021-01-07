<?php
  include("../../config/config.php");
  include("../../api/func.php");
  // $id = $_SESSION["account"]["officer_id"];
  // if(isset($_GET["action"])) {
  //   $notif = $_GET["action"];
  //   if($notif=="activity"){
  //     $over_view_count = $con->prepare("SELECT COUNT(id) FROM psits_logs");
  //     $over_view_count->execute();
  //     $over_view_count = $over_view_count->fetch()[0];
  //   } else if($notif=="liquidation") {
  //     $over_view_count = $con->prepare("SELECT COUNT(id) FROM notifications WHERE receiver_id = 5 AND NOT sender_id = {$_SESSION['account']['officer_id']}");
  //     $over_view_count->execute();
  //     $over_view_count = $over_view_count->fetch()[0];
  //     // $reg = 0;
  //     // foreach ($over_view_count as $key => $value) {
  //     //   $reg += sizeof(explode(", ",$value["student"]))-1;
  //     // }
  //     // $over_view_count = $reg;
  //     // $over_view_count = 3;
  //   }
  //
  //   $seen = $con->prepare("SELECT seen FROM psits_officers WHERE officer_id = {$id}");
  //   $seen->execute();
  //   $seen = $seen->fetch()[0];
  //   $seen = explode(", ", $seen);
  //   foreach ($seen as $key => $value) {
  //     $n_seen[explode("->",$value)[0]] =  explode("->",$value)[1];
  //   }
  //   if($n_seen[$notif]<$over_view_count){
  //     $count = $over_view_count-$n_seen[$notif];
  //     $logs = $con->prepare("SELECT * FROM psits_logs ORDER BY id DESC LIMIT {$count}");
  //     $logs->execute();
  //     $info = $logs->fetchAll(PDO::FETCH_ASSOC);
  //     foreach ($info as $key => $value) {
  //       $info[$key]['user'] = fullName($value['user']);
  //       $info[$key]['description'] = getFullName(explode('->',$value['description'])[1]);
  //     }
  //     echo 123;
  //     $ar = array(
  //       'count'=>$count,
  //       'info'=>$info);
  //     echo json_encode($ar);
  //   } else {
  //     echo "";
  //   }
  //   $str='';
  //   if(isset($_GET["update"])) {
  //     $n_seen[$notif] = $over_view_count;
  //     $ca=0;
  //     foreach($n_seen as $key =>$value) {
  //       $str.=$key.'->'.$value;
  //       if($ca<sizeof($n_seen)-1){
  //         $str.=", ";
  //       }
  //       $ca++;
  //       echo "";
  //     }
  //     $query = $con->prepare("UPDATE psits_officers SET seen = '{$str}' WHERE officer_id = {$id}");
  //     $query->execute();
  //   }
  // }

 ?>
