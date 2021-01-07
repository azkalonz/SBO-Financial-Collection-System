

<?php
  require("../config/config.php");
  require("../api/func.php");
  $events = $con->prepare("SELECT * FROM psits_events");
  $events->execute();
  $events = $events->fetchAll(PDO::FETCH_ASSOC);
  if(!isset($_GET['event'])){
    $event = 1;} else {$event = $_GET['event'];}
  if(!isset($_GET['course'])){
    $course = "all";} else {$course = $_GET['course'];}
  if(!isset($_GET['month'])){
    $month ="all";} else {$month = $_GET['month'];}
  if(!isset($_GET['year'])){
    $year = "all";} else {$year = $_GET['year'];}
  if(!isset($_GET['status'])){
    $status = "all";} else {$status = $_GET['status'];}
  $price = getPrice($event);

  $sql = sprintf("SELECT * FROM ccs_students WHERE course = '%s' GROUP BY student_id",$course);
  if($course == "all"){
    $sql = sprintf("SELECT * FROM ccs_students GROUP BY student_id");
  }
  $students = $con->prepare($sql);
  $students->execute();
  $students = $students->fetchAll(PDO::FETCH_ASSOC);
?>
<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<style media="screen">
#studtable_filter {display:none;}
  table#studtable tbody tr:hover td {
    background: rgba(0,0,0,0.1)!important;}
  #display_info,#display_paginate {
    position: relative!important;
    z-index: 1!important;}
