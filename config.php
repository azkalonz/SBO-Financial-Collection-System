<?php
  session_start();
  date_default_timezone_set("Asia/Manila");

  $dbname = 'sys_psits';
  $dbhost = '50.62.177.19';
  $dbuser = 'azkalonz';
  $dbpass = 'knun*nJOhIi.';
  $webtitle = 'PSITS';

  $webhost = "http://mark.net/";
  try {
    $con = new PDO("mysql:host={$dbhost};dbname={$dbname}",$dbuser,$dbpass);
  } catch(Exception $e) {
    echo "<h1>Could not start mysql connection.</h1>
            <b>DB name:</b> {$dbname}<br>
            <b>DB host:</b> {$dbhost}<br>
            <b>DB user:</b> {$dbuser}<br>
            <b>DB pass:</b> {$dbpass}<br><br>".$e->getMessage();
  }
  function updateFunds() {
    global $con;
    $reg = $con->prepare("SELECT * FROM psits_events");
    $reg->execute();
    $reg = $reg->fetchAll(PDO::FETCH_ASSOC);
    // foreach ($reg as $key => $value) {
    //   $total_reg = sizeof(explode(", ",$value["student"]))-1;
    //   $total_reg *= $value["price"];
    //   $query = $con->prepare("UPDATE psits_events SET fund = {$total_reg} WHERE id = {$value['id']}");
    //   $query->execute();
    // }
  }
  updateFunds();
  function secure(){
    if(!isset($_SESSION['account']['password'])){
      header('location: /account/login.php?sec&ref='.str_replace('&','__',$_SERVER['REQUEST_URI']));
    }
  }
?>
