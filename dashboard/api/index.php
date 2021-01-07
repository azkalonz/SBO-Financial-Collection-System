<?php
  require('../../config/config.php');
  require('../../api/func.php');
  (function(){
    global $con;
    $result = array();
    $sql = '';
    if(!isset($_GET['action'])){
      $result['error'] = 'Undefined Action';
      echo json_encode($result); return;
    }
    if(!isset($_SESSION['account']['access'])){
      $result['error'] = 'Invalid access';
      echo json_encode($result); return;
    } else if($_SESSION['account']['access']<30){
      $result['error'] = '401 Unauthorized';
      echo json_encode($result); return;
    }
    if($_GET['action']=='student'){
      $sql = 'SELECT ';
      if(isset($_GET['first_name'])){
        $sql .= 'first_name,';
      }
      if(isset($_GET['student_id'])){
        $sql .= 'student_id,';
      }
      if(isset($_GET['full_name'])){
        $sql .= 'full_name,';
      }
      if(isset($_GET['last_name'])){
        $sql .= 'last_name,';
      }
      if(isset($_GET['email'])){
        $sql .= 'email,';
      }
      if(isset($_GET['all'])){
        $sql = 'SELECT *,';
      }
      $sql = substr($sql, 0, -1);
      $sql .= ' FROM ccs_students ';
      if(isset($_GET['id'])){
        $sql .= 'WHERE id = '.$_GET['id'];
      }
    } else if($_GET['action']=='officer'){
          $sql = 'SELECT ';
          if(isset($_GET['first_name'])){
            $sql .= 'first_name,';
          }
          if(isset($_GET['full_name'])){
            $sql .= 'full_name,';
          }
          if(isset($_GET['all'])){
            $sql = 'SELECT *,';
          }
          $sql = substr($sql, 0, -1);
          $sql .= ' FROM psits_officers ';
          if(isset($_GET['id'])){
            $sql .= 'WHERE officer_id = '.$_GET['id'];
          }
        }
    $query = $con->prepare($sql);
    $query->execute();
    $result['result'] = $query->fetchAll(PDO::FETCH_ASSOC);

     if($_GET['action']=='payment'){
      $id = $_GET['id'];
      $query = $con->prepare("SELECT * FROM psits_collections WHERE student_id = {$id}");
      $query->execute();
      $result['payment'] = $query->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result['payment'] as $key => $value) {
        foreach ($result['payment'][$key] as $key2 => $value2) {
          if($key2=='event')
          $result['payment'][$key]['event'] = getEventName($result['payment'][$key]['event']);
        }
      }
    } else if($_GET['action']=='collected'){
          $id = $_GET['id'];
          $fullname = fullNameId($id);
          $query = $con->prepare("SELECT * FROM psits_collections WHERE encoded_by = '{$fullname}'");
          $query->execute();
          $result['payment'] = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result['payment'] as $key => $value) {
            foreach ($result['payment'][$key] as $key2 => $value2) {
              if($key2=='event'){
                $result['payment'][$key]['event'] = getEventName($result['payment'][$key]['event']);
              } else if($key2=='student_id'){
                $result['payment'][$key]['student_name'] = getFullName($result['payment'][$key]['student_id']);
              }
            }
          }
    } else if($_GET['action']=='cashout'){
          $id = $_GET['id'];
          $fullname = fullNameId($id);
          $query = $con->prepare("SELECT * FROM psits_expenses WHERE user_id = {$id}");
          $query->execute();
          $result['exp'] = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result['exp'] as $key => $value) {
            foreach ($result['exp'][$key] as $key2 => $value2) {
              if($key2=='event_id'){
                $result['exp'][$key]['event'] = getEventName($result['exp'][$key]['event_id']);
              }
            }
          }
        }
    if($_GET['action']=='register'){
      $id = $_GET['id'];
      $query = $con->prepare("SELECT * FROM psits_events");
      $query->execute();
      $student = $query->fetchAll(PDO::FETCH_ASSOC);

      foreach ($student as $key => $value) {
        foreach (explode(', ',$value['student']) as $key2 => $value2) {
          foreach (explode('->',$value2) as $key3 => $value3) {
            if($key3==0){
              if($value3==$id){
                $result['reg'][$key]['event'] = getEventName($value['id']);
                $date = $con->prepare("SELECT _date FROM psits_logs WHERE description LIKE '%$id%' AND event_id = {$value['id']}");
                $date->execute();
                $date = $date->fetch()[0];
                $result['reg'][$key]['date'] = $date;
                $result['reg'][$key]['registrant'] = fullName(explode('->',$value2)[1]);
              }
            }
          }
        }
      }
    } else if($_GET['action']=='registered'){
          $id = $_GET['id'];
          $query = $con->prepare("SELECT * FROM psits_events");
          $query->execute();
          $student = $query->fetchAll(PDO::FETCH_ASSOC);
          $username = getUsername($id);

          foreach ($student as $key => $value) {
            foreach (explode(', ',$value['student']) as $key2 => $value2) {
              foreach (explode('->',$value2) as $key3 => $value3) {
                if($key3==1){
                  if($value3==$_GET['username']){
                    $result['reg'][$key]['event'] = getEventName($value['id']);
                    $date = $con->prepare("SELECT _date FROM psits_logs WHERE user = '{$username}' AND event_id = {$value['id']}");
                    $date->execute();
                    $date = $date->fetch()[0];
                    $result['reg'][$key]['date'] = $date;
                    $result['reg'][$key]['student'] = getFullName(explode('->',$value2)[0]);
                  }
                }
              }
            }
          }
    } else if($_GET['action']=='hints'){
      switch($_GET['column']){
        case 'full_name':
        $query = $con->prepare('SELECT full_name FROM ccs_students');
        break;
        case 'student_id':
        $query = $con->prepare('SELECT student_id FROM ccs_students');
        break;
      }
      $query->execute();
      $query = $query->fetchAll(PDO::FETCH_ASSOC);
      $r = [];
      foreach ($query as $key => $value) {
        foreach ($value as $key2 => $value2) {
          array_push($r,"\"$value2\"");
        }
      }
      echo implode(', ',$r);
      exit;
    }
    echo json_encode($result);
  })();
?>
