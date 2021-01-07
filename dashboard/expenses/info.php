<?php if(!isset($_GET["id"])){header("../../index.php");} ?>
<?php
  include("../../config/config.php");
  include("../../api/func.php");
  $id = $_GET["id"];
  $event = $_GET['event'];
  $sem = $_GET['sem'];?>
<?php
  $exp = $con->prepare("SELECT * FROM psits_expenses WHERE user_id = {$id}");
  $exp->execute();
  $exp = $exp->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript">
$(document).ready( function () {
  $('#info').DataTable();
});
</script>

<div class="panel">
  <div class="panel-header">
    <div class="flex justify-space-between align-center" style="padding: 0 10px;">
      <div class="flex">
        <a onclick="goBack()">
          <i class='fa fa-arrow-circle-left' style='color: #fff;cursor:pointer;font-size: 1.5rem;'></i>
        </a>
        <div class="">
          <h3><?php echo fullNameId($id)?><a style="text-transform: lowercase;">'s</a></h3>
          <?php if ($_GET['stat']): ?>
            <span>Liquidated expenses</span>
            <?php else: ?>
              <span>Unliquidated expenses</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="panel-body">
    <table id="info">
      <thead>
        <tr>
          <th>Description</th>
          <th>Date of withdrawal</th>
          <th>Amount received</th>
          <th>Action</th>
        </tr>
      </thead>
      <?php foreach ($exp as $key => $value): ?>
        <?php if ($value["status"]==(int)$_GET['stat']): ?>
          <tr>
            <td><?php echo $value["title"] ?></td>
            <td><?php echo $value["_date"] ?></td>
            <td>
              <img src="img/peso.svg" alt="" width="10">
              <?php echo sprintf("%.2f",$value["received"]) ?></td>
              <?php if($_GET['stat']==0): ?>
                <td>
                  <a class="ajax-link" href="expenses/action.php" user-id="<?php echo $value["user_id"] ?>" expense-id="<?php echo $value["id"] ?>">Request Report</a>
                </td>
              <?php else: ?>
                <td>
                  <a class="link-ajax" href="#fullreport" rel="modal:open" data-aja="expenses/fullreport.php?id=<?php echo $value['id'] ?>">Full report</a>
                </td>
              <?php endif; ?>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<script type="text/javascript">
    $(".ajax-link").click(function() {
      event.preventDefault();
      link = $(this).attr("href");
      that = $(this);
      $.ajax({
        url: link+"?notify&user-id="+$(this).attr("user-id")+"&exp-id="+$(this).attr("expense-id")+"&id="+$(this).attr("user-id"),
        method: "GET",
        success: function(data) {
          console.log(data);
          that.html(data);
        }
      })
    })
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
    goBack = function(){
      $("#disp").load("expenses/event.php?action="+"<?php echo $event?>"+"&sem="+"<?php echo $sem ?>");
    }
    Unliquidated = function(){
    <?php if($_GET['stat']==1): ?>
      let stat = 0;
      <?php else: ?>
      let stat = 1;
      <?php endif; ?>
      $.ajax({
        method: 'GET',
        url: "expenses/info.php"+"?id=<?php echo $_GET['id'] ?>&event=<?php echo $_GET['event'] ?>&sem=<?php echo $_GET['sem'] ?>&stat="+stat,
        success: function(data){
          $('#disp').html(data);
        }
      })
    }
</script>
