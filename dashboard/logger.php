<?php
$log_views = [
  'added',
  'registered',
  'collected',
  'refunded',
  'modified'
];
 ?>
<div class="logs sm-container show-after">
  <div class="flex col-100 justify-space-between flex-wrap">
    <div>
      <h4 style="position:relative;color: #1492d6;">Recent Activities</h4>
    </div>
    <div style="position: relative;z-index: 1;">
      <input type="text" name="" value="" placeholder="search" id="search-log">
    </div>
    <div class="col-100">
      <a href="javascript:$('#search-log').keyup();$('.log_view').prop('checked',false)">
      <i class="fal fa-sync-alt"></i></a>
      <a href="#" onclick="options('#options')">
      <i class="fal fa-filter"></i></a>
      <a href="#" id="fs">
        <i class="fal fa-compress-alt"></i></a>
      <div id="options" style="display:none;background:#fafafa;box-shadow:0 6.7px 9.4px rgba(0,0,0,0.1);color:#222;padding: 10px;">
        <?php foreach ($log_views as $value): ?>
          <div>
            <input class="log_view" type="checkbox" id="<?php echo "{$value}_view" ?>" value="<?php echo $value ?>">
            <label for="<?php echo "{$value}_view" ?>" style="cursor:pointer;">
              <?php echo ucfirst($value) ?>
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="log-container flex flex-wrap align-content-start">
    <div style="width:100%;height: 100%;position: absolute;top:0;left:0;right:0;bottom:0;" class="flex align-center justify-center" id="loading-logs">
      <img src="img/spinner.svg" width="50" alt="Loading logs...">
    </div>
  </div>
</div>
<script type="text/javascript">
limit = 50;
document.querySelector('#fs').addEventListener("click", function() {
  var el = document.querySelector('.logs'),
    rfs = el.requestFullscreen
      || el.webkitRequestFullScreen
      || el.mozRequestFullScreen
      || el.msRequestFullscreen
  ;

    rfs.call(el);
});
$('input.log_view').click(function(){
  $('#search-log').keyup()
})
$(".log-container").load("logs.php?limit="+limit);
$("#search-log").on("keyup",function() {
  let views = [];
  $(".log-container").html('\
  <div style="width:100%;height: 100%;position: absolute;top:0;left:0;right:0;bottom:0;" class="flex align-center justify-center" id="loading-logs">\
  <img src="img/spinner.svg" width="50" alt="">\
  </div>');
  $("#loading-logs").show();

  $('input.log_view').each(function(){
    if($(this).prop('checked')==true) {
      views.push($(this).val())
    }
  })
  $(".log-container").load("logs.php?limit="+limit+"&search="+$(this).val().replace(' ','_'),{filter: views},function() {
    $("#loading-logs").hide();
  });
})
$(".log-container").on("scroll",function() {
  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight-10){
    $(".logs").removeClass("show-after");
  } else{
    $(".logs").addClass("show-after");
  }
  if($(this).scrollTop()!=0)
  $(".logs").addClass("show-before");
  else
  $(".logs").removeClass("show-before");
})
</script>
