<?php include('../../config/config.php') ?>
<?php include('../../api/func.php') ?>
<?php
  function getInfo($id){
    global $con;
    $query = $con->prepare("SELECT * FROM psits_liquidation WHERE exp_id = {$id}");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
  $info = getInfo($_GET['id']);
 ?>
<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
<table>
  <tr>
    <td class="table-title" colspan="100%">Expense Period <b id="exp-id"></b></td>
  </tr>
  <tr>
    <td>From</td>
    <td colspan="100%">
      <?php echo $info[0]['_date'] ?>
    </td>
  </tr>
  <tr>
    <td>To</td>
    <td colspan="100%" id="to">
      <?php echo $info[0]['_endDate'] ?>
    </td>
  </tr>
  <tr>
    <td class="table-title" colspan="100%">Liable Person</td>
  </tr>
  <tr>
    <td>Name</td>
    <td colspan="100%" id="person">
      <?php echo fullNameId($info[0]['user']) ?>
    </td>
  </tr>
  <tr>
    <td class="table-title" colspan="100%">Purpose of Expense</td>
  </tr>
  <tr>
    <td colspan="100%" id="purpose">
      <?php echo getExpensesInfo($info[0]['exp_id'])['title'] ?>
    </td>
  </tr>
  <tr>
    <td class="table-title" colspan="100%">Itemized Expenses</td>
  </tr>
  <tr>
    <td><b>Particular/Details</b></td>
    <td><b>Official Receipt</b></td>
    <td><b>Amount</b></td>
  </tr>
  <?php $total = 0; ?>
  <?php foreach ($info as $key => $value): ?>
    <?php $detail = explode(', ',$value['details']) ?>
    <?php $amount = explode(', ',$value['amount']) ?>
    <?php $receipt = explode(', ',$value['receipts']) ?>
      <?php for($i=0; $i<sizeof($amount); $i++): ?>
        <tr>
          <td><?php echo $detail[$i]; ?></td>
          <td><?php echo $receipt[$i]; ?></td>
          <td>
            <img src="/dashboard/img/peso.svg" width="12" alt="">
            <?php echo sprintf('%.2f',$amount[$i]); ?></td>
        </tr>
        <?php $total+=$amount[$i]; ?>
      <?php endfor; ?>
  <?php endforeach; ?>
  <tr>
    <td colspan="2">
      <b>Total spent</b>
    </td>
    <td>
      <img src="/dashboard/img/peso.svg" width="12" alt="">
      <?php echo sprintf('%.2f',$total); ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>Cash Received</b>
    </td>
    <td colspan="100%">
      <img src="/dashboard/img/peso.svg" width="12" alt="">
      <?php echo sprintf('%.2f',getExpensesInfo($info[0]['exp_id'])['received']) ?>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <b>Cash Left</b>
    </td>
    <td colspan="100%">
      <img src="/dashboard/img/peso.svg" width="12" alt="">
      <?php $left = getExpensesInfo($info[0]['exp_id'])['received']-getExpensesInfo($info[0]['exp_id'])['amount']; ?>
      <?php echo sprintf('%.2f',$left) ?>
    </td>
  </tr>
</table>
