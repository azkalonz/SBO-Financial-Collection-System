<?php
  require('../../config/config.php');
  require('../../api/func.php');
  $liquidated_expenses = $con->prepare("SELECT * FROM notifications WHERE exp_id IN(SELECT id FROM psits_expenses WHERE status = 0) AND receiver_id = {$_SESSION['account']['user-id']} GROUP BY exp_id ");
  $liquidated_expenses->execute();
  $liquidated_expenses = $liquidated_expenses->fetchAll(PDO::FETCH_ASSOC);
 ?>
<div class="panel col-100">
  <div class="panel-header">
    Notifications
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Date</th>
          <th>Due</th>
          <th>Notified by</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!sizeof($liquidated_expenses)): ?>
          <td colspan="5">No records found.</td>
        <?php endif; ?>
        <?php foreach ($liquidated_expenses as $key => $value): ?>
          <?php
            $expenses = $con->prepare("SELECT _date FROM psits_expenses WHERE id = {$value['exp_id']}");
            $expenses->execute();
            $expenses = $expenses->fetch()[0];

            $date1 = date_create(date('Y-m-d',strtotime($expenses)));
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1,$date2);
            $dl = $diff->format("%a");
           ?>
          <tr>
            <td><?php echo $value['title'] ?></td>
            <td><?php echo $value['_date'] ?></td>
            <td><?php echo 7-$dl; ?> Days left</td>
            <td><?php echo fullNameId($value['sender_id']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
