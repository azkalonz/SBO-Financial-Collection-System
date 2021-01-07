<?php
  function exists($id,$sem) {
    global $con;
    $query = $con->prepare("SELECT * FROM ccs_students WHERE student_id = {$id} AND sem = {$sem}");
    $query->execute();
    if($query->fetch()>0)
      return true;
    else
      return false;
  }
  function event_exists($sem,$event) {
    global $con;
    $sql = sprintf("SELECT * FROM psits_events WHERE sem = %d AND id = '%s'",$sem,$event);
    $query = $con->prepare($sql);
    $query->execute();
    if($query->fetch()>0)
      return true;
    else
      return false;
  }
  function getEmail($id) {
    global $con;
    $query = $con->prepare("SELECT email FROM ccs_students WHERE student_id = {$id}");
    $query->execute();
    return $query->fetch()[0];
  }
  function getEventName($id) {
    global $con;
    $query = $con->prepare("SELECT name FROM psits_events WHERE id = {$id}");
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
  function sendToMail($data){
    $studentinfo = json_encode($data['student']);
    $content = json_encode($data['content']);
    if(isset($data['size'])){
      $size = $data['size'];
    } else {
      $size = '';
    }
echo <<<EOD
<script>
      data = {};
      data["action"] = "send-receipt";
      data["title"] = '{$data['content']['title']}';
      data["message"] = 'Collection';
      data["email"] = '{$data['student']['email']}';
      data["amount"] = '{$data['content']['amount']}';
      data["date"] = '{$data['content']['date']}';
      data["size"] = '$size';
      data["student_id"] = '{$data['student']['id']}';
      data["name"] = '{$data['student']['name']}';
      data["event"] = '{$data['content']['event']}';
      data["collection_id"] = {$data['content']['collection_id']};
      $.ajax({
        method: "POST",
        data: data,
        url: "../mail.php",
        success: function(data){
          e = JSON.parse(data);
          if(e.error){
            $("#collect_result").html(`Receipt not sent to email. But amount is collected. Resend email later
            <a href="/dashboard/student/?action=student&all&id={$data['student']['sid']}">Here</a>`);
            return;
          }
          $("#collect_result").html("Receipt sent to email.");
        }
      })
      </script>
EOD;
  }
  class Request {
    public function __construct($action,$user,$desc,$event,$sem){
      global $con;
      $sql = sprintf("INSERT INTO psits_logs(action, user, _date, _time, description,event_id,sem) VALUES ('%s','%s','%s','%s','%s',%d,%d)",
      $action, $user, date("F d, Y"), date("h:i A"), $desc,$event, $sem);
      $query = $con->prepare($sql);
      $query->execute();
    }
    public function __newstudent($fnm,$lnm,$fln,$crs,$yr,$sem,$em,$id) {
      global $con;
      $sql = sprintf("INSERT INTO ccs_students(student_id, first_name, last_name, full_name, course, _year, sem, email, change_pass, password)
      VALUES (%d, '%s', '%s', '%s', '%s', %d, %d, '%s', %d, '%s')", $id, $fnm, $lnm, $fln, $crs, $yr, $sem, $em, 1, strtoupper($lnm));
      $query = $con->prepare($sql);
      $query->execute();
    }
    // public function __register($id, $sem, $event)
  }
?>
