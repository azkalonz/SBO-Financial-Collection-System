<?php if(!isset($_GET["action"])){header("location: ../index.php");} ?>
<?php
  include("../../config/config.php");
  include("../../api/func.php");
  $id = $_GET["action"];
  $sem = $_GET["sem"];
  $exp = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND event_id = {$id} AND sem = {$sem}");
  $exp->execute();
  $exp = $exp->fetchAll(PDO::FETCH_ASSOC);
 ?>
 <div id="container">
   <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
   <script type="text/javascript">
   $(document).ready( function () {
     $('#exp').DataTable();
   });
   </script>
   <div class="panel">
     <div class="panel-header">
       <div class="flex justify-space-between align-items-center">
         <b>
           <?php echo getEventName($id) ?> Expenses Summary
         </b>
         <div style="position:relative;">
         <button class="green" type="button" onclick="options('#dl-option')">
           Export
         </button>
         <div id="dl-option" style="position:absolute;background:#fafafa;box-shadow:0 6.7px 9.4px rgba(0,0,0,0.3);color:#222;top:36px;width:100%;font-size:.9rem;display:none;z-index:1;">
           <ul style="list-style:none;margin:0!important;padding:0;width:100%;text-transform:none;">
             <li style="padding:0;">
               <a href="/expense_print.php?event=<?php echo $id ?>" class="option">
               Excel</a>
             </li>
             <li>
               <a href="/pdf_print_expenses.php?event=<?php echo $id ?>" target="_blank" class="option">
               PDF</a>
             </li>
           </ul>
         </div>
         </div>
       </div>
     </div>
     <div class="panel-body">
       <table id="exp">
         <thead>
           <tr>
             <th>Person</th>
             <th>Description</th>
             <th>Withdrawal date</th>
             <th>Liquidation Date</th>
             <th>Sem</th>
             <th>Spent</th>
             <th>Received</th>
             <th>Money Left</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach ($exp as $key => $value): ?>
             <tr <?php echo $value["amount"]!=0?'class="link-ajax"':''; ?> data-aja="expenses/fullreport.php?id=<?php echo $value['id'] ?>" style="cursor:pointer;">
               <td>
                 <a href="expenses/info.php" class="ajax-link" student-id="<?php echo $value["user_id"] ?>" event="<?php echo $value["event_id"] ?>" sem="<?php echo $value['sem'] ?>">
                 <?php echo $value["user"] ?></a>
               </td>
               <td><?php echo $value["title"] ?></td>
               <td><?php echo $value["_month"]." ".$value["_day"].", ".$value["_year"] ?></td>
               <td><?php echo getLiquidationDate($value['id']) ?></td>
               <td><?php echo $value["sem"] ?></td>
               <td>
                 <img src="img/peso.svg" alt="" width="10">
                 <?php echo $value["amount"]!=0?sprintf('%.2f',$value["amount"]):"<a style='color: red;'>Pending</a>" ?></td>
               <td>
                 <img src="img/peso.svg" alt="" width="10">
                 <?php echo sprintf("%.2f",$value["received"]) ?></td>
               <td>
                 <img src="img/peso.svg" alt="" width="10">
                 <?php echo $value["amount"]!=0?sprintf('%.2f',$value["received"]-$value["amount"]):"<a style='color: red;'>Pending</a>" ?></td>
             </tr>
           <?php endforeach; ?>

         </tbody>
       </table>
     </div>
   </div>
 </div>
<script type="text/javascript">
    $(".link-ajax").click(function() {
      event.preventDefault();
      if(event.target.tagName=='A'){return;}
      $('#fullreport').modal('show');
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
    $ajxlinks = function(){
      $(".ajax-link").click(function() {
        event.preventDefault();
        link = $(this).attr("href");
        $.ajax({
          url: link+"?id="+$(this).attr("student-id")+"&event="+$(this).attr("event")+"&sem="+$(this).attr("sem")+"&stat="+1,
          method: "GET",
          success: function(data) {
            $("#container").html(data);
          }
        })
      })
    }
    $ajxlinks();
    downloadOption = function(){
      if($('#dl-option').css('display')=='none'){
        $('#dl-option').slideDown();
        return;
      }
      $('#dl-option').slideUp();
    }
    $('.paginate_button').click($ajxlinks);
</script>
