<?php
  require("../config/config.php");
  include("../api/func.php");
  secure(['admin-only'=>true]);
  $student = allRegStudent();
  $total = totalStudentAll();
  $events = $con->prepare("SELECT * FROM psits_events");
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);

  $reg = $con->prepare("SELECT student FROM psits_events");
  $reg->execute();
  $reg = $reg->fetchAll(PDO::FETCH_ASSOC);

  $total_fund = 0;
 ?>
  <style media="screen">
    #graph-container {
    min-width: 320px;
    max-width: auto;
    width: 100%;
    height: inherit;
    border-radius: 8px;
    margin: 0 auto;
    }
  </style>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#Allevents').DataTable({
        searching: false,
        lengthChange: false
      })
    });
  </script>
    <div id="disp">
    <div class="wrap flex justify-space-between col-100" style="position: relative;">
      <!-- <div class="sm-container cont graph">
        <div id="graph-container"></div>
        <button id="plain">Plain</button>
        <button id="inverted">Inverted</button>
        <button id="polar">Polar</button>
      </div> -->

    </div>
    <div class="wrap flex col-100">
      <div class="panel col-100">
        <div class="panel-header flex justify-space-between col-100">
          <div class="">
            Overview
          </div>
          <div style="max-width:500px;">
            <span><i class="fal fa-info-circle"></i>
            Right click table row to open the context menu.</span>
          </div>
        </div>
        <div class="panel-body">
          <!-- <div class="event-list col-100 cont"> -->
            <table id="Allevents" style="transform: translateX(-1px) translateY(-1px);">
              <thead>
                <tr>

                </tr>
                <tr>
                  <th>Event</th>
                  <th>Latest Collection</th>
                  <th>Modified</th>
                  <th>Payment Amount</th>
                  <th>No. of Students</th>
                  <th>Collected</th>
                  <th>Expenses</th>
                  <th>Total (onhand)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($events as $key => $value): ?>
                  <?php $total_report = 0;$total_amount = 0;$total_cash = 0; ?>
                  <?php $expense = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND event_id = {$value['id']}");
                    $expense->execute();?>
                  <?php foreach ($expense->fetchAll(PDO::FETCH_ASSOC) as $key => $value2): ?>
                    <?php $status = $value2['status'] ?>
                    <?php if($status==0){
                      $total_amount+=$value2['received'];
                    } else {
                      $total_amount+=$value2['amount'];
                    } ?>
                    <?php $total_report++; $total_cash+=$value2['received']; ?>
                  <?php endforeach; ?>
                  <?php
                    $h = ((int)explode(':',$value['startTime'])[0]);
                    $h = $h>9?$h:'0'.$h;
                     ?>
                  <?php $m = explode(':',$value['startTime'])[1]?>
                  <tr action="expenses" s-price="<?php echo $value['s_price']?'true':'false'; ?>" event-name="<?php echo $value['name'] ?>" event-id="<?php echo $value['id'] ?>" amount="<?php echo $value['price'] ?>" date="<?php echo date('Y-m-d',strtotime( $value['date'])) ?>" rate="<?php echo $value['sale'] ?>" start-time="<?php echo $h.':'.$m; ?>" allday="<?php echo $value['allDay']?>" theme="<?php echo $value['theme_color']?>" reg-btn="<?php echo $value['registration']?>">
                    <td style="max-width: 200px;">
                      <a class="ajax-link" href="collection/?id=<?php echo $value['id'] ?>" destination="<?php echo $value["id"] ?>" sem="<?php echo $value["sem"] ?>">
                        <?php echo $value["name"] ?>
                      </a>
                    </td>
                    <td><?php echo latestCollection($value['id']) ?></td>
                    <td><?php echo $value['modified'] ?>
                    <br>
                    <a style="opacity: 0.5;font-size: .7rem;"><?php echo fullName($value['_by']) ?></a>
                    </td>
                    <td>
                      <img src="img/peso.svg" alt="" width="10">
                      <?php echo $value["price"] ?></td>
                    <td><?php
                    echo getTotalStudents($value['id'])
                     ?></td>
                     <td>
                       <img src="img/peso.svg" alt="" width="10">
                       <?php echo sprintf("%.2f",getFund($value['id'])) ?></td>
                     <td>
                       <img src="img/peso.svg" alt="" width="10">
                       <?php echo sprintf("%.2f",getExpenses($value['id'])) ?></td>
                    <td>
                      <img src="img/peso.svg" alt="" width="10">
                      <?php $money_left = $total_amount>0?$total_cash-$total_amount:$total_amount ?>
                      <?php echo sprintf("%.2f",(getFund($value['id'])-$total_cash)+($money_left)) ?></td>
                  </tr>
                  <?php $total_fund+=(getFund($value['id'])-$total_cash)+($money_left) ?>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="7" align="right"><b>Total</b></td>
                  <td>
                    <img src="img/peso.svg" alt="" width="10">
                    <?php echo sprintf("%.2f",$total_fund); ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        <!-- </div> -->
      </div>
      <!-- <button type="button" name="button" style="background: #00aefc" onclick="$('.tab[link]')[2].click()">EXPENSE SUMMARY <i class="fa fa-arrow-right"></i></button> -->
    </div>
  </div>

  <script type="text/javascript">

  $(".ajax-link").click(function() {
    event.preventDefault();
    url = $(this).attr("href");
    $.ajax({
      method: "GET",
      url: url,
      beforeSend: function(){
        let loading = `<div class="col-100 flex align-center justify-center" style="background: #fff;">            <img src="img/spinner.svg" width="50"></div>`
        $('#disp').html(loading);
      },
      success: function(data) {
        html = $('#disp').html();
        $("#disp").html("<a id='goback'><i class='fa fa-arrow-circle-left' style='cursor:pointer;color: #222;margin: 10px; font-size: 2.5rem;'></i></a>"+data);
        $('#goback').off();
        $('#goback').on('click',function(){
          let loading = `<div class="col-100 flex align-center justify-center" style="background: #fff;">            <img src="img/spinner.svg" width="50"></div>`
          $('#disp').html(loading);
          $('#disp').load('overview.php');
        })
      }
    })
  })

  events = [
    <?php foreach($events as $val){ ?>
      "<?php echo $val["name"] ?>",
    <?php } ?>
  ];

  // registered = [
  //   <?php foreach($reg as $val){ ?>
  //     <?php $c = explode(",",$val["student"]);?>
  //     <?php echo (int)sizeof($c)-1 ?>,
  //   <?php } ?>
  // ];
  registered = [
    <?php foreach($events as $key => $val){ ?>
      <?php $total_report = 0;$total_left = 0;$total_cash = 0; ?>
      <?php $expense = $con->prepare("SELECT * FROM psits_expenses WHERE id NOT IN (SELECT exp_id FROM pending_expenses) AND event_id = {$val['id']} AND status = 1");
        $expense->execute();?>
      <?php foreach ($expense->fetchAll(PDO::FETCH_ASSOC) as $key => $value2): ?>
        <?php $total_report++; $total_left+=($value2['received']-$value2['amount']); $total_cash+=$value2['received']; ?>
      <?php endforeach; ?>
      <?php echo (getFund($val['id'])-$total_cash)+$total_left ?>,
    <?php } ?>
  ];
  var chart = Highcharts.chart('graph-container', {

      title: {
          text: 'PSITS EVENT FUNDS Chart'
      },

      subtitle: {
          text: 'Polar'
      },

      xAxis: {
          categories: events
      },
      chart: {
          inverted: false,
          polar: true,
          plain: false
      },
      series: [{
          type: 'column',
          colorByPoint: true,
          data: registered,
          showInLegend: false
      }]

  });

  $('#plain').click(function () {
      chart.update({
          chart: {
              inverted: false,
              polar: false
          },
          subtitle: {
              text: 'Plain'
          }
      });
  });
  $('#inverted').click(function () {
      chart.update({
          chart: {
              inverted: true,
              polar: false
          },
          subtitle: {
              text: 'Inverted'
          }
      });
  });
  $('#polar').click(function () {
      chart.update({
          chart: {
              inverted: false,
              polar: true
          },
          subtitle: {
              text: 'Polar'
          }
      });
  });
  </script>
