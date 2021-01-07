<?php
  session_start();
  $success = true;
  (function(){
    global $success;
    $user = isset($_SESSION['account']['student_id'])?$_SESSION['account']['student_id']:$_SESSION['account']['officer_id'];
    $entry = "s{$_GET['id']}";
    $json = file_get_contents('../intramurals-2019/votes.js');
    $votes = json_decode("$json",true);
    foreach ($votes[$entry]['voters'] as $key => $value) {
      if($user == $value || !isset($_SESSION['account'])){
      $success = false;
      return;}
    }
    $votes[$entry]['votes']+=1;
    array_push($votes[$entry]['voters'],$user);
    $votes_js = fopen('../intramurals-2019/votes.js','w');
    $new_votes = json_encode($votes);
    fwrite($votes_js,$new_votes);
    fclose($votes_js);
  })();
  if($success)
  echo json_encode(['res'=>true]);
  else
  echo json_encode(['res'=>false]);
?>
