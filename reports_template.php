<?php
  include('config/config.php');
  include('api/func.php');

  $event_name = getEventName($_GET['id']);
  $dates = [];
  $payment_total=0;
  $act = isset($_GET['reg'])?'Registration':'Collection';

  if(isset($_GET['reg'])){
    $event = $con->prepare("SELECT * FROM psits_events WHERE id = {$_GET['id']}");
    $event->execute();
    $event = $event->fetchAll(PDO::FETCH_ASSOC);
    $students = substr($event[0]['student'],2);
    $students = explode(', ',$students);
    $total = sizeof($students);

    foreach ($students as $key => $value) {
      // if(!($total-1)){
      //   $error = true;
      //   break;
      // }
      $id = explode('->',$value)[0];
      $reg = fullName(explode('->',$value)[1]);
      $q = $con->prepare("SELECT _date FROM psits_logs WHERE description LIKE '%$id%' AND event_id = {$_GET['id']} AND action = 'registered'");
      $q->execute();
      $date = $q->fetch()[0];

      $q = $con->prepare("SELECT * FROM ccs_students WHERE student_id = {$id} LIMIT 1");
      $q->execute();
      $info = $q->fetchAll(PDO::FETCH_ASSOC)[0];

      $s[$key]['id'] = $id;
      $s[$key]['reg'] = $reg;
      $s[$key]['date'] = $date;
      $s[$key]['s'] = $info;
      array_push($dates,$date);
    }
  }
  else {
    $event = $con->prepare("SELECT * FROM psits_collections WHERE event = {$_GET['id']}");
    $event->execute();
    $event = $event->fetchAll(PDO::FETCH_ASSOC);
    $total = sizeof($event);
    if(!($total)){
      $error = true;
    }
    foreach ($event as $key => $value) {
      $id = $value['student_id'];
      $reg = $value['encoded_by'];
      $date = $value['_date'];

      $q = $con->prepare("SELECT * FROM ccs_students WHERE student_id = {$id} LIMIT 1");
      $q->execute();
      $info = $q->fetchAll(PDO::FETCH_ASSOC)[0];

      if (!isset($_GET['reg'])) {
        $q = $con->prepare("SELECT payment FROM psits_collections WHERE student_id = {$id} AND event = {$_GET['id']}");
        $q->execute();
        $payment = $q->fetch()[0];
      }
      $payment_total+=$payment;
      $s[$key]['id'] = $id;
      $s[$key]['reg'] = $reg;
      $s[$key]['date'] = $date;
      $s[$key]['payment'] = $payment;
      $s[$key]['s'] = $info;
      array_push($dates,$date);
    }
  }
  rsort($dates);
  foreach ($dates as $key => $value) {
    foreach ($s as $key2 => $value2) {
      if($s[$key2]['date']==$value){
        $dates2[$value][$key]=$s[$key];
      }
    }
  }
  if(isset($error)){
    echo '<h1>No records found</h1>';
    echo '<br><a href="javascript:window.close()">Close window</a>';
    exit;
  }
?>
 <style type="text/css">
  table {
    margin: 0;padding:0;
  }
  table tr td {
    width: 108px;
    padding: 10px;
  }
  h1,h2,h3,h4,h5,h6 {
    margin: 0;
  }
  table tr td table {
    border-collapse: collapse;
  }
  .red td {
    background-color: #f5cfcf;
  }
  td.red {
    background-color: #f5cfcf;
  }
</style>
<page>
  <table border="1" style="border-collapse: collapse; width: 100%;">
    <tbody>
      <tr>
        <td rowspan="3">
          <img src="<?php echo $images['logo'] ?>" width="100" alt="">
        </td>
        <td colspan="5" class="table-title" align="center">
          <?php echo $department['university'] ?>
        </td>
      </tr>
      <tr>
        <td colspan="5" class="table-title" align="center">
          <?php echo $department['college'] ?>
        </td>
      </tr>
      <tr>
        <td colspan="5" class="table-title" align="center" style="text-transform: uppercase;">
          <?php echo $labels['text-logo'] ?> <?php echo $act; ?> REPORT
        </td>
      </tr>
      <tr>
        <td colspan="5" class="table-title" align="center">
          <h1>
            <?php echo $event_name ?> (1:00PM - 3:30PM)
          </h1>
          <h3>
            Attendance Sheet
          </h3>
          &nbsp;
        </td>
      </tr>
      <tr>
        <td colspan="5" class="table-title" align="center" style="padding-bottom:-200px;">
          <h2><?php echo $event_name  ?> <?php echo $act; ?> Report</h2>
        </td>
      </tr>
      <tr class="red" style="font-weight: bold;">
        <td>Student ID</td>
        <?php if (isset($_GET['reg'])): ?>
          <td>Last name</td>
          <td>First name</td>
          <?php else:?>
            <td>Full name</td>
            <td>Encoded by</td>
        <?php endif; ?>
        <td>
          <?php echo isset($_GET['reg'])?'Course':'Amount' ?>
        </td>
        <td>Year</td>
      </tr>
      <?php foreach ($dates2 as $key => $value): ?>
        <?php $month_total = 0;
        $month_total_payment = 0; ?>
        <tr class="black">
          <td colspan="5" style="background: #383838;color:#fff;">
            <h3><?php echo $key ?></h3>
          </td>
        </tr>
        <?php foreach ($value as $key2 => $value2): ?>
          <?php if ($value2['s']['course']!=$_GET['course']): ?>
            <?php continue; ?>
          <?php endif; ?>
          <?php $month_total++; ?>
          <?php if (!isset($_GET['reg'])): ?>
            <?php $month_total_payment+=$value2['payment']; ?>
          <?php endif; ?>
          <tr>
            <td><?php echo $value2['id'] ?></td>
            <?php if (isset($_GET['reg'])): ?>
              <td><?php echo $value2['s']['last_name'] ?></td>
              <td><?php echo $value2['s']['first_name'] ?></td>
              <td><?php echo $value2['reg']!=null?$value2['s']['course']:$value2['s']['full_name'] ?></td>
              <td><?php echo $value2['reg']!=null?$value2['s']['_year']:$value2['s']['full_name'] ?></td>
            <?php else:?>
              <td><?php echo $value2['s']['full_name'] ?></td>
              <td><?php echo $value2['reg']!=null?$value2['reg']:$value2['s']['full_name'] ?></td>
              <td>
                <?php echo $value2['payment'] ?>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
        <tr>
          <?php if (isset($_GET['reg'])): ?>
            <!-- <td colspan="4" align="right"><b><?php echo $key ?> total</b></td>
            <td><?php echo $month_total ?></td> -->
          <?php else: ?>
            <td colspan="4" align="right"><b><?php echo $key ?> total</b></td>
            <td><?php echo $month_total_payment ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      <tr>
        <?php if (isset($_GET['reg'])): ?>
          <td colspan="4" align="right"><b>TOTAL STUDENTS</b></td>
          <td><?php echo $month_total ?></td>
        <?php else: ?>
          <td colspan="4" align="right"><b>TOTAL</b></td>
          <td><?php echo $payment_total ?></td>
        <?php endif; ?>
      </tr>
    </tbody>
  </table>
</page>
