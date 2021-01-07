<?php
  require('../../config/config.php');
  $liquidated_expenses = $con->prepare("SELECT * FROM psits_expenses WHERE status = 1 AND user_id = {$_SESSION['account']['user-id']}");
  $liquidated_expenses->execute();
  $liquidated_expenses = $liquidated_expenses->fetchAll(PDO::FETCH_ASSOC);
 ?>
<div class="panel col-100">
  <div class="panel-header">
    Liquidated expenses
  </div>
  <div class="panel-body">
    <table>
      <thead>
        <tr>
          <th>Description</th>
          <th>Date of withdrawal</th>
          <th>Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!sizeof($liquidated_expenses)): ?>
          <td colspan="5">No records found. Please check your unliquidated expenses.</td>
        <?php endif; ?>
        <?php foreach ($liquidated_expenses as $key => $value): ?>
          <tr>
            <td><?php echo $value['title'] ?></td>
            <td><?php echo $value['_date'] ?></td>
            <td>
              <img src="img/peso.svg" alt="" width="10">
              <?php echo $value['received'] ?></td>
            <td>
              <a class="link-ajax" href="#fullreport" rel="modal:open" data-aja="expenses/fullreport.php?id=<?php echo $value['id'] ?>">Full report</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
$(".link-ajax").click(function() {
  event.preventDefault();
  link = $(this).attr("data-aja");
  $.ajax({
    url: link,
    method: "GET",
    beforeSend: function(){
      let loading = `  <div style="text-align:center;">
          <img src="/dashboard/img/spinner.svg" width="50" alt="">
        </div>`;
      $('#fullreport').html(loading)
    },
    success: function(data) {
      console.log(data);
      $('#fullreport').html(data);
    }
  })
})
</script>
