<?php
  require("config/config.php");
  include("_.php");

  $title = "{$labels['text-logo']} Update";
  $message = "No message";
  $name = '';
  if(empty($_POST['action'])){
    echo json_encode(['error'=>'Please provide an action.']);
    exit;
  }
  if(isset($_POST['title']) && !empty($_POST['title'])){
    $title = $_POST['title'];
  } else {
    echo json_encode(['error'=>'Please provide a title.']);
    exit;}
  if(empty($_POST['message'])){
    echo json_encode(['error'=>'Please provide a message.']);
    exit;
  } else {
    $message = $_POST['message'];
  }

  $emails = $con->prepare("SELECT * FROM ccs_students");
  $emails->execute();
  $emails = $emails->fetchAll(PDO::FETCH_ASSOC);

  $subscribers = $con->prepare("SELECT * FROM subscribers");
  $subscribers->execute();
  $subscribers = $subscribers->fetchAll(PDO::FETCH_ASSOC);
 ?>
<?php
if($_POST['action']!='newsletter'){
  $date = date('F_d,_Y');
  $user = str_replace(' ','_',$_SESSION['account']['full_name']);
}
if($_POST['action']=='send-receipt'){
  if(strlen($_POST['size'])>1){
    $size = '&size='.$_POST['size'];
  } else {
    $size = '';
  }
  $student_id = $_POST['student_id'];
  $info = "<span style='color: #9C27B0;'>{$_SESSION['account']['full_name']}  |  {$date}</span>";
  $name = str_replace(' ','_',$_POST['name']);
  $amount = $_POST['amount'];
  $event = str_replace(' ','_',$_POST['event']);
  $collection_id = $_POST['collection_id'];
  $message = file_get_contents("{$webhost}template.php?date={$date}&collection_id={$collection_id}&event={$event}&name={$name}&user={$user}&amount={$_POST['amount']}&sid={$student_id}{$size}");
} else if($_POST['action']=='newsletter') {
  $message = $message;
} else if($_POST['action']=='refund'){
  $query = $con->prepare("SELECT * FROM psits_collections WHERE id = {$_POST['id']}");
  $query->execute();
  $postdata = http_build_query(
    array(
        'info' => json_encode($query->fetchAll(PDO::FETCH_ASSOC)),
    )
  );
  $opts = array('http' =>
      array(
          'method'  => 'POST',
          'header'  => 'Content-Type: application/x-www-form-urlencoded',
          'content' => $postdata
      )
  );
  $context  = stream_context_create($opts);
  $message = file_get_contents("{$webhost}refund_template.php?date={$date}&user={$user}", false, $context);
} else {
  $postdata = http_build_query(
    array(
        'message' => $_POST['message'],
    )
  );
  $opts = array('http' =>
      array(
          'method'  => 'POST',
          'header'  => 'Content-Type: application/x-www-form-urlencoded',
          'content' => $postdata
      )
  );
  $context  = stream_context_create($opts);
  $message = file_get_contents("{$webhost}template.php?date={$date}&user={$user}", false, $context);
}
 ?>
<?php
 require 'mailer/PHPMailerAutoload.php';

//Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->isSMTP();
// change this to 0 if the site is going live
    // $mail->Debugoutput = 'html';
    $mail->Host = 'tls://smtp.gmail.com';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    // $mail->SMTPDebug  = 4;
    $mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
    );

 //use SMTP authentication
    $mail->SMTPAuth = true;
//Username to use for SMTP authentication
    $mail->Username = $webmail['email'];
    $mail->Password = $webmail['pass'];
    $mail->setFrom($webmail['email'], $webmail['name']);
    if(!isset($_POST['email'])){
      foreach ($emails as $key => $value) {
        if(!empty($value['email'])){
          $mail->addReplyTo($webmail['email'], '');
          $mail->AddBCC($value['email'], '');
        }
      }
      foreach ($subscribers as $key => $value) {
        if(!$value['active']){continue;}
        if(!empty($value['email'])){
          $mail->addReplyTo($webmail['email'], '');
          $mail->AddBCC($value['email'], '');
        }
      }
    } else {
      $mail->AddAddress($_POST['email'], str_replace('_',' ',$name));
    }
    $mail->Subject = $title;
    $mail->IsHTML(true);
    $mail->Body = $message;
    $mail->AltBody = "test";
    if (!$mail->send()) {
        echo json_encode(['error'=>'Error: Make sure that you are connected to the internet and use the correct PORT and webhost.']);
    } else {
      echo json_encode([]);
    }
?>