.dataTables_length,.dataTables_filter {color: #fff!important;}
</style>
  <script type="text/javascript">
  dataCollection = null;
  $(document).ready( function () {
    dataCollection = $('#studtable').DataTable({
      lengthChange: false
    });
  });
  </script>
  <fieldset>
    <legend>Options</legend>
    <div class="flex">
      <div class="panel">
        <div class="panel-header">
          Filter
        </div>
        <div class="panel-body">
          <div class="flex justify-space-between flex-wrap">
            <div class="flex justify-start col-100 align-center">
              <div class="filter-label">
                Event
              </div>
              <select id="event">
                <?php foreach ($events as $key => $value): ?>
                  <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex justify-start col-100 align-center">
              <div class="filter-label">
                Course
              </div>
              <select id="course">
                <option value="all">All</option>
                <option value="bsit">BSIT</option>
                <option value="bsit">BSIS</option>
                <option value="bscs">BSCS</option>
                <option value="act">ACT</option>
              </select>
            </div>
            <div class="flex justify-start col-100 align-center">
              <div class="filter-label">
                Month
              </div>
              <select id="month">
                <option value="all">All</option>
                <?php foreach (months() as $value): ?>
                  <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="flex justify-start col-100 align-center">
              <div class="filter-label">
                Year
              </div>
              <select id="year">
                <option value="all">All</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
              </select>
            </div>
            <div class="flex justify-start col-100 align-center">
              <div class="filter-label">
                Status
              </div>
              <select id="status">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="panel">
        <div class="panel-header">
          Search
        </div>
        <div class="panel-body">
          <div class="flex flex-wrap justify-space-between align-center">
            <div class="_filter">
              ID
              <input id="filter_id" type="text" name="" value="">
            </div>
            <div class="_filter">
              Name
              <input id="filter_name" type="text" name="" value="">
            </div>
            <div class="_filter flex justify-start align-center">
              <div style="margin:0 5px;">
                <br>
                <button class="blue" type="button" name="button" onclick="searchThis(dataCollection)">Find</button>
              </div>
              <div style="margin:0 5px;">
                <br>
                <button class="red" type="button" name="button" onclick="clearFilter(dataCollection)">Clear</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </fieldset>

    <div class="panel">
      <div class="panel-header">
        header
      </div>
      <div class="panel-body">
        <div id="col-loader" style="display:none;position:absolute;left:0;right:0;bottom:0;top:0;height:100%;width:100%;background: #fff;z-index: 1;opacity: 0.8;">
          <div class="flex justify-center align-center" style="width: inherit;height: inherit;">
            <img src="img/spinner.svg" alt="" width="50">
          </div>
        </div>
        <table id="studtable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Course</th>
              <!-- <th>Year</th> -->
              <!-- <th>Event</th> -->
              <th>AMT.</th>
              <th>Stat</th>
              <th>Encoded by</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
              <?php foreach($students as $key => $value): ?>
                <?php if ($event!='all'): ?>
                  <?php $payment = getPayment($event,$value['student_id']); ?>
                  <?php $full_name = $value['last_name'].", ".$value['first_name']; ?>
                  <?php $stud_status = getStatus($event,$value['student_id']); ?>
                  <?php $monthPaid = getMonthPaid($event,$value['student_id']); ?>
                  <?php $yearPaid = getYearPaid($event,$value['student_id']); ?>
                  <?php if(empty($stud_status)){$stud_status="unpaid";} ?>
                    <?php if ((strtoupper($stud_status)==strtoupper($status) || $status=="all") && ($monthPaid==$month || $month=="all") && ($yearPaid==$year || $year=="all")): ?>
                      <?php $payment_status = strlen(getEncoder($event,$value['student_id']))?"Paid":"Unpaid" ?>
                      <tr>
                      <td><?php echo $value['student_id'] ?></td>
                      <td><?php echo $full_name ?></td>
                      <td><?php echo $value['course'] ?></td>
                      <td><?php echo $value['_year'] ?></td>
                      <!-- <td><?php echo getEventName($event) ?></td>
                      <td>
                        <img src="img/peso.svg" alt="" width="10">
                        <?php echo sprintf("%.2f",$payment) ?></td> -->
                      <td><?php echo $payment_status ?></td>
                      <td><?php echo getEncoder($event,$value['student_id']) ?></td>
                      <td>
                      <?php if ($payment_status=="Unpaid"): ?>
                        <a class="collect_money" href="#collect" rel="modal:open"
                        sid="<?php echo $value['id'] ?>"
                        student_id = "<?php echo $value['student_id'] ?>"
                         amount="<?php echo $price ?>" event="<?php echo $event ?>" sname="<?php echo $full_name ?>"
                         course="<?php echo $value['course'] ?>"
                         email="<?php echo $value['email'] ?>"
                         year="<?php echo $value['_year'] ?>">
                         <i class="fal fa-check-circle"></i>
                         Collect</a></td>
                     <?php else: ?>
                       <a class="uncollect_money" href="#" data-id="<?php echo getCollectionId($event,$value['student_id']) ?>" email="<?php echo $value['email'] ?>">
                        <i class="fal fa-check-circle"></i>
                        Uncollect</a>
                     <?php endif; ?>
                    </tr>
                  <?php endif; ?>

                <?php endif; ?>
              <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
<script type="text/javascript">
  $("#year").val("<?php echo $year ?>")
  $("#month").val("<?php echo $month ?>")
  $("#course").val("<?php echo $course ?>")
  $("#event").val("<?php echo $event ?>")
  $("#status").val("<?php echo $status ?>")
  $(".collect_money").click(function(){
    form = document.forms["collection"];
    form.full_name.value = $(this).attr("sname");
    form.student_id.value = $(this).attr("student_id");
    form.sid.value = $(this).attr("sid");
    form.amount.value = $(this).attr("amount");
    form.receipt_email.value = $(this).attr("email");
    form.course.value = $(this).attr("course");
    form.year.value = $(this).attr("year");
    form.event.value = $(this).attr("event");
    if($(this).attr("event")=="3"){
      let prices = $(this).attr('amount').split(',');
      form.amount.value = prices[0];
      $('#size').val(prices[0]);
      $('#xsmall').val(prices[0]);
      $('#small').val(prices[1]);
      $('#medium').val(prices[2]);
      $('#large').val(prices[3]);
      $('#xlarge').val(prices[4]);
      $(".se").show();
      $('#size')[0].options.selectedIndex = 0;
    }
    else{
      $(".se").hide();}
  })
  update = function(){
    $("#col-loader").show();
    $("#collection_view").load("collection_list.php?event="+
    $("#event").val()+
    "&course="+
    $("#course").val()+
    "&month="+
    $("#month").val()+
    "&year="+
    $("#year").val()+
    "&status="+
    $("#status").val(),()=>{
      $("#col-loader").hide();
    })
  }

  $("#year").on("change", function(){update();})
  $("#event").on("change", function(){update();})
  $("#course").on("change", function(){update();})
  $("#month").on("change", function(){update();})
  $("#status").on("change", function(){update();})
  $("#size").off()
  $("#size").on("change",function(){
    val = parseFloat($(this).val());
    $("input[name=amount]").val(val)
  })
  $('.uncollect_money').click(function(){
    id = $(this).attr('data-id');
    $.ajax({
      method: "POST",
      data: {
        action: 'refund',
        title: 'Your purchase through <?php echo $labels['text-logo'] ?> has been refunded',
        id: id,
        message: '&nbsp;',
        email: $(this).attr('email')
      },
      url: "/mail.php",
      beforeSend: function(){
        $('#col-loader').show();
      },
      success: function(data){
        e = JSON.parse(data);
        $('#col-loader').hide();
        if(e.error){
          alert(e.error)
        }
        $.ajax({
          method: 'GET',
          url: `/api/send.php?action=delete_collection&id=${id}`,
          success: function(data){
            console.log(data);
            e = JSON.parse(data);
            $('#col-loader').hide();
            if(e.error){
              alert(e.error);
              return;
            }
            update()
          }
        })
      }
    })

  })
  initSearch();
</script>
