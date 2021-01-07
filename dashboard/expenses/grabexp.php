<?php
  include('../../config/config.php');
  include('../../api/func.php');
 ?>
 <script type="text/javascript">
   $('#expense-summary').DataTable({
     lengthChange: false,
     searching: false
   })
 </script>
<?php if($_SESSION['account']['access']==40): ?>

  <?php   $event = $con->prepare("SELECT * FROM psits_events");
    $event->execute();
    $event = $event->fetchAll(PDO::FETCH_ASSOC); ?>
  <div class="wrap flex flex-wrap">

    <div class="col-100">
      <div id="disp" class="col-100">
      </div>

    <div class="panel">
      <div class="panel-header">
        Summary
      </div>
      <div class="panel-body">
        <table id="expense-summary">
          <thead>
            <tr>
              <th>EVENT</th>
              <th>REPORTS</th>
              <th>MONEY SPENT</th>
              <th>CASH RECEIVED</th>
              <th>MONEY LEFT</th>
              <th>COLLECTION</th>
              <th>MONEY ON HAND</th>
            </tr>
          </thead>
          <tbody>
            <?php $rep=0;$tot=0;$cas=0;$onhand=0;$totalfund=0; ?>
            <?php foreach ($event as $key => $value): ?>
              <?php $l = 0;$u = 0;$total_report = 0;$total_amount = 0;$total_cash = 0; ?>
              <?php $expense = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND event_id = {$value['id']}");
                $expense->execute();?>
              <?php foreach ($expense->fetchAll(PDO::FETCH_ASSOC) as $key => $value2): ?>
                <?php if ($value2['status']==1): ?>
                  <?php $l++; ?>
                  <?php else: ?>
                  <?php $u++; ?>
                <?php endif; ?>
                <?php $total_report++; $total_amount+=$value2['amount']; $total_cash+=$value2['received']; ?>
              <?php endforeach; ?>
            <tr>
              <td style="max-width: 220px;text-overflow:ellipsis;overflow:hidden;">
                <a href="expenses/event.php" class="ajax-link2" cid="<?php echo $value['id'] ?>" sem="<?php echo $value['sem'] ?>">
                <?php echo $value['name'] ?></td></a>
              <td>
                <table class="normalize">
                  <tr>
                    <td style="color: green;"><?php echo $l ?></td>
                    <td style="color: red;"><?php echo $u ?></td>
                  </tr>
                </table>
              </td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$total_amount) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$total_cash) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php $money_left = getMoneyLeft($value['id']) ?>
                <?php echo sprintf("%.2f",$money_left) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo getFund($value['id']); ?>
                <?php $totalfund+=getFund($value['id']); ?>
              </td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php
                $currentFund = ((getFund($value['id'])-$total_cash))+$money_left;
                echo sprintf("%.2f",$currentFund); ?></td>
              <?php
              // (double)$new_fund = (double)$value['funds']-(double)$total_cash;
              // $update_fund = $con->prepare("UPDATE psits_events SET onhand = {$new_fund} WHERE id = {$value['id']}");
              // $update_fund->execute();
               ?>
            </tr>
            <?php $rep+=$total_report;$tot+=$total_amount;$cas+=$total_cash;$onhand+=$currentFund; ?>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td><b>Total</b></td>
              <td><?php echo $rep ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$tot) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$cas) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$tot>0?$cas-$tot:0) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$totalfund) ?></td>
              <td>
                <img src="img/peso.svg" alt="" width="10">
                <?php echo sprintf("%.2f",$onhand); ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    </div>

  </div>
  <div class="flex justify-end" style="position: fixed; bottom: 20px;right:10px;">
      <button class="blue" type="button" onclick="options('#options')">
        Menu
      </button>
      <div id="options" style="position:absolute;background:#fafafa;box-shadow:0 6.7px 9.4px rgba(0,0,0,0.1);color:#222;bottom:36px;width:200px;font-size:.9rem;display:none;">
        <ul style="list-style:none;margin:0!important;padding:0;width:100%;text-transform:none;">
          <li style="padding:0;">
            <a id="cashout" href="#" class="option">
              <span>CASH OUT</span>
            </a>
          </li>
          <?php if ($_SESSION['account']['access']==40): ?>
            <li>
              <a id="pending-expenses" href="#" class="option">
              PENDING WITHDRAWALS</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
  </div>
<?php endif; ?>
