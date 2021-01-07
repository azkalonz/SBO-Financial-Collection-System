<?php
  require("../config/config.php");
  include("request.php");
$error = <<<EOD
    <script type="text/javascript">
      if($("#registerResult").attr("class").split(" ")[1]!="error"){
        $("."+$("#registerResult").attr("class").split(" ")[1]).addClass("error");
        $("."+$("#registerResult").attr("class").split(" ")[1]).removeClass($("#registerResult").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$alert = <<<EOD
    <script type="text/javascript">
      if($("#registerResult").attr("class").split(" ")[1]!="alert"){
        $("."+$("#registerResult").attr("class").split(" ")[1]).addClass("alert");
        $("."+$("#registerResult").attr("class").split(" ")[1]).removeClass($("#registerResult").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$success = <<<EOD
    <script type="text/javascript">
      if($("#registerResult").attr("class").split(" ")[1]!="success"){
        $("."+$("#registerResult").attr("class").split(" ")[1]).addClass("success");
        $("."+$("#registerResult").attr("class").split(" ")[1]).removeClass($("#registerResult").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$error2 = <<<EOD
    <script type="text/javascript">
      if($("#result").attr("class").split(" ")[1]!="error"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("error");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$alert2 = <<<EOD
    <script type="text/javascript">
      if($("#result").attr("class").split(" ")[1]!="alert"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("alert");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$success2 = <<<EOD
    <script type="text/javascript">
      if($("#result").attr("class").split(" ")[1]!="success"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("success");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$alert3 = <<<EOD
    <script type="text/javascript">
      if($("#collect_result").attr("class").split(" ")[1]!="alert"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("alert");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$success3 = <<<EOD
    <script type="text/javascript">
      if($("#collect_result").attr("class").split(" ")[1]!="success"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("success");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$error3 = <<<EOD
    <script type="text/javascript">
      if($("#collect_result").attr("class").split(" ")[1]!="error"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("error");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$alert4 = <<<EOD
    <script type="text/javascript">
      if($("#editResult").attr("class").split(" ")[1]!="alert"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("alert");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$success4 = <<<EOD
    <script type="text/javascript">
      $('#disp').load('overview.php');
      if($("#editResult").attr("class").split(" ")[1]!="success"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("success");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;
$error4 = <<<EOD
    <script type="text/javascript">
      if($("#editResult").attr("class").split(" ")[1]!="error"){
        $("."+$("#result").attr("class").split(" ")[1]).addClass("error");
        $("."+$("#result").attr("class").split(" ")[1]).removeClass($("#result").attr("class").split(" ")[1]);
      } checkerror();
    </script>
EOD;

  if (isset($_GET["action"])) {
    $id = $_GET['id'];
    if($_GET['action']=='del'){
      $query = $con->prepare("DELETE FROM comments WHERE id = {$id} AND student_id = {$_SESSION['account']['user-id']}");
      if(!$query->execute()){
        echo json_encode(["error"=>"Error: Unable to delete comment"]);
      }
    }
    if($_GET['action']=='delete_collection'){
      $user = $_SESSION["account"]["username"];
      $query = $con->prepare("DELETE FROM psits_collections WHERE id = {$id}");
      $student = $con->prepare("SELECT * FROM psits_collections WHERE id = {$id}");
      $student->execute();
      $student = $student->fetchAll(PDO::FETCH_ASSOC)[0];
      if(!$query->execute()){
        echo json_encode(['error'=>'Error: Unable to uncollect']);
      } else {
        $desc = "student_id -> {$student['student_id']}";
        $t = new Request("Refunded {$student['payment']}",$user,$desc,$student['event'],1);
        echo json_encode(['success'=>'Success']);
      }
    }
    if($_GET['action']=='desc'){
      $query = $con->prepare("SELECT _desc,long_desc FROM psits_events WHERE id = {$id}");
      $query->execute();
      echo json_encode($query->fetch(PDO::FETCH_ASSOC));
    }
  }
  if(isset($_POST["action"])){
    if(isset($_SESSION["account"])){
      $user = $_SESSION["account"]["username"];
    }
    if($_POST['action']=='delete_note'){
      $id = $_POST['id'];
      $query = $con->prepare("UPDATE notes SET hidden = 0 WHERE id = $id");
      $query->execute();
    }
    if($_POST['action'] == 'note'){
      $err['error'] = false;
      $user = $_SESSION['account']['full_name'];
      $content = $_POST['content'];
      $title = $_POST['title'];
      $color = $_POST['color'];
      if(empty($title) || empty($content)){
        $err['error'] = true;
      }
      if(!$err['error']){
        $query = $con->prepare("INSERT INTO notes(title, msg, user, color) VALUES ('$title','$content','$user','$color')");
        if(!$query->execute()){
          $err['error'] = true;
        }
        $_SESSION['note']['color'] = $color;
        $latest = $con->prepare("SELECT id FROM notes ORDER BY id DESC LIMIT 1");
        $latest->execute();
        $err['id'] = $latest->fetch()[0];
      }
      echo json_encode($err);
    }
    if($_POST['action'] == 'update_officer'){
      $result = [];
      $user = $_POST['username'];
      $pass = $_POST['password'];
      $duplicate_user = $con->prepare("SELECT * FROM psits_officers WHERE username = '{$user}'");
      $duplicate_user->execute();
      $du = $duplicate_user->fetch();
      if($du>0 && $user!=$_SESSION['account']['username']){
        $result = [
          'error'=>'Username is already taken!'
        ];
      } else {
        if(strlen($pass)<7){
          $result = [
            'error'=>'Password must be at least 7 characters long.'
          ];
        } else {
          $c = $con->prepare("UPDATE psits_officers SET username = '{$user}', password = '{$pass}' WHERE officer_id = {$_SESSION['account']['officer_id']}");
          if(!$c->execute()){
            $result = [
              'error'=>'Database error'
            ];
          } else {
            $_SESSION['account']['username'] = $user;
            $_SESSION['account']['password'] = $pass;
          }
        }
      }
      echo json_encode($result);

    }
    if($_POST['action'] == 'edit_event'){
      $name = $_POST['event_name'];
      $id = $_POST['event_id'];
      $amount = $_POST['payment_amount'];
      $regbtn = $_POST['reg-btn'];
      $end_date = date('F d, Y',strtotime($_POST['end_date']));
      $start_date = date('F d, Y',strtotime($_POST['start_date']));
      $start_time = $_POST['start_time'];
      $allday = $_POST['all_day'];
      $color = $_POST['theme_color'];
      $long = $_POST['long_desc'];
      $short = $_POST['short_desc'];
      echo $alert4;
      if(empty($name)){echo 'Event name cannot be empty!'; return;}
      if((float)$amount < 0){echo 'Payment amount must be greater than or equal to 0!'; return;}
      // if((float)$rate < 0){echo 'Rate must be greater than or equal to 0!'; return;}
      // if(empty($start_date)){echo 'Date cannot be empty!'; return;}
      // if(!preg_match("/^[A-Z]{1}[a-z]{0,9}\s[\d]{1,2},\s[\d]{4}$/",$start_date)){echo 'Use date format M dd, yyy';return;}

      $sql = sprintf("UPDATE psits_events SET name = '%s', price = '%s', `date` = '%s', endDate = '%s', startTime = '%s', allDay = %d, theme_color = '%s', registration = %d, _desc = '%s', long_desc = '%s' WHERE id = %d", $name, $amount, $start_date, $end_date, $start_time, $allday, $color, $regbtn, $short, $long, $id);
      $query = $con->prepare($sql);
      if($query->execute()){
        $modify = sprintf("UPDATE psits_events SET modified = '%s', _by = '%s' WHERE id = %d",date("F d, Y"),$_SESSION['account']['username'],$id);
        $modify = $con->prepare($modify);
        $modify->execute();
        echo 'Event updated';
      }
      $desc = "student_id -> {$_SESSION['account']['officer_id']}";
      $register = new Request("Modified",$user,$desc,$id,1);
      echo $success4;
    }


    if($_POST['action'] == 'add_event'){
      $name = $_POST['event_name'];
      $id = $_POST['event_id'];
      $amount = $_POST['payment_amount'];
      $date = $_POST['date'];
      $sem = $_POST['sem'];
      $desc = $_POST['desc'];
      echo $alert4;
      if(empty($name)){echo 'Event name cannot be empty!'; return;}
      if(empty($desc)){echo 'Description cannot be empty!'; return;}
      if((float)$amount < 0){echo 'Payment amount must be greater than or equal to 0!'; return;}
      // if((float)$rate < 0){echo 'Rate must be greater than or equal to 0!'; return;}
      if(empty($date)){echo 'Date cannot be empty!'; return;}
      if(!preg_match("/^[A-Z]{1}[a-z]{0,9}\s[\d]{1,2},\s[\d]{4}$/",$date)){echo 'Use date format M dd, yyy';return;}

      $sql = sprintf("INSERT INTO psits_events SET name = '%s', _desc = '%s', `date` = '%s', price = '%s', sem = %d ",
      $name, $desc, $date, $amount, $sem);
      $add = $con->prepare($sql);
      if($add->execute()){
        echo $success4;
        echo 'Success';
      } else {
        echo $error4;
        echo 'Database error. Contact your web administrator.';
      }
    }

    if($_POST["action"] == "register") {
      $id = $_POST["student_id"];
      if(exists($id,$_POST["sem2"])){
        $sem = $_POST["sem2"];
        $event = $_POST["event_name"];
        if(true || event_exists($sem,$event)){
              $desc = "student_id -> {$id}";
              $current_registered_students = $con->prepare("SELECT student FROM psits_events WHERE id = {$event}");
              $current_registered_students->execute();
              $registered_students = explode(", ",$current_registered_students->fetch()[0]);
              for($i=0; $i<sizeof($registered_students); $i++){
                if(explode("->",$registered_students[$i])[0]==$id){
                  echo $alert;
                  echo "Already registered";return;}
              }
              $register = new Request($_POST["action"]."ed",$user,$desc,$event,$sem);
              array_push($registered_students, $id."->".$user);
              $sql = sprintf("UPDATE psits_events SET student = '%s' WHERE id = {$event}",implode(", ",$registered_students));
              $insert_new_students = $con->prepare($sql);
              if($insert_new_students->execute()){
                echo $success;
                ?>
                <script type="text/javascript">
                  $("<?php echo $_POST["disp"] ?>").html("Registered");
                  $("<?php echo $_POST["disp"] ?>").next().html("<?php echo $_SESSION["account"]["full_name"] ?>");
                </script>
                <?php
              echo "Registered";return;}
              else{
              echo "Error";return;}
            }else{
              echo $alert;
              $strsem = $sem==1?"1st":"2nd";
              echo "Event ".$event." does not exists in ".$strsem." semester!";
              }
        } else {
          echo $error;
          echo "Student doesn't exists!";return;}
    }
    if($_POST["action"] == "student-login"){
      $student_id = $_POST["student_id"];
      $pass = $_POST["student_password"];
      $query = $con->prepare(sprintf("SELECT * FROM ccs_students WHERE student_id = {$student_id} AND password LIKE BINARY '%s'",$pass));
      $query->execute();
      $res=$query->fetch(PDO::FETCH_ASSOC);
      if($res>0){
        foreach($res as $key => $val) {$_SESSION["account"][$key] = $val;}
        $_SESSION['account']['user-id'] = $_SESSION['account']['student_id'];
        $_SESSION['darkmode'] = $_SESSION['account']['darkmode']?'true':'false';
        ?>
        <script type="text/javascript">
          $(".error,.alert").addClass("success");
          $(".error").removeClass("error");
          $(".alert").removeClass("error");
          $(".success").html("<h5>Welcome back!</h5>");
          $(".success").show();
          setTimeout(function() {
            <?php if(!isset($_POST['ref'])): ?>
            window.location="../";
            <?php else: ?>
            window.location="<?php echo str_replace('__','&',$_POST['ref']) ?>";
            <?php endif; ?>
          },500);
        </script>
        <?php
      } else {
        ?>
        <script type="text/javascript">
          $('.alert').addClass('error');
          $('.alert').removeClass('alert');
        </script>
        <?php
        echo "Invalid Student ID or Password!";
      }
    }
    if($_POST["action"] == "teacher-login"){
      $username = $_POST["officer_id"];
      $pass = $_POST["password"];
      $query = $con->prepare(sprintf("SELECT * FROM psits_officers WHERE username = '%s' AND password LIKE BINARY '%s'",$username,$pass));
      $query->execute();
      $res=$query->fetch(PDO::FETCH_ASSOC);
      if($res>0){
        foreach($res as $key => $val) {$_SESSION["account"][$key] = $val;}
        $_SESSION['account']['user-id'] = $_SESSION['account']['officer_id'];
        $_SESSION['darkmode'] = $_SESSION['account']['darkmode']?'true':'false';
        ?>
        <script type="text/javascript">
          $(".error,.alert").addClass("success");
          $(".error").removeClass("error");
          $(".alert").removeClass("error");
          $(".success").html("<h5>Welcome back!</h5>");
          $(".success").show();
          setTimeout(function() {
            <?php if(!isset($_POST['ref'])): ?>
            window.location="../";
            <?php else: ?>
            window.location="<?php echo str_replace('__','&',$_POST['ref']) ?>";
            <?php endif; ?>
          },500);
        </script>
        <?php
      } else {
        ?>
        <script type="text/javascript">
          $('.alert').addClass('error');
          $('.alert').removeClass('alert');
        </script>
        <?php
        echo "Invalid Username or Password!";
      }
    }

    if($_POST["action"] == "add") {
      if(!exists($_POST["id"],$_POST["sem"])){
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $fullname = $fname." ".$lname;
        $sem = $_POST["sem"];
        $id = $_POST["id"];
        $course = $_POST["course"];
        $year = $_POST["yearlevel"];
        $email = $_POST["email"];
        if(!$fname == "") {
          if(!$lname == "") {
              if(!$sem == ""){
                if(!$id == ""){
                  if(!$course == ""){
                    if(!$year == ""){
                      $desc = "student_id -> {$id}";
                      $add = new Request($_POST["action"]."ed",$user,$desc,0,$sem);
                      $add->__newstudent($fname,$lname,$fullname,$course,$year,$sem,$email,$id);
                      echo $success2;
                      echo "Success";
                      return;
                    }  else {
                      echo $error2;
                      echo "Year Level can't be blank";return;
                    }
                  } else {
                    echo $error2;
                    echo "Course can't be blank";return;
                  }
                } else {
                  echo $error2;
                  echo "Student ID can't be blank";return;
                }
              }
          } else{
            echo $error2;
            echo "Last name can't be blank";return;
          }
        }else{
          echo $error2;
          echo "First name can't be blank";return;
        }

      } else {
        echo $alert2;
        echo "Student Already Exists!";return;
      }
    }
    if($_POST['action']=='collect'){
      $user = $_SESSION['account']['full_name'];
      $year = date("Y");
      $month = date("F");
      $date = date("F d, Y");
      $email = $_POST['receipt_email'];
      $update_email = $con->prepare("UPDATE ccs_students SET email = '$email' WHERE student_id={$_POST['student_id']}");
      if(!$update_email->execute()){
        exit;
      }
      if(empty($_POST['amount']) && (int)$_POST['amount']>0){
        echo $error3;
        echo "Invalid Amount";return;
      }
      $check = $con->prepare("SELECT * FROM psits_collections WHERE student_id = {$_POST['student_id']} AND event = {$_POST['event']}");
      $check->execute();
      if($check->fetch()>0 && $_POST['event']!=3){
        echo $alert3;
        echo $_POST['full_name']." has already paid the event.";
        return;}
      $sql = sprintf("INSERT INTO psits_collections(student_id,student_name,course,_year,event,payment,status,encoded_by,_date,month,year_)
      VALUES (%d,'%s','%s',%d,%d,%.2f,'%s','%s','%s', '%s', %d)", $_POST['student_id'], $_POST['full_name'],
      $_POST['course'],$_POST['year'],$_POST['event'],$_POST['amount'],"Paid",$user, $date, $month, $year);
      $query = $con->prepare($sql);
      $sql2 = sprintf("SELECT id FROM psits_collections WHERE student_id = %d AND event = %d AND _date = '%s' AND payment = %.2f", $_POST['student_id'], $_POST['event'], $date, $_POST['amount']);
      $latest_collection = $con->prepare($sql2);
      if($query->execute()){
        $latest_collection->execute();
        $latest_collection = $latest_collection->fetch()[0];
        $desc = "student_id -> {$_POST['student_id']}";
        $collect = new Request("collected ".$_POST['amount'],$_SESSION['account']['username'],$desc,$_POST['event'],1);
        echo $success3;
        echo "Sending receipt please wait...";

        $data = [
          "student"=>[
            "sid"=>$_POST['sid'],
            "name"=>$_POST['full_name'],
            "id"=>$_POST['student_id'],
            "course"=>$_POST['course'],
            "year"=>$_POST['year'],
            "email"=>getEmail((int)$_POST['student_id'])
          ],
          "user"=>[
            "name"=>$user,
            "position"=>$_SESSION['account']['position']
          ],
          "content"=>[
            "title"=>"We received your payment with thanks (STUDENT_ID {$_POST['student_id']} {$date})",
            "date"=>$date,
            "event"=>getEventName($_POST['event']),
            "amount"=>$_POST['amount'],
            "collection_id"=>$latest_collection
          ]
        ];
        if($_POST['event']==3){
          $data['size']=getTshirtSize($_POST['amount']);
        }
        sendToMail($data);
        ?>
        <script type="text/javascript">
          update();
        </script>
        <?php
      } else {
        echo $error3;
        echo "Server error";
      }
    }
  }
?>
