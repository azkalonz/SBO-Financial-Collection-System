<?php include('../config/config.php');
secure(['admin-only'=>true]);
 ?>
<style media="screen">
  table tr {
    cursor: pointer;}
  table tr:hover > td {
    background: #ede5a7!important;
  }
</style>

<div id="back-parent" class="w-100 flex align-items-center" style="display: none;">
  <i id="back" class="fa fa-arrow-circle-left" style="cursor:pointer;color: #fff;margin: 10px; font-size: 2rem;"></i>
  <a id="back-text" style="color: #fff;font-weight:bold;">Go back</a>
</div>
<div id="view" class="flex justify-center align-stretch flex-wrap" style="height: 100vh;width: 100%;">
  <div class="student box flex justify-center align-center">
    <div class="flex justify-space-between">
      <h1>STUDENTS</h1>
      <div>
        <button type="button" name="button" onclick="getStudent('BSIT')">BSIT</button>
        <button type="button" name="button" onclick="getStudent('BSCS')">BSCS</button>
        <button type="button" name="button" onclick="getStudent('ACT')">ACT</button>
        <button type="button" name="button" onclick="getStudent()">All</button>
      </div>
    </div>
  </div>
  <div class="users box justify-space-between align-center">
    <div>
      <button type="button" name="button" onclick="getOfficer('40')">ADMIN</button>
      <button type="button" name="button" onclick="getOfficer('30')">PSITS OFFICERS</button>
    </div>
    <div class="flex justify-end text-right">
      <h1>OFFICERS & TEACHERS</h1>
    </div>

  </div>
</div>

<script type="text/javascript">
   getStudent = (e='')=>{
    let html = $('#view').html();
    $.ajax({
      method: 'GET',
      url: 'students.php?course='+e,
      beforeSend: function(){
        $('#back-parent').css('background','#4075f1');
        $('#back-parent').slideDown();
        $('#back-text').text('Students')
        $('#view').html('<img src="img/spinner.svg" width="100"/>');
      },
      success: function(data){
        $('#back').off();
        $('#back').on('click',function(){
          $('#view').html(html);
          $('#back-parent').slideUp();
        });
        $('#view').html(data);
      }
    })
  }
   getOfficer = (e)=>{
    let html = $('#view').html();
    $.ajax({
      method: 'GET',
      url: 'officers.php?access='+e,
      beforeSend: function(){
        $('#back-parent').css('background','#4075f1');
        $('#back-parent').slideDown();
        $('#back-text').text('Teachers/Officers')
        $('#view').html('<img src="img/spinner.svg" width="100"/>');
      },
      success: function(data){
        $('#back').off();
        $('#back').on('click',function(){
          $('#view').html(html);
          $('#back-parent').slideUp();
        });
        $('#view').html(data);
      }
    })
  }
</script>
