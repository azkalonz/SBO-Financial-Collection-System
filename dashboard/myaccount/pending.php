<?php
  require('../../config/config.php');
  $liquidated_expenses = $con->prepare("SELECT * FROM psits_expenses WHERE id IN (SELECT exp_id FROM pending_expenses) AND user_id = {$_SESSION['account']['user-id']}");
  $liquidated_expenses->execute();
  $liquidated_expenses = $liquidated_expenses->fetchAll(PDO::FETCH_ASSOC);
 ?>
<div class="panel col-100">
  <div class="panel-header">
    Pending expenses
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Description</th>
          <th>Date request</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!sizeof($liquidated_expenses)): ?>
          <td colspan="5">No records found. Please check your unliquidated expenses.</td>
        <?php endif; ?>
        <?php foreach ($liquidated_expenses as $key => $value): ?>
          <?php
            $date1 = date_create(date('Y-m-d',strtotime($value['_date'])));
            $date2 = date_create(date('Y-m-d'));
            $diff = date_diff($date1,$date2);
            $dl = $diff->format("%a");
           ?>
          <tr>
            <td><?php echo $value['title'] ?></td>
            <td><?php echo $value['_date'] ?></td>
            <td>
              <img src="img/peso.svg" alt="" width="10">
              <?php echo $value['received'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
