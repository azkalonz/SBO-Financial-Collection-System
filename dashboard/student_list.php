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
  dataStudent = null;
  $(document).ready( function () {
    dataStudent = $('#studtable').DataTable({
      lengthChange: false
    });
  });
  </script>

  <?php
    require("../config/config.php");
    include("../api/func.php");
    $total = 0;
    $sem = 1;
    $cat = 1;
    $showing = "all";
    if(isset($_GET["sem"])){
      $sem = $_GET["sem"];
      $start = 0;
    }
    if(isset($_GET["cat"])){
      $cat = $_GET["cat"];
    }
    if(isset($_GET["showing"])){
      $showing = $_GET["showing"];
    }
    $student = displayStudent($sem);
    $total = sizeof($student);
   ?>
  <?php
    $registered = 0;
    $prevID = -123;
    function checkStatus($id, $sem, $event) {
      global $con;
      global $registered;
      global $prevID;
      $registered_students = $con->prepare("SELECT student FROM psits_events WHERE id = {$event}");
      $registered_students->execute();
      $students = explode(", ",$registered_students->fetch()[0]);
      foreach($students as $k) {
        $blown = explode("->",$k);
        if($blown[0]==$id){
          $registered = $prevID!=$id?$registered+1:$registered;
          $prevID = $id;
          return "Registered";
        }
      }
      return "Unregistered";
    }
    function registrant($id, $sem, $event) {
      global $con;
      $registrant = $con->prepare("SELECT student FROM psits_events WHERE id = {$event}");
      $registrant->execute();
      $students = explode(", ",$registrant->fetch()[0]);
      foreach($students as $k) {
        if(explode("->",$k)[0]==$id)
          return fullName(explode("->",$k)[1]);
      }
      return "None";
    }
   ?>
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
                 Category
               </div>
               <select id="cat">
                 <?php
                 $event_list = $con->prepare("SELECT * FROM psits_events");
                 $event_list->execute();
                 while($e = $event_list->fetch()){
                   ?>
                   <option value="<?php echo $e["id"] ?>"><?php echo $e["name"] ?></option>
                   <?php
                 }
                  ?>
               </select>
             </div>
             <div class="flex justify-start col-100 align-center">
               <div class="filter-label">
                 Status
               </div>
               <input type="hidden" id="sem" value="1">
               <!-- <select id="sem">
                 <option value="1">1st Semester S.Y. 2019-2020</option>
                 <option value="2">2nd Semester S.Y. 2019-2020</option>
               </select> -->
               <select id="showing">
                 <option value="all">All</option>
                 <option value="Unregistered">Unregistered</option>
                 <option value="Registered">Registered</option>
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
                 <button class="blue" type="button" name="button" onclick="searchThis(dataStudent)">Find</button>
               </div>
               <div style="margin:0 5px;">
                 <br>
                 <button class="red" type="button" name="button" onclick="clearFilter(dataStudent)">Clear</button>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </fieldset>

  <div class="panel">
    <div class="panel-header flex justify-space-between">
      <div class="">

      </div>
      <div class="">
        <a href="#addStudent" rel="modal:open">Add Student +</a>
      </div>
    </div>
    <div class="panel-body">
      <table id="studtable" class="display">
        <thead>
          <tr id="loading">
            <td colspan="100%" style="text-align:center;position:absolute;opacity: 0.7;left:0;right:0;bottom:0;top:0;width:100%;height:100%;padding:0!important;">
              <div class="flex align-center justify-center" style="width:100%;background: #fff;height:100%;">
                <img src="img/spinner.svg" alt="" width="70">
              </div>
            </td>
          </tr>

          <!-- <tr>
            <td  style="background: #144763!important; color: #fff;" align="center" colspan="7">
              <div class="box">
                <div class="progress" id="progress">
                 <div class="inner">
                 </div>
               </div>
               Registered : <a id="total_r_students">0</a> of <a><?php echo $total ?></a>
              </div>
            </td>
          </tr> -->
          <tr>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Year</th>
            <th>Status</th>
            <th>Registrant</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($student as $value): ?>
            <?php if(checkStatus($value["student_id"],$value["sem"],$cat)==$showing && $showing!="all"){ ?>
            <tr action="student">
              <td><?php echo $value["student_id"]  ?></td>
              <td><?php echo $value["full_name"]  ?></td>
              <td><?php echo $value["course"]  ?></td>
              <td><?php echo $value["_year"]  ?></td>
              <td sid="<?php echo 'a'.$value["student_id"] ?>">
                <?php echo checkStatus($value["student_id"],$value["sem"],$cat) ?>
              </td>
              <td><?php echo registrant($value["student_id"],$value["sem"],$cat) ?></td>
              <td>
                <a href="#registerStudent" class="set-link" rel="modal:open" uid="<?php echo $value["student_id"] ?>" fullname="<?php echo $value["full_name"] ?>"
                   course="<?php echo $value["course"] ?>" year="<?php echo $value["_year"] ?>" sem2="<?php echo $value["sem"] ?>">
                   <i class="fal fa-check-circle"></i>
                   Register</a>
              </td>
            </tr>
          <?php } else if($showing=="all"){ ?>
            <tr>
              <td><?php echo $value["student_id"]  ?></td>
              <td><?php echo $value["full_name"]  ?></td>
              <td><?php echo $value["course"]  ?></td>
              <td><?php echo $value["_year"]  ?></td>
              <td sid="<?php echo 'a'.$value["student_id"] ?>">
                <?php echo checkStatus($value["student_id"],$value["sem"],$cat) ?>
              </td>
              <td><?php echo registrant($value["student_id"],$value["sem"],$cat) ?></td>
              <td>
                <a href="#registerStudent" class="set-link" rel="modal:open" uid="<?php echo $value["student_id"] ?>" fullname="<?php echo $value["full_name"] ?>"
                   course="<?php echo $value["course"] ?>" year="<?php echo $value["_year"] ?>" sem2="<?php echo $value["sem"] ?>">
                   <i class="fal fa-check-circle"></i>
                   Register</a>
              </td>
            </tr>
          <?php } ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script type="text/javascript">
  ids=[],names=[];
  setLink = null;
    $(document).ready(function() {
      // totalstud = function() {
      //   var circle = new ProgressBar.Circle('#progress', {
      //   color: '#fff',
      //   strokeWidth: 5,
      //   trailWidth: 1,
      //   text: {
      //       value: '0'
      //   },
      //   step: function(state, bar) {
      //       bar.setText((bar.value() * 100).toFixed(0)+"%");
      //    }
      //   });
      //    circle.animate(<?php echo $total==0?0:$registered/$total ?>);
      // }
      // totalstud();
      $(".paginate_button").on("click",function(){
        setlink()
      })
      $('input[type=search]').on('keyup',function(){
        setlink()
      })
      $('select[name=studtable_length]').on('change',function(){
        setlink()
      })
      setlink = function(){
        $(".set-link").on("click", function() {
          $id = $(this).attr("uid");
          $name = $(this).attr("fullname");
          $course = $(this).attr("course");
          $year = $(this).attr("year");
          $sem2 = $(this).attr("sem2");
          $event = $("#cat").val();
          $("input#student_id").val($id);
          $("input#full_name").val($name);
          $("input#course").val($course);
          $("input#year").val($year);
          $("input#sem2").val($sem2);
          $("input#event_name").val($event);
        })
      }
      $(".set-link").on("click", function() {
        $id = $(this).attr("uid");
        $name = $(this).attr("fullname");
        $course = $(this).attr("course");
        $year = $(this).attr("year");
        $sem2 = $(this).attr("sem2");
        $event = $("#cat").val();
        $("input#student_id").val($id);
        $("input#full_name").val($name);
        $("input#course").val($course);
        $("input#year").val($year);
        $("input#sem2").val($sem2);
        $("input#event_name").val($event);
      })
      $("#cat").val("<?php echo $cat ?>");
      $("#sem").val("<?php echo $sem ?>");
      $("#showing").val("<?php echo $showing ?>");
      $update = function() {
        $("tr#loading").show();
        $cat = $("#cat").val();
        $sem = $("#sem").val();
        $showing = $("#showing").val();
        $("#student_list").load("student_list.php?sem="+$sem+"&cat="+$cat+"&showing="+$showing,{},function(){
          $("tr#loading").hide();
        });
      }
      $("#sem").on("change",function() {
        $update();
      })
      $("#showing").on("change",function() {
        $update();
      })
      $("#cat").on("change",function() {
        $update();
      })
      $("#total_r_students").html("<?php echo $registered?>");


  })
  initSearch();
  </script>
