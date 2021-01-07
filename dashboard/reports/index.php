<?php
  include '../../config/config.php';
  include '../../api/func.php';
  secure(['admin-only'=>true]);
  $events = $con->prepare('SELECT * FROM psits_events');
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);

  foreach ($events as $key => $value) {
    $a[$value['name']]['r']=-1;
    $q = $con->prepare("SELECT COUNT(id) FROM psits_collections WHERE event = {$value['id']}");
    $q->execute();
    $a[$value['name']]['c'] = $q->fetch()[0];

    $q = $con->prepare("SELECT _date FROM psits_collections WHERE event = {$value['id']} ORDER BY id DESC LIMIT 1");
    $q->execute();
    $a[$value['name']]['s'] = $q->fetch()[0];

    $q = $con->prepare("SELECT _date FROM psits_logs WHERE action = 'registered' AND event_id = {$value['id']} ORDER BY id DESC LIMIT 1");
    $q->execute();
    $a[$value['name']]['l'] = $q->fetch()[0];

    foreach (explode(', ',$value['student']) as $v) {
      $a[$value['name']]['r']++;
    }
  }
?>
<div class="panel">
  <div class="panel-header">
    Registered students
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Event</th>
          <th>Last modified</th>
          <th>No. of students</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $key => $value): ?>
          <tr>
            <td style="max-width: 120px; text-overflow:ellipsis;overflow:hidden;"><?php echo $value['name'] ?></td>
            <td><?php echo $a[$value['name']]['l'] ?></td>
            <td><?php echo $a[$value['name']]['r'] ?></td>
            <td>
              <a href="/reports_pdf.php?reg&course=BSIT&id=<?php echo $value['id'] ?>&name=<?php echo getEventName($value['id'])?>" target="_blank">
                <i class="fal fa-print"></i>
                Print</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<br>
<hr>
<div class="panel">
  <div class="panel-header">
    Collected fees
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Event</th>
          <th>Last modified</th>
          <th>No. of collections</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($events as $key => $value): ?>
          <tr>
            <td style="max-width: 120px; text-overflow:ellipsis;overflow:hidden;"><?php echo $value['name'] ?></td>
            <td><?php echo $a[$value['name']]['s'] ?></td>
            <td><?php echo $a[$value['name']]['c'] ?></td>
            <td>
              <a href="/collection_excel.php?event=<?php echo $value['id'] ?>&name=<?php echo getEventName($value['id'])?>" target="_blank">
                <i class="fal fa-print"></i>
                Print</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
