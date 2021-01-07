<?php
  require("../config/config.php");
  require("../api/func.php");
  secure(['admin-only'=>true]);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include("head.php") ?>
    <link rel="canonical" href="https://quilljs.com/standalone/full/">
    <link rel="stylesheet" href="/dashboard/css/pell.css">
    <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style media="screen">
      button.pell-button{color: #222!important;padding:0!important;}
      button.pell-button:hover{color: #fff!important;}
      .pell-content {
        white-space: pre-wrap;
      }
    </style>

  </head>
  <body>
  <?php include("main.php") ?>
  <footer style="background-color: #000;">
    <small>STUDENT ORGANIZATION FINANCIAL COLLECTION SYSTEM | MKJ2019</small>
  </footer>
  <div id="contextmenu">
  </div>

  <?php include('../modals/window.php') ?>
  <script type="text/javascript" src="js/main.js"></script>
  <script type="text/javascript">
      function toggleLeftPane(){
        if($('.left-pane').is(':hidden')){
          $(document).scrollTop(0);
          $('body').css('overflow','hidden')
          $('.left-pane').show("slide", { direction: "left" });$('.links').css('max-height',(
            $(window).height()-($('.logo').outerHeight()+$('.user').outerHeight())
          )+'px');$('nav#main').css('margin-left',$('.left-pane').width()+'px');
        } else {
          $('body').css('overflow','auto');
          $('.left-pane').hide("slide", { direction: "left" });
          $('nav#main').css('margin-left','0px')
        }
      }
      checkerror = function() {
        if($("#registerResult").text().replace(/\n/g, '').replace(/ /g,'')<=0){
          $("#registerResult").hide();
        }else {
          $("#registerResult").show();
        }
        if($("#result").text().replace(/\n/g, '').replace(/ /g,'')<=0){
          $("#result").hide();
        }else {
          $("#result").show();
        }
      }
      checkerror();
      $("#register-btn").click(function(){
        $("#registerResult").css("display","block");
      })
      $(document).on("click", function() {
        if($("#registerStudent").css("display")=="none")
        $("#registerResult").hide();
      });
      $("#add_student").click(function() {
        $data = {"action":"add"};
        $("#add input").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $("#add select").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $action($data,"#result","#student_list");
      })
      $("#register-btn").click(function() {
        $data = {"action":"register"};
        $("#register input").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $("#register select").each(function(){
          $data[$(this).attr("name")] = $(this).val()
        })
        $action($data,"#registerResult","td[sid=a"+$("input[id=student_id]").val()+']');
      })
      let searchThis = (e)=>{
        e.search($('#filter_id').val()).draw();
        setlink();
      }
      let clearFilter = (e)=>{
        $('#filter_name').val('');$('#filter_id').val('');
        searchThis(e);
      }
      let initSearch = ()=>{
        $.ajax({
          method: 'GET',
          url: '/dashboard/api/index.php?action=hints&column=full_name',
          success: function(data){
            names = JSON.parse('{"filter":['+data+']}')['filter'];
            $('#filter_name')
              .autocomplete({
                source: JSON.parse('{"filter":['+data+']}')['filter']
              })
                .on('change',()=>{
                  $('#filter_id').val(ids[names.indexOf($('#filter_name').val())])
                })
           }
        })
        $.ajax({
          method: 'GET',
          url: '/dashboard/api/index.php?action=hints&column=student_id',
          success: function(data){
            ids = JSON.parse('{"filter":['+data+']}')['filter'];
            $('#filter_id')
              .autocomplete({
                source: JSON.parse('{"filter":['+data+']}')['filter']
              })
              .on('change',()=>{
                $('#filter_name').val(names[ids.indexOf($('#filter_id').val())])
              })
           }
        })
      }
  </script>
</body>
</html>
