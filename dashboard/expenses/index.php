<?php   require("../../config/config.php");
  secure(['admin-only'=>true]);?>
  <div id="expenses-container" class="col-100" style="background: none;">
  </div>

  <script type="text/javascript">
  <?php if ($_SESSION['account']['access']==40): ?>
    load = "expenses/grabexp.php";
    <?php else:?>
    load = "expenses/add.php";
  <?php endif;?>
    $("#expenses-container").load(load,{},function(){
      $(".ajax-link2").click(function(){
        event.preventDefault();
        link = $(this).attr("href");
        id = $(this).attr("cid");
        sem = $(this).attr("sem");
        $.ajax({
          method: "GET",
          url: link+"?action="+$(this).attr("cid")+"&sem="+$(this).attr("sem"),
          beforeSend: function(){
            $("#disp").html("<div class='col-100 flex align-center justify-center' style='background: #fff;'>\
            <img src='img/spinner.svg' width='50'/></div>");
          },
          success: function(data){
            $("#disp").load(link+"?action="+id+"&sem="+sem)
          }
        })
      })
      $("#cashout").click(function(){
        $.ajax({
          method: "GET",
          url: "expenses/add.php",
          beforeSend: function(){
            $("#disp").html("<div class='col-100 flex align-center justify-center' style='background: #fff;'>\
            <img src='img/spinner.svg' width='50'/></div>");
          },
          success: function(data){
            $("#disp").html(data)
          }
        })
      })
      $("#pending-expenses").click(function(){
        $.ajax({
          method: "GET",
          url: "expenses/pending.php",
          beforeSend: function(){
            $("#disp").html("<div class='col-100 flex align-center justify-center' style='background: #fff;'>\
            <img src='img/spinner.svg' width='50'/></div>");
          },
          success: function(data){
            $("#disp").html(data)
          }
        })
      })
      options = function(d){
        let settings = {duration: 200}
        if($(d).css('display')=='none'){
          $(d).slideDown(settings);
          return;
        }
        $(d).slideUp(settings);
      }

    });

  </script>
