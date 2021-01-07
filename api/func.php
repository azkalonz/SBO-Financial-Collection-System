<?php
  function getMoneyLeft2($id){
    global $con;
    $query = $con->prepare("SELECT (received-amount) as money_left FROM psits_expenses WHERE id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getRecevied($id){
    global $con;
    $query = $con->prepare("SELECT received FROM psits_expenses WHERE id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getAmount($id){
    global $con;
    $query = $con->prepare("SELECT amount FROM psits_expenses WHERE id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
   function displayStudent($sem) {
    global $con;
    $sql = sprintf("SELECT * FROM ccs_students WHERE sem = {$sem} GROUP BY student_id ORDER BY id DESC");
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
  // function totalStudent($sem) {
  //   global $con;
  //   $query = $con->prepare("SELECT * FROM ccs_students WHERE student_id in (SELECT student_id FROM ccs_students GROUP BY student_id HAVING COUNT(student_id)=1) AND sem = {$sem};");
  //   $query->execute();
  //   return $query->fetch()[0];
  // }
  function isLoggedIn() {
    if(isset($_SESSION["account"]))
      return true;
    else
      return false;
  }
  function totalStudentAll() {
    global $con;
    $query = $con->prepare("SELECT count(distinct student_id) from ccs_students");
    $query->execute();
    return $query->fetch()[0];
  }
  function allRegStudent() {
    global $con;
    $c=0;
    $query = $con->prepare("SELECT student FROM psits_events");
    $query->execute();
    $res = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($res as $d) {
      $d["student"] = explode(",",$d["student"]);
      $c = $c+(sizeof($d["student"])-1);
    }
    return $c;
  }
  function getEventName($id) {
    global $con;
    $query = $con->prepare("SELECT name FROM psits_events WHERE id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
  function months(){
    $months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    return $months;
  }
  function getPrice($id){
    global $con;
    $query = $con->prepare("SELECT price FROM psits_events WHERE id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getPayment($event,$id){
    global $con;
    $query = $con->prepare("SELECT payment FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getEncoder($event,$id){
    global $con;
    $query = $con->prepare("SELECT encoded_by FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  // function getDateModified($event,$id){
  //   global $con;
  //   $query = $con->prepare("SELECT _date FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
  //   $query->execute();
  //   return $query->fetch()[0];
  // }
  function fullName($user){
    global $con;
    $sql = sprintf("SELECT full_name FROM psits_officers WHERE username = '%s'",$user);
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetch()[0];
  }
  function getOID($username){
    global $con;
    $sql = sprintf("SELECT officer_id FROM psits_officers WHERE username = '%s'",$username);
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetch()[0];
  }
  function getUsername($id) {
    global $con;
    $sql = sprintf("SELECT username FROM psits_officers WHERE officer_id = $id");
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetch()[0];
  }
  function fullNameId($id){
    global $con;
    $sql = sprintf("SELECT full_name FROM psits_officers WHERE officer_id = %d",$id);
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetch()[0];
  }
  function studentFullNameId($id){
    global $con;
    $sql = sprintf("SELECT full_name FROM ccs_students WHERE id = %d",$id);
    $query = $con->prepare($sql);
    $query->execute();
    return $query->fetch()[0];
  }
  function getStudentId($id){
    global $con;
    $query = $con->prepare("SELECT id FROM ccs_students WHERE student_id = {$id}");
    $query->execute();
    return $query->fetch()[0];}
  function getFullName($id){
    global $con;
    $query = $con->prepare("SELECT full_name FROM ccs_students WHERE student_id = {$id}");
    $query->execute();
    return $query->fetch()[0];}
  function getStatus($event,$id){
    global $con;
    $query = $con->prepare("SELECT status FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getCollectionId($event,$id){
    global $con;
    $query = $con->prepare("SELECT id FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getMonthPaid($event,$id){
    global $con;
    $query = $con->prepare("SELECT month FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getMoneyLeft($id){
    global $con;
    $left = 0;
    $query = $con->prepare("SELECT received, amount FROM psits_expenses WHERE event_id = {$id} AND status = 1");
    $query->execute();
    foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $key => $value) {
      $left+=$value['received']-$value['amount'];
    }
    return $left;
  }
  function getYearPaid($event,$id){
    global $con;
    $query = $con->prepare("SELECT year_ FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getFund($event) {
    global $con;
    $res = 0;
    $query = $con->prepare("SELECT payment FROM psits_collections WHERE event = {$event}");
    $query->execute();
    while($amount = $query->fetch()){
      $res+=$amount[0];}
    return $res;
  }
  function getPaymentData($event,$id){
    global $con;
    $query = $con->prepare("SELECT * FROM psits_collections WHERE student_id = {$id} AND event = {$event}");
    $query->execute();
    return $query->fetch();
  }
  function getSale($event){
    global $con;
    $query = $con->prepare("SELECT sale FROM psits_events WHERE id = ${event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function latestCollection($event){
    global $con;
    $query = $con->prepare("SELECT _date FROM psits_collections WHERE event = {$event} ORDER BY _date DESC");
    $query->execute();
    return $query->fetch()[0];
  }
  function getTotalStudents($event){
    global $con;
    $query = $con->prepare("SELECT count(id) FROM psits_collections WHERE event = {$event}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getExpenses($event){
    global $con;
    $total = 0;
    $query = $con->prepare("SELECT amount FROM psits_expenses WHERE event_id = {$event}");
    $query->execute();
    while($s = $query->fetch()){
      $total+=$s[0];
    }
    return $total;
  }
  function getLiquidationDate($id){
    global $con;
    $query = $con->prepare("SELECT _enddate FROM psits_liquidation WHERE exp_id = {$id}");
    $query->execute();
    $res = $query->fetch()[0];
    return strlen($res)>0?$res:"<a style='color: red;'>Pending</a>";
  }
  function getExpensesInfo($id) {
    global $con;
    $query = $con->prepare("SELECT * FROM psits_expenses WHERE id = {$id}");
    $query->execute();
    return $query->fetch();
  }
  function getTshirtPaid($id) {
    global $con;
    $query = $con->prepare("SELECT payment FROM psits_collections WHERE student_id = {$id} AND event = 3");
    $query->execute();
    return $query->fetch()[0];
  }
  function getTshirtSize($amount) {
    global $con;
    $prices = $con->prepare("SELECT price FROM psits_events WHERE id = 3");
    $prices->execute();
    $sizes = [
      'Extra small',
      'Small',
      'Medium',
      'Large',
      'Extra large'
    ];
    foreach (explode(', ',$prices->fetch()[0]) as $key => $value) {
      if($value==$amount){
        return str_replace(' ','_',$sizes[$key]);
      }
    }
  }
  function countMyPendingComments($event,$id){
    global $con;
    $query = $con->prepare("SELECT count(id) FROM comments WHERE event_id = {$event} AND student_id = {$id} AND stat = 2");
    $query->execute();
    return $query->fetch()[0];
  }


 ?>
