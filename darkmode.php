<?php
    require 'config/config.php';
    if(isset($_SESSION['account']) && $_POST['count']<=0){
      $mode = $_POST['mode']==='true'?1:0;

      if ($_SESSION['account']['access']<30) {
        $query = $con->prepare("UPDATE ccs_students SET darkmode = {$mode} WHERE id = {$_SESSION['account']['id']}");
        $query->execute();
      } else {
        $query = $con->prepare("UPDATE psits_officers SET darkmode = {$mode} WHERE officer_id = {$_SESSION['account']['officer_id']}");
        $query->execute();
      }

      $_SESSION['account']['darkmode'] = $_POST['mode'];
    }
    $_SESSION['darkmode']=$_POST['mode'];
    echo $_SESSION['darkmode'];
  ?>
