<?php
  session_start();
  $user = isset($_SESSION['account']['officer_id'])?$_SESSION['account']['officer_id']:$_SESSION['account']['student_id'];
  $_SESSION['submition'] = ["stat"=>true];

  (function(){
    global $user;
    if(!isset($_SESSION['account'])){return;}
    $image = $_FILES['entry']['tmp_name'];
    $location = '../intramurals-2019/entries/'.$user;
    $info = getimagesize($image);



    if($info["mime"] == "image/jpeg"){
      $image = imagecreatefromjpeg($image);
    } else if($info["mime"] == "image/png") {
      $image = imagecreatefrompng($image);
    } else if($info["mime"] == "image/gif") {
      $image = imagecreatefromgif($image);
    } else {
      $_SESSION['submition'] = ["stat"=>false];
    }
  imagejpeg($image,$location.'.png',100);
  imagejpeg($image,$location.'-thumb.png',10);

  $json = file_get_contents('../intramurals-2019/votes.js');
  $votes = json_decode("$json",true);
  $votes["s{$user}"] = ["voters"=>[],"votes"=>0,"name"=>$_SESSION['account']['full_name']];
  $vote_file = fopen('../intramurals-2019/votes.js', 'w');
  $new_votes = json_encode($votes);
  fwrite($vote_file, $new_votes);
  fclose($vote_file);

  })();
  header('location:'.$_SERVER['HTTP_REFERER']);
?>
