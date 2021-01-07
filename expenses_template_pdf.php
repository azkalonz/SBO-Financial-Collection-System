<?php include('config/config.php') ?>
<?php include('api/func.php') ?>
<?php
  $event = $con->prepare("SELECT * FROM psits_liquidation WHERE event_id = {$_GET['event']} ORDER BY _endDate DESC");
  $event->execute();
  $events = $event->fetchAll(PDO::FETCH_ASSOC);
  $event_name = getEventName($_GET['event']);

  $year_sort = array();
  $tfoot_total_amount = 0;
  $tfoot_total_received = 0;
  $tfoot_total_left = 0;

  // Initialize years $year_sort[2019],$year_sort[2018]...
  foreach ($events as $key => $value) {
    $year_sort[explode(', ',$value['_date'])[1]] = [];
  }
  // Filter liquidations by year
  foreach ($year_sort as $year => $date) {
    foreach ($events as $key => $value) {
      if($year == explode(', ',$value['_date'])[1]){
        array_push($year_sort[$year], $value);
      }
    }
  }
  foreach ($year_sort as $year => $date) {
    $merge = false;
    $same = 1;
    foreach ($year_sort[$year] as $key => $value) {
      if(!isset($prev)){
        $prev = $value['_date'];
      } else {
        if($prev==$value['_date']){
          $same++;
          $year_sort[$year][$key]['rowspan'] = $same;
        } else {
          $same = 1;
          $merge = true;
        }
      }
      $prev = $value['_date'];
    }
  }

  foreach ($year_sort as $year => $date) {
    foreach ($year_sort[$year] as $key => $value) {
      if(isset($year_sort[$year][$key]['rowspan'])){
        $year_sort[$year][$key-($year_sort[$year][$key]['rowspan']-1)]['rowspan'] = $year_sort[$year][$key]['rowspan'];
        $year_sort[$year][$key]['rowspan'] = 1;
      }
    }
  }
  ?>
  <?php if (!$year_sort): ?>
    <h1>No record found</h1>
  <?php endif; ?>
 <style type="text/css">
  table {
    margin: 0;padding:0;
  }
  table tr td {
    padding: 10px;
  }
  h1,h2,h3,h4,h5,h6 {
    margin: 0;
  }
  table tr td table {
    border-collapse: collapse;
  }
  table tr td table tr td {
    border: 1px solid;
    border-left:0;border-top:0;border-right:0;
    width: 100px;
    padding: 0;
  }
  .red td {
    background-color: #f5cfcf;
  }
  td.red {
    background-color: #f5cfcf;
  }
</style>
<!-- <pre>
  <?php print_r($year_sort) ?>
</pre> -->
<page>
  <table border="1" style="border-collapse: collapse; width: 100%;">
    <tbody>
      <tr>
        <td rowspan="3">
          <img src="<?php echo $images['logo'] ?>" width="100" alt="">
        </td>
        <td colspan="6" class="table-title" align="center">
          <?php echo $department['university'] ?>
        </td>
      </tr>
      <tr>
        <td colspan="6" class="table-title" align="center">
          <?php echo $department['college'] ?>
        </td>
      </tr>
      <tr>
        <td colspan="6" class="table-title" align="center">
          <?php echo $labels['text-logo'] ?> FINANCIAL STATEMENT
        </td>
      </tr>
      <tr>
        <td colspan="6" class="table-title" align="center">
          &nbsp;
        </td>
      </tr>
      <tr>
        <td colspan="6" class="table-title" align="center">
          <b><h2><?php echo $event_name  ?> Expenses Summary</h2></b>
        </td>
      </tr>
      <tr class="red">
        <td>Date</td>
        <td>Date</td>
        <td>Total Amount</td>
        <td>Cash Received</td>
        <td>Money Left</td>
        <td>Receipt Reference</td>
      </tr>

      <?php foreach ($year_sort as $year => $date): ?>
        <tr>
          <td colspan="6" style="background-color: #d0d0d0;">
            <h1>
            <?php echo $year ?>
          </h1></td>
        </tr>
        <?php foreach ($year_sort[$year] as $key => $value): ?>
          <?php
          $startDate = date('F d',strtotime($value['_date']));
          $endDate = $value['_date']!=$value['_endDate']?' - '.date('F d',strtotime($value['_endDate'])):'';
           ?>
          <tr>
            <?php if (isset($value['rowspan'])): ?>
              <?php if ($value['rowspan']!=1): ?>
                <td rowspan="<?php echo $value['rowspan'] ?>">
                  <?php echo $startDate.$endDate ?>
                </td>
              <?php endif; ?>
              <?php else: ?>
                <td>
                  <?php echo $startDate.$endDate ?>
                </td>
            <?php endif; ?>
          <?php
          $items = explode(', ',$value['details']);
          $receipts = explode(', ',$value['receipts']);
          $amounts = explode(', ',$value['amount']);
           ?>

         <td style="padding: 0;">
           <?php for($i=0; $i<sizeof($items); $i++): ?>
               <table>
                 <tr>
                   <col width="130">
                     <td class="red" style="padding: 10px;"><?php echo $items[$i] ?></td>
                   </col>
                 </tr>
               </table>
           <?php endfor; ?>
         </td>

         <td style="padding: 0;">
           <?php for($i=0; $i<sizeof($items); $i++): ?>
               <table>
                 <tr>
                   <col width="100">
                     <td class="red" style="padding: 10px;"><?php echo sprintf('%.2f',$amounts[$i]) ?></td>
                   </col>
                 </tr>
               </table>
               <?php $tfoot_total_amount+=(float)$amounts[$i] ?>
           <?php endfor; ?>
         </td>



           <td class="red">
              <?php echo sprintf('%.2f',getRecevied($value['exp_id'])) ?>
              <?php $tfoot_total_received+=(float)getRecevied($value['exp_id']) ?>
           </td>

           <td class="red">
             <?php echo sprintf('%.2f',getMoneyLeft2($value['exp_id'])) ?>
             <?php $tfoot_total_left+=getMoneyLeft2($value['exp_id']) ?>
           </td>


         <td style="padding: 0;">
           <?php for($i=0; $i<sizeof($items); $i++): ?>
               <table>
                 <tr>
                   <col width="100">
                     <td style="padding: 10px;"><?php echo $receipts[$i] ?></td>
                   </col>
                 </tr>
               </table>
           <?php endfor; ?>
         </td>

         </tr>
        <?php endforeach; ?>

      <?php endforeach; ?>
      <tr>
        <td><b>Total</b></td>
        <td></td>
        <td>
          <?php echo sprintf('%.2f',$tfoot_total_amount) ?>
        </td>
        <td>
          <?php echo sprintf('%.2f',$tfoot_total_received) ?>
        </td>
        <td>
          <?php echo sprintf('%.2f',$tfoot_total_left) ?>
        </td>
        <td></td>
      </tr>
    </tbody>
  </table>
</page>
