<?php
  include('../../config/config.php');
  if($_POST['action']=='accept'){
    foreach ($_POST['items'] as $value) {
      $query = $con->prepare("UPDATE comments SET stat = 1 WHERE id = {$value}");
      if($query->execute()){
        echo 'Comment accepted';
      }
    }
  } else if($_POST['action']=='ignore'){
      foreach ($_POST['items'] as $value) {
        $query = $con->prepare("UPDATE comments SET stat = 2 WHERE id = {$value}");
        if($query->execute()){
          echo 'Comment ignored';
        }
      }
    } else if($_POST['action']=='trash'){
        foreach ($_POST['items'] as $value) {
          $query = $con->prepare("DELETE FROM comments WHERE id = {$value}");
          if($query->execute()){
            echo 'Comment deleted';
          }
        }
    }
?>
